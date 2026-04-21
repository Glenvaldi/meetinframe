<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Template;
use App\Models\History;
use App\Models\User;

// Nanti jika Anda sudah membuat Model Template atau History, import di sini:
// use App\Models\Template;
// use App\Models\SessionHistory;

class ProfileController extends Controller
{
// Jangan lupa: use App\Models\History; di paling atas

    public function creatorDashboard()
    {
        $user = Auth::user();

        $templateCount = Template::where('user_id', $user->id)->count();
        $templates = Template::where('user_id', $user->id)->latest()->get();
        
        // AMBIL HISTORY MILIK USER (Terbaru di atas)
        $histories = History::where('user_id', $user->id)->latest()->get();
        $sessionCount = $histories->count();

        $roleTitle = ($templateCount > 0) ? 'Content Creator' : 'Newbie';

        return view('creator', compact('user', 'templateCount', 'roleTitle', 'sessionCount', 'templates', 'histories'));
    }

    public function deleteHistory($id)
    {
        $history = History::where('id', $id)->where('user_id', Auth::id())->first();

        if ($history) {
            // Hapus file gambar fisik jika ada
            if ($history->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($history->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($history->image_path);
            }
            $history->delete();
            return back()->with('success', 'Riwayat foto berhasil dihapus.');
        }

        return back()->with('error', 'Gagal menghapus data.');
    }

// Tambahkan fungsi baru ini untuk menangani form upload
public function uploadTemplate(Request $request)
{
    $request->validate([
        'template_name' => 'required|string|max:255',
        'layout_type' => 'required|in:3,4,6',
        'price' => 'nullable|numeric',
        'template_file' => 'required|image|mimes:png|max:2048', // Wajib PNG
    ]);

    // Simpan file ke folder 'public/images/templates' atau 'storage'
    // Agar mudah diakses langsung seperti template bawaan, kita taruh di 'public_path' saja
    // atau gunakan Storage link. Mari gunakan Storage 'public' agar konsisten.
    
    $path = $request->file('template_file')->store('templates', 'public');

    Template::create([
        'user_id' => Auth::id(),
        'name' => $request->template_name,
        'image_path' => $path, // Simpan path storage
        'layout_type' => $request->layout_type,
        'price' => $request->price ?? 0,
    ]);

    return back()->with('success', 'Template berhasil dipublish! 🚀');
}

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // --- FITUR KOMUNITAS (PUBLIC) ---

    // 1. Halaman Daftar Semua Creator
    public function communityIndex()
    {
        // Ambil user yang sudah pernah upload template (punya minimal 1 template)
        // Kita juga hitung jumlah templatenya
        $creators = User::has('templates')->withCount('templates')->latest()->get();

        return view('community.index', compact('creators'));
    }

    // 2. Halaman Profil Publik Creator (Detail)
    public function showCreator($id)
    {
        // Ambil data user beserta template-nya
        $creator = User::with(['templates' => function($query) {
            $query->where('is_active', true)->latest();
        }])->findOrFail($id);

        return view('community.show', compact('creator'));
    }
}