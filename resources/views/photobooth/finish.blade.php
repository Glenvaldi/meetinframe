@extends('layouts.app')

@section('title', 'Sesi Selesai - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
    .finish-container { 
        max-width: 900px; 
        margin: 40px auto 80px; 
        padding: 0 20px; 
        text-align: center; 
    }

    /* --- HEADER SECTION --- */
    .finish-header { margin-bottom: 40px; animation: fadeDown 0.8s ease; }
    
    .finish-badge { 
        display: inline-block; background: #fff; border: 3px solid var(--dark); 
        padding: 8px 24px; border-radius: 50px; font-weight: 800; font-size: 0.9rem; 
        color: var(--dark); box-shadow: 4px 4px 0px var(--hot-pink); margin-bottom: 20px; 
        text-transform: uppercase; 
    }
    
    .finish-title { 
        font-family: 'Uncut Sans', sans-serif; font-size: 3.5rem; font-weight: 800; 
        color: var(--dark); text-transform: uppercase; letter-spacing: -2px; margin-bottom: 10px; 
        line-height: 1.1;
    }
    
    .finish-subtitle { 
        font-size: 1.1rem; font-weight: 600; color: #555; max-width: 600px; margin: 0 auto; line-height: 1.6;
    }

    /* --- KARTU HASIL FOTO (YANG BISA DI-DOWNLOAD) --- */
    .result-card-neo { 
        background: #fff; border: 4px solid var(--dark); border-radius: 24px; 
        padding: 25px; box-shadow: 12px 12px 0px #00d2d3; display: inline-block; 
        margin-bottom: 40px; position: relative; max-width: 100%; animation: fadeUp 0.8s ease;
    }
    
    .result-img { 
        max-width: 100%; height: auto; border: 3px solid var(--dark); 
        max-height: 50vh; object-fit: contain; background: #f9f9f9;
    }

    /* --- GROUP TOMBOL UTAMA --- */
    .action-stack {
        display: flex; flex-direction: column; gap: 15px; 
        max-width: 500px; margin: 0 auto 30px; animation: fadeUp 1s ease;
    }

    .btn-action-neo { 
        padding: 16px 20px; border-radius: 15px; border: 3px solid var(--dark); 
        font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.2s; 
        display: flex; align-items: center; justify-content: center; gap: 10px; 
        text-transform: uppercase; box-shadow: 4px 4px 0px var(--dark); text-decoration: none; 
    }
    
    .btn-download { background: var(--hot-pink); color: #fff; }
    .btn-download:hover { background: var(--dark); color: #fff; transform: translate(2px,2px); box-shadow: none; }
    
    .btn-new-photo { background: #ffeaa7; color: var(--dark); }
    .btn-new-photo:hover { background: #f1c40f; transform: translate(2px,2px); box-shadow: none; }

    .btn-home-final { background: #fff; color: var(--dark); }
    .btn-home-final:hover { background: var(--dark); color: #fff; transform: translate(2px,2px); box-shadow: none; }

    /* --- LINK KE DASHBOARD CREATOR --- */
    .creator-check-link {
        display: inline-block;
        margin: 10px auto 40px;
        color: var(--dark);
        font-weight: 800;
        text-decoration: none;
        font-size: 1rem;
        transition: 0.2s;
        border-bottom: 3px solid var(--hot-pink);
        padding-bottom: 4px;
        text-transform: uppercase;
    }
    .creator-check-link:hover { 
        color: var(--hot-pink); 
        transform: translateY(-2px); 
    }

    /* --- VISUAL CIMIT.JPG (GAYA BRUTAL) --- */
    .finish-visual-neo {
        margin: 20px auto 0;
        max-width: 700px;
        animation: fadeUp 1.2s ease;
    }
    
    .finish-img-cimit {
        width: 100%;
        height: auto;
        border: 4px solid var(--dark);
        border-radius: 24px;
        box-shadow: 12px 12px 0px var(--hot-pink);
        transform: rotate(-1deg);
        transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .finish-img-cimit:hover {
        transform: rotate(0) scale(1.02);
        box-shadow: 16px 16px 0px var(--dark);
    }

    /* ANIMASI */
    @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* LAYAR HP */
    @media (max-width: 768px) {
        .finish-title { font-size: 2.2rem; }
        .result-card-neo { padding: 15px; box-shadow: 8px 8px 0px #00d2d3; width: 100%; }
        .btn-action-neo { font-size: 1rem; padding: 14px; }
        .finish-img-cimit { box-shadow: 8px 8px 0px var(--hot-pink); transform: rotate(0); }
        .finish-img-cimit:hover { transform: rotate(0) scale(1); }
    }
</style>
@endsection

@section('content')
<div class="finish-container">
    
    <div class="finish-header">
        <div class="finish-badge">
            <i class="bi bi-check-circle-fill text-success"></i> Sesi Selesai
        </div>
        <h1 class="finish-title">
            Luar Biasa! <br> Momen Terabadikan
        </h1>
        <p class="finish-subtitle">
            Karya Anda telah berhasil disimpan di dalam riwayat. Hasilnya terlihat sangat menarik! Apakah Anda ingin mencoba gaya atau template lainnya?
        </p>
    </div>

    <div class="result-card-neo">
        <img src="{{ request()->input('final_image') ?? session('final_image') ?? asset('images/placeholder-result.png') }}" alt="Hasil Foto" class="result-img" id="final-result-img">
    </div>

    <div class="action-stack">
        <button id="btn-download-final" class="btn-action-neo btn-download">
            <i class="bi bi-download"></i> Unduh Hasil Foto
        </button>

        <a href="{{ route('select.option') }}" class="btn-action-neo btn-new-photo">
            <i class="bi bi-camera-fill"></i> Mulai Sesi Baru
        </a>

        <a href="{{ route('home') }}" class="btn-action-neo btn-home-final">
            <i class="bi bi-house-door-fill"></i> Kembali ke Beranda
        </a>
    </div>

    <a href="{{ route('creator') }}" class="creator-check-link">
        Lihat Riwayat Foto di Dashboard <i class="bi bi-arrow-right"></i>
    </a>

    <div class="finish-visual-neo">
        <img src="{{ asset('images/cimit.jpg') }}" alt="Success Visual" class="finish-img-cimit">
    </div>

</div>
@endsection

@section('js_extra')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Efek ledakan perayaan
    confetti({
        particleCount: 150,
        spread: 80,
        origin: { y: 0.6 },
        colors: ['#ff0055', '#00d2d3', '#1a1a1a', '#f1c40f']
    });

    // Fungsi Download Hasil Foto
    document.getElementById('btn-download-final').addEventListener('click', () => {
        const imgSrc = document.getElementById('final-result-img').src;
        if(imgSrc && imgSrc.startsWith('data:image')) {
            const a = document.createElement('a');
            a.href = imgSrc;
            a.download = `MeetinFrame_${new Date().getTime()}.png`;
            document.body.appendChild(a);
            a.click();
            a.remove();
        } else {
            alert("Sedang memproses gambar, silakan coba lagi.");
        }
    });
});
</script>
@endsection