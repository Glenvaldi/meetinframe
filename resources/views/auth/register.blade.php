@extends('layouts.app')

@section('title', __('Buat Akun Baru') . ' - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .auth-wrapper {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 40px 20px;
    }

    /* Dekorasi Brutal */
    .decor-neo { position: absolute; z-index: 0; pointer-events: none; }
    .decor-1 { top: 10%; left: 5%; font-size: 5rem; color: #00d2d3; transform: rotate(-10deg); }
    .decor-2 { bottom: 10%; right: 5%; font-size: 4rem; color: #ffeaa7; transform: rotate(15deg); }

    /* --- KARTU REGISTER BRUTAL --- */
    .auth-card-neo {
        background: #fff;
        border: 4px solid var(--dark);
        width: 100%;
        max-width: 480px;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 12px 12px 0px #00d2d3;
        position: relative;
        z-index: 1;
        animation: fadeUp 0.6s ease;
    }

    /* --- HEADER --- */
    .auth-header { text-align: center; margin-bottom: 35px; }
    .brand-icon-neo {
        width: 70px; height: 70px; margin: 0 auto 20px;
        background: var(--hot-pink); border: 4px solid var(--dark);
        border-radius: 16px; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 2rem; box-shadow: 4px 4px 0px var(--dark);
        transform: rotate(5deg); transition: 0.3s;
    }
    .auth-card-neo:hover .brand-icon-neo { transform: rotate(0) scale(1.05); }
    
    .auth-title { font-family: 'Uncut Sans', sans-serif; font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 5px; text-transform: uppercase; }
    .auth-desc { color: #555; font-size: 1rem; font-weight: 600; }

    /* --- FORM STYLES --- */
    .form-group { margin-bottom: 20px; position: relative; }
    .form-label { font-size: 0.95rem; font-weight: 800; color: var(--dark); margin-bottom: 8px; display: block; text-transform: uppercase; }
    
    .input-wrap { position: relative; }
    .input-icon {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        font-size: 1.2rem; color: var(--dark); transition: 0.2s; pointer-events: none;
    }
    
    .form-input {
        width: 100%; padding: 15px 15px 15px 50px;
        border-radius: 12px; border: 3px solid var(--dark); background: #f9f9f9;
        font-size: 1rem; color: var(--dark); font-weight: 600; transition: 0.2s;
    }
    .form-input:focus {
        background: #fff; outline: none;
        box-shadow: 4px 4px 0px var(--hot-pink); transform: translate(-2px, -2px);
    }
    .form-input:focus + .input-icon { color: var(--hot-pink); }

    .toggle-eye {
        position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
        color: var(--dark); cursor: pointer; transition: 0.2s; font-size: 1.2rem;
    }
    .toggle-eye:hover { color: var(--hot-pink); transform: translateY(-50%) scale(1.1); }

    /* --- TOMBOL --- */
    .btn-auth-neo {
        width: 100%; padding: 16px; border-radius: 12px; border: 3px solid var(--dark);
        background: #00d2d3; color: var(--dark); font-weight: 800; font-size: 1.1rem; cursor: pointer;
        transition: 0.2s; margin-top: 10px; box-shadow: 6px 6px 0px var(--dark);
        display: flex; align-items: center; justify-content: center; gap: 10px; text-transform: uppercase;
    }
    .btn-auth-neo:hover {
        transform: translate(2px, 2px); box-shadow: none; background: var(--dark); color: #fff;
    }

    /* --- FOOTER CARD --- */
    .auth-footer { margin-top: 25px; text-align: center; font-size: 0.95rem; color: #555; font-weight: 600; }
    .link-primary { color: #00d2d3; font-weight: 800; text-decoration: none; border-bottom: 2px solid transparent; transition: 0.2s; }
    .link-primary:hover { border-color: #00d2d3; color: var(--dark); }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* HP RESPONSIVE */
    @media (max-width: 768px) {
        .decor-neo { display: none; }
        .auth-card-neo { padding: 30px 20px; box-shadow: 8px 8px 0px #00d2d3; border-width: 3px; }
        .auth-title { font-size: 1.6rem; }
        .btn-auth-neo { font-size: 1rem; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
        .form-input { border-width: 3px; padding: 12px 12px 12px 45px;}
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    
    <i class="bi bi-box-fill decor-neo decor-1"></i>
    <i class="bi bi-emoji-smile-fill decor-neo decor-2"></i>

    <div class="auth-card-neo">
        
        <div class="auth-header">
            <div class="brand-icon-neo">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h1 class="auth-title">{{ __('Buat Akun Baru') }}</h1>
            <p class="auth-desc">{{ __('Bergabunglah dengan komunitas kreator MeetinFrame.') }}</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('Nama Lengkap') }}</label>
                <div class="input-wrap">
                    <input type="text" class="form-input" name="name" 
                           placeholder="{{ __('Contoh: Glenvaldi') }}" value="{{ old('name') }}" required>
                    <i class="bi bi-person-fill input-icon"></i>
                </div>
                @error('name') <small style="color:var(--hot-pink); font-weight:800; margin-top:8px; display:block;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('Email Address') }}</label>
                <div class="input-wrap">
                    <input type="email" class="form-input" name="email" 
                           placeholder="{{ __('nama@email.com') }}" value="{{ old('email') }}" required>
                    <i class="bi bi-envelope-fill input-icon"></i>
                </div>
                @error('email') <small style="color:var(--hot-pink); font-weight:800; margin-top:8px; display:block;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('Password') }}</label>
                <div class="input-wrap">
                    <input type="password" class="form-input" id="password" name="password" 
                           placeholder="{{ __('Minimal 8 karakter') }}" required>
                    <i class="bi bi-lock-fill input-icon"></i>
                    <i class="bi bi-eye-slash-fill toggle-eye" onclick="togglePassword('password', this)"></i>
                </div>
                @error('password') <small style="color:var(--hot-pink); font-weight:800; margin-top:8px; display:block;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('Ulangi Password') }}</label>
                <div class="input-wrap">
                    <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" 
                           placeholder="{{ __('Ketik ulang password...') }}" required>
                    <i class="bi bi-lock-fill input-icon"></i>
                    <i class="bi bi-eye-slash-fill toggle-eye" onclick="togglePassword('password_confirmation', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-auth-neo">
                {{ __('Daftar Sekarang') }} <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </form>

        <div class="auth-footer">
            {{ __('Sudah punya akun?') }} <a href="{{ route('login') }}" class="link-primary">{{ __('Login di sini') }}</a>
        </div>
    </div>
</div>
@endsection

@section('js_extra')
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill');
            icon.style.color = "var(--hot-pink)";
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-fill');
            icon.classList.add('bi-eye-slash-fill');
            icon.style.color = "var(--dark)";
        }
    }
</script>
@endsection