<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// Pastikan model User Anda ada dan benar
use App\Models\User; 

class AuthController extends Controller
{
    // --- TAMPILAN ---

    public function login()
    {
        // Arahkan ke view auth/login.blade.php
        return view('auth.login');
    }

    public function register()
    {
        // Arahkan ke view auth/register.blade.php
        return view('auth.register');
    }

    // --- LOGIKA REGISTER (Menyambungkan ke MySQL) ---

    public function registerPost(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // 'confirmed' cocokkan dengan password_confirmation
        ]);

        // 2. Buat User BARU (Menggunakan Eloquent/MySQL)
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash password sebelum disimpan
            ]);

            // Login otomatis setelah register
            Auth::login($user); 

            // Arahkan ke halaman utama photobooth (dashboard)
            return redirect()->route('creator')->with('success', 'Pendaftaran berhasil! Selamat datang.');

        } catch (\Exception $e) {
            // Tangani error database atau lainnya
            return back()->withInput()->with('error', 'Pendaftaran gagal. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }

    // --- LOGIKA LOGIN ---

    public function loginPost(Request $request)
    {
        // 1. Validasi Data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba Otentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Login berhasil, arahkan ke dashboard
            return redirect()->intended(route('creator'))->with('success', 'Login berhasil!');
        }

        // 3. Login Gagal
        return back()->withInput()->with('error', 'Email atau password salah. Silakan coba lagi.');
    }
    
    // --- LOGIKA LOGOUT ---
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }
}