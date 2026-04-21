<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Template;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PhotoboothController extends Controller
{
    // Halaman 1: Halaman utama
    public function index()
    {
        $title = "Selamat Datang di Website Photobooth";
        return view('photobooth.index', compact('title'));
    }

    // Halaman 2: Pilih Frame / Template
    public function selectOption()
    {
        $customOptions = [
            'Custom A' => '4 pose',
            'Custom B' => '3 pose',
            'Custom C' => '2 pose',
            'Custom D' => '6 pose',
        ];

        $creatorTemplates = Template::where('is_active', true)->latest()->get();

        return view('photobooth.select_option', compact('customOptions', 'creatorTemplates'));
    }

    // Halaman 3: Sesi Foto (Start Session)
    public function startSession(Request $request)
    {
        $selectedOption = $request->input('option_type');
        $customType = $request->input('custom_type');
        $template = $request->input('template');
        $poseCount = $request->input('pose_count');

        if ($selectedOption === 'custom') {
            $poseCount = match($customType) {
                'Custom A' => 4,
                'Custom B' => 3,
                'Custom C' => 2,
                'Custom D' => 6,
                default => 3,
            };
        }

        $filters = [
            'filter_1' => 'tanpa efek',
            'filter_2' => 'b&w',
            'filter_3' => 'soft',
            'filter_4' => 'sepia',
            'filter_5' => 'vivid',
        ];

        $timer = 3;

        return view('photobooth.start_session', compact(
            'selectedOption', 'customType', 'template', 'poseCount', 'filters', 'timer'
        ));
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $imageName);
        session(['uploadedImage' => 'uploads/'.$imageName]);

        return redirect()->route('customize.frame');
    }

    public function customizeFrame(Request $request)
    {
        $capturedImages = [];
        $imageDataJson = $request->input('image_data');
        
        if ($imageDataJson) {
            $decoded = json_decode($imageDataJson, true);
            if (is_array($decoded) && count($decoded) > 0) $capturedImages = $decoded;
        } elseif (session('uploadedImage')) {
            $uploaded = session('uploadedImage');
            $url = Str::startsWith($uploaded, 'data:') ? $uploaded : asset($uploaded);
            $capturedImages = [$url];
        }

        if (empty($capturedImages)) {
            return redirect()->route('select.option')->with('error', 'Gagal memuat foto.');
        }

        $templateName = $request->input('template') ?? 'Custom';
        $overlayUrl = null;
        $dbTemplate = Template::where('name', $templateName)->first();

        if ($dbTemplate) {
            $overlayUrl = asset('storage/' . $dbTemplate->image_path);
        } else {
            $hardcoded = [
                'Template 1' => asset('images/templates/frame_template1.png'),
                'Template 2' => asset('images/templates/frame_template2.png'),
                'Template 3' => asset('images/templates/frame_template3.png'),
                'Template 4' => asset('images/templates/frame_template4.png'),
                'Template 5' => asset('images/templates/frame_template5.png'),
                'Template 6' => asset('images/templates/frame_template6.png'),
                'Template 7' => asset('images/templates/frame_template7.png'),
                'Template 8' => asset('images/templates/frame_template8.png'),
            ];
            $overlayUrl = $hardcoded[$templateName] ?? null;
        }

        return view('photobooth.customize_frame', [
            'capturedImages' => $capturedImages,
            'template' => $templateName,
            'overlayUrl' => $overlayUrl
        ]);
    }

    public function finish(Request $request)
    {
        if (Auth::check()) {
            $imagePath = null;
            if ($request->has('final_image')) {
                $data = $request->input('final_image');
                
                // Perbaikan cara decode yang lebih aman (sama seperti di QR Code)
                $image_parts = explode(";base64,", $data);
                $image_base64 = count($image_parts) >= 2 ? base64_decode($image_parts[1]) : base64_decode($data);
                
                $imageName = 'history_' . time() . '_' . Str::random(10) . '.png';
                
                \Illuminate\Support\Facades\Storage::disk('public')->put('histories/' . $imageName, $image_base64);
                $imagePath = 'histories/' . $imageName;
            }

            History::create([
                'user_id' => Auth::id(),
                'image_path' => $imagePath
            ]);
        }
        return view('photobooth.finish');
    }

    // ==========================================
    // --- FITUR BARU: FOTO COUPLE MULTIPLAYER ---
    // ==========================================

    public function coupleLobby()
    {
        $title = "Lobby Foto Couple";
        return view('photobooth.couple_lobby', compact('title'));
    }

    public function createRoom(Request $request)
    {
        $request->validate([
            'pose_count' => 'required|in:2,3,4,6'
        ]);

        $roomCode = strtoupper(Str::random(6));

        Cache::put('room_' . $roomCode, [
            'pose_count' => $request->pose_count,
            'created_at' => now(),
        ], now()->addHours(2));

        return redirect()->route('couple.session', ['room_code' => $roomCode]);
    }

    public function joinRoom(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string|max:10'
        ]);

        $roomCode = strtoupper(trim($request->room_code));

        if (Cache::has('room_' . $roomCode)) {
            return redirect()->route('couple.session', ['room_code' => $roomCode]);
        }

        return back()->with('error', 'Kode Room tidak ditemukan atau sudah berakhir.');
    }

    public function coupleSession($room_code)
    {
        $roomData = Cache::get('room_' . $room_code);
        
        if (!$roomData) {
            return redirect()->route('couple.lobby')->with('error', 'Room tidak valid atau sudah berakhir.');
        }

        $poseCount = $roomData['pose_count'];
        $title = "Room: " . $room_code;

        return view('photobooth.couple_session', compact('room_code', 'poseCount', 'title'));
    }

    // ==========================================
    // --- FITUR UPLOAD CEPAT UNTUK QR CODE ---
    // ==========================================
    public function uploadQrImage(Request $request)
    {
        $data = $request->input('image');
        
        // CARA BARU YANG LEBIH AMAN: 
        // Pisahkan header "data:image/...;base64," apapun formatnya (PNG/JPEG)
        $image_parts = explode(";base64,", $data);
        
        if (count($image_parts) >= 2) {
            $image_base64 = base64_decode($image_parts[1]);
        } else {
            $image_base64 = base64_decode($data); // Fallback
        }

        // Karena ukurannya kecil, kita simpan sebagai .jpg
        $imageName = 'qr_' . time() . '_' . Str::random(5) . '.jpg';

        $path = public_path('uploads/qrs');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($path . '/' . $imageName, $image_base64);

        // Ambil URL default dari Laravel
        $url = asset('uploads/qrs/' . $imageName);
        
        // TRIK ANTI NGROK ERROR: 
        // Paksa URL jadi https:// jika diakses menggunakan Ngrok
        if (request()->header('x-forwarded-proto') === 'https') {
            $url = str_replace('http://', 'https://', $url);
        }

        return response()->json(['url' => $url]);
    }
}