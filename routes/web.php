<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;    
use Illuminate\Support\Facades\App;        
use Illuminate\Support\Facades\Redirect;   
use App\Http\Controllers\PhotoboothController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// ==========================================
// --- RUTE GANTI BAHASA ---
// ==========================================
Route::get('lang/{locale}', function ($locale) {
    // Hanya izinkan bahasa id, en, dan ko
    if (in_array($locale, ['id', 'en', 'ko'])) {
        Session::put('locale', $locale);
    }
    return Redirect::back();
});

// ==========================================
// --- RUTE UTAMA ---
// ==========================================
Route::get('/', [PhotoboothController::class, 'index'])->name('home');
Route::get('/select-option', [PhotoboothController::class, 'selectOption'])->name('select.option');
Route::post('/upload-photo', [PhotoboothController::class, 'uploadPhoto'])->name('upload.photo');

// --- PENYELAMAT ERROR 405 (GET METHOD) ---
// Jika user me-refresh atau ganti bahasa di tengah sesi foto, 
// kembalikan mereka ke halaman pilih frame secara otomatis.
Route::get('/start-session', function () {
    return redirect()->route('select.option');
});
Route::post('/start-session', [PhotoboothController::class, 'startSession'])->name('start.session');

Route::match(['GET','POST'], '/customize-frame', [PhotoboothController::class, 'customizeFrame'])->name('customize.frame');

// --- PENYELAMAT ERROR 405 (GET METHOD) UNTUK HALAMAN FINISH ---
Route::get('/finish', function () {
    return redirect()->route('home');
});
Route::post('/finish', [PhotoboothController::class, 'finish'])->name('finish');

// Rute untuk proses upload gambar dari QR Code
Route::post('/upload-qr-image', [PhotoboothController::class, 'uploadQrImage'])->name('upload.qr.image');

// ==========================================
// --- FOTO COUPLE (MULTIPLAYER) ---
// ==========================================
Route::get('/couple-lobby', [PhotoboothController::class, 'coupleLobby'])->name('couple.lobby');
Route::post('/couple/create-room', [PhotoboothController::class, 'createRoom'])->name('couple.create');
Route::post('/couple/join-room', [PhotoboothController::class, 'joinRoom'])->name('couple.join');
Route::get('/couple-session/{room_code}', [PhotoboothController::class, 'coupleSession'])->name('couple.session');

// ==========================================
// --- AUTENTIKASI ---
// ==========================================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// --- KOMUNITAS & CREATOR ---
// ==========================================
// (Bisa diakses publik)
Route::get('/community', [ProfileController::class, 'communityIndex'])->name('community.index');
Route::get('/creator/{id}', [ProfileController::class, 'showCreator'])->name('community.show');

// (Hanya bisa diakses jika sudah login)
Route::get('/creator', [ProfileController::class, 'creatorDashboard'])
    ->middleware('auth')
    ->name('creator');

Route::post('/profile/update', [ProfileController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('profile.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/creator-dashboard', [ProfileController::class, 'creatorDashboard'])->name('creator.dashboard');
    Route::post('/creator/upload-template', [ProfileController::class, 'uploadTemplate'])->name('creator.upload');
    Route::delete('/creator/history/{id}', [ProfileController::class, 'deleteHistory'])->name('creator.delete_history');
});