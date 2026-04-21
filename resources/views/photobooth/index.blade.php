@extends('layouts.app')

@section('title', __('Beranda') . ' - MeetinFrame Photobooth')

@section('head_extra')
<style>
    /* --- HERO NEO-BRUTALISM --- */
    .hero-neo {
        padding: 60px 20px 100px;
        text-align: center;
        position: relative;
        z-index: 1;
        max-width: 1200px;
        margin: 0 auto;
    }

    .hero-badge-neo {
        display: inline-block;
        background: #fff;
        border: 3px solid var(--dark);
        padding: 8px 24px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1rem;
        color: var(--dark);
        box-shadow: 4px 4px 0px var(--dark);
        margin-bottom: 30px;
        transform: rotate(-3deg);
        transition: 0.2s;
    }
    .hero-badge-neo:hover { transform: rotate(0deg) scale(1.05); }

    .hero-title-neo {
        font-family: 'Uncut Sans', sans-serif;
        font-size: 4.5rem;
        font-weight: 800;
        line-height: 1.05;
        color: var(--dark);
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: -2px;
    }
    
    .hero-title-neo span {
        color: var(--hot-pink);
        text-shadow: 4px 4px 0px var(--dark);
        display: inline-block;
        transform: rotate(2deg);
    }

    .hero-desc-neo {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        max-width: 650px;
        margin: 0 auto 40px;
        line-height: 1.6;
    }

    .hero-img-wrapper {
        margin-top: 60px;
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 900px;
    }

    .hero-img-neo {
        width: 100%;
        height: auto;
        border: 4px solid var(--dark);
        border-radius: 24px;
        box-shadow: 15px 15px 0px var(--hot-pink);
        transform: rotate(1deg);
        transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .hero-img-wrapper:hover .hero-img-neo {
        transform: rotate(0deg) scale(1.02);
        box-shadow: 20px 20px 0px var(--dark);
    }

    /* Teks Berjalan (Marquee) */
    .marquee-container {
        background: var(--dark);
        color: var(--soft-pink);
        padding: 15px 0;
        border-top: 4px solid var(--dark);
        border-bottom: 4px solid var(--dark);
        overflow: hidden;
        white-space: nowrap;
        display: flex;
        transform: rotate(-2deg) scale(1.05);
        margin: 60px 0;
        box-shadow: 0 10px 0 rgba(0,0,0,0.1);
    }
    
    .marquee-content {
        display: inline-block;
        animation: marquee 15s linear infinite;
        font-weight: 800;
        font-size: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .marquee-content span { margin: 0 30px; }

    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* --- STEPS (CARA MAIN) --- */
    .section-steps-neo {
        padding: 80px 20px;
        max-width: 1150px;
        margin: 0 auto;
    }

    .section-title-neo {
        font-family: 'Uncut Sans', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 60px;
        text-transform: uppercase;
        color: var(--dark);
    }

    .step-grid-neo {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .step-card-neo {
        background: #fff;
        border: 4px solid var(--dark);
        border-radius: 20px;
        padding: 40px 30px 30px;
        box-shadow: 8px 8px 0px var(--dark);
        transition: 0.3s;
        position: relative;
    }
    
    .step-card-neo:nth-child(1) { background: #ffeaa7; } /* Kuning */
    .step-card-neo:nth-child(2) { background: #81ecec; } /* Cyan */
    .step-card-neo:nth-child(3) { background: #ffb8b8; } /* Merah Muda */

    .step-card-neo:hover {
        transform: translateY(-10px);
        box-shadow: 12px 12px 0px var(--dark);
    }

    .step-num-badge {
        position: absolute;
        top: -25px;
        left: -20px;
        background: var(--hot-pink);
        color: white;
        width: 60px;
        height: 60px;
        border: 4px solid var(--dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 800;
        transform: rotate(-10deg);
        box-shadow: 4px 4px 0px var(--dark);
    }

    .step-card-neo h3 { font-weight: 800; font-size: 1.5rem; margin-bottom: 15px; color: var(--dark); }
    .step-card-neo p { font-weight: 600; font-size: 1.05rem; color: #333; line-height: 1.5; margin: 0; }

    /* --- FEATURE ZIG-ZAG --- */
    .feature-neo {
        padding: 80px 20px;
        display: flex;
        align-items: center;
        gap: 60px;
        max-width: 1150px;
        margin: 0 auto;
    }
    .feature-neo.reverse { flex-direction: row-reverse; }

    .f-content-neo { flex: 1; }
    .f-image-neo { flex: 1; position: relative; width: 100%; }

    .f-title-neo {
        font-family: 'Uncut Sans', sans-serif;
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.1;
        text-transform: uppercase;
        color: var(--dark);
    }

    .f-text-neo {
        font-size: 1.15rem;
        font-weight: 600;
        color: #444;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .f-img-brutal {
        width: 100%;
        height: auto;
        border: 4px solid var(--dark);
        border-radius: 30px;
        box-shadow: 12px 12px 0px var(--dark);
        transition: 0.3s;
    }
    
    .feature-neo:nth-child(even) .f-img-brutal { transform: rotate(2deg); box-shadow: -12px 12px 0px var(--hot-pink); }
    .feature-neo:nth-child(odd) .f-img-brutal { transform: rotate(-2deg); box-shadow: 12px 12px 0px #00d2d3; }

    .f-img-brutal:hover { transform: rotate(0) scale(1.03); }

    /* =========================================
       PENGATURAN KHUSUS LAYAR HP (MOBILE FIX)
       ========================================= */
    @media (max-width: 992px) {
        .hero-neo { padding: 40px 15px 60px; }
        .hero-title-neo { font-size: 2.8rem; letter-spacing: -1px; }
        .hero-desc-neo { font-size: 1.05rem; }
        
        .hero-img-neo { 
            border-width: 3px; 
            box-shadow: 8px 8px 0px var(--hot-pink); 
            border-radius: 16px;
        }

        .marquee-content { font-size: 1.2rem; }
        
        .section-title-neo { font-size: 2.2rem; margin-bottom: 50px; }

        .feature-neo { 
            flex-direction: column !important; 
            text-align: center; 
            gap: 40px; 
            padding: 60px 15px; 
        }
        
        .f-title-neo { font-size: 2.2rem; }
        .f-img-brutal { 
            transform: rotate(0) !important; 
            box-shadow: 8px 8px 0px var(--dark) !important; 
            border-radius: 16px;
        }
    }
</style>
@endsection

@section('content')

<section class="hero-neo">
    <div class="hero-badge-neo">
        <i class="bi bi-rocket-takeoff-fill text-danger"></i> {{ __('TANPA INSTALASI APLIKASI') }}
    </div>
    
    <h1 class="hero-title-neo">
        {{ __('Abadikan Momen Seru') }} <br> <span>{{ __('Lebih Estetik!') }}</span>
    </h1>
    
    <p class="hero-desc-neo">
        {{ __('MeetinFrame menghadirkan pengalaman studio photobooth langsung di browsermu. Pilih frame favorit, tambahkan stiker menarik, dan unduh hasil foto resolusi tinggi secara gratis!') }}
    </p>
    
    <a href="{{ route('select.option') }}" class="btn-neo-main" style="font-size: 1.2rem; padding: 15px 35px;">
        <i class="bi bi-camera-fill me-2"></i> {{ __('MULAI FOTO SEKARANG') }}
    </a>

    <div class="hero-img-wrapper">
        <img src="{{ asset('images/wallpaperawal.jpg') }}" alt="MeetinFrame App Preview" class="hero-img-neo">
    </div>
</section>

<div class="marquee-container">
    <div class="marquee-content">
        <span>📸 {{ __('100% GRATIS') }}</span>
        <span>✨ {{ __('FILTER ESTETIK') }}</span>
        <span>🎨 {{ __('BERAGAM FRAME') }}</span>
        <span>🔥 {{ __('TANPA WATERMARK') }}</span>
        <span>🚀 {{ __('SUPER CEPAT') }}</span>
        <span>📸 {{ __('100% GRATIS') }}</span>
        <span>✨ {{ __('FILTER ESTETIK') }}</span>
        <span>🎨 {{ __('BERAGAM FRAME') }}</span>
        <span>🔥 {{ __('TANPA WATERMARK') }}</span>
        <span>🚀 {{ __('SUPER CEPAT') }}</span>
    </div>
</div>

<section class="section-steps-neo">
    <h2 class="section-title-neo">{{ __('Hanya 3 Langkah Mudah! ⚡') }}</h2>
    
    <div class="step-grid-neo">
        <div class="step-card-neo">
            <div class="step-num-badge">1</div>
            <h3>{{ __('Pilih Frame') }}</h3>
            <p>{{ __('Tentukan jumlah pose dan pilih desain frame dari koleksi Official atau buatan Kreator lain yang paling sesuai dengan gayamu hari ini.') }}</p>
        </div>

        <div class="step-card-neo">
            <div class="step-num-badge" style="background: #00d2d3;">2</div>
            <h3>{{ __('Siapkan Pose') }}</h3>
            <p>{{ __('Siapkan gaya terbaikmu! Timer otomatis akan membantu mengambil foto secara berurutan dengan sempurna melalui webcam atau kamera HP.') }}</p>
        </div>

        <div class="step-card-neo">
            <div class="step-num-badge" style="background: var(--dark);">3</div>
            <h3>{{ __('Hias & Simpan') }}</h3>
            <p>{{ __('Tambahkan stiker lucu, sesuaikan warna latar belakang, lalu unduh atau bagikan hasilnya ke media sosial dengan mudah!') }}</p>
        </div>
    </div>
</section>

<section class="feature-neo">
    <div class="f-image-neo">
        <img src="{{ asset('images/cobasekarang.jpg') }}" class="f-img-brutal" alt="Fitur Studio">
    </div>
    <div class="f-content-neo">
        <h2 class="f-title-neo">{!! __('Studio Foto Pribadi <br> Kapan Saja') !!}</h2>
        <p class="f-text-neo">
            {{ __('Tidak perlu repot mengantre panjang atau membayar mahal. Gunakan MeetinFrame untuk mengabadikan momen bersama sahabat atau pasanganmu. Fitur *Auto Remove BG* membuat fotomu tampil estetik layaknya di studio profesional!') }}
        </p>
        <a href="{{ route('select.option') }}" class="btn-neo-main">
            {{ __('COBA SEKARANG') }} <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

<section class="feature-neo reverse" style="padding-bottom: 100px;">
    <div class="f-image-neo">
        <img src="{{ asset('images/comunity.jpg') }}" class="f-img-brutal" alt="Fitur Komunitas">
    </div>
    <div class="f-content-neo">
        <h2 class="f-title-neo">{!! __('Beragam Template <br> Karya Komunitas') !!}</h2>
        <p class="f-text-neo">
            {{ __('Bosan dengan frame yang biasa saja? Kunjungi "Community Hub" untuk menemukan template unik buatan kreator lain. Kamu juga bisa ikut berpartisipasi dengan membagikan karya frame buatanmu sendiri!') }}
        </p>
        <a href="{{ route('community.index') }}" class="btn-neo-main" style="background: var(--dark); color: white;">
            {{ __('JELAJAHI KOMUNITAS') }} <i class="bi bi-globe"></i>
        </a>
    </div>
</section>

@endsection