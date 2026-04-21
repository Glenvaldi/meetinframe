@extends('layouts.app')

@section('title', __('Finalisasi Foto') . ' - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
    /* Background sudah diatur dari app.blade.php */
    .customize-container { max-width: 1280px; margin: 30px auto 60px; padding: 0 20px; }

    /* --- HEADER NEO-BRUTALISM --- */
    .page-header-neo { text-align: center; margin-bottom: 50px; animation: fadeDown 0.8s ease; }
    
    .page-badge-neo { 
        display: inline-block; background: #fff; border: 3px solid var(--dark); 
        color: var(--dark); font-weight: 800; font-size: 0.9rem; padding: 6px 20px; 
        border-radius: 50px; margin-bottom: 15px; text-transform: uppercase; 
        letter-spacing: 1px; box-shadow: 4px 4px 0px var(--hot-pink); transform: rotate(-2deg);
    }
    
    .page-title-neo { 
        font-family: 'Uncut Sans', sans-serif; font-size: 3.5rem; font-weight: 800; 
        color: var(--dark); margin-bottom: 10px; text-transform: uppercase; letter-spacing: -1px;
    }
    
    .page-subtitle-neo { 
        color: #444; font-size: 1.1rem; font-weight: 600; 
    }

    /* --- MAIN GRID --- */
    .customize-grid { 
        display: grid; grid-template-columns: 1fr 420px; gap: 40px; 
        align-items: start; animation: fadeUp 0.8s ease; 
    }

    /* --- CANVAS BOX BRUTAL --- */
    .canvas-box-neo {
        background: #fff; border-radius: 24px; padding: 40px; 
        border: 4px solid var(--dark); box-shadow: 8px 8px 0px var(--dark);
        display: flex; justify-content: center; align-items: center; position: relative; min-height: 600px;
        /* Pola grid kotak-kotak ala buku tulis */
        background-image: linear-gradient(var(--dark) 1px, transparent 1px), linear-gradient(90deg, var(--dark) 1px, transparent 1px);
        background-size: 40px 40px;
        background-position: center;
    }
    
    /* Frame tempat foto berada */
    .canvas-wrapper {
        background: #fff;
        padding: 15px;
        border: 4px solid var(--dark);
        box-shadow: 12px 12px 0px var(--hot-pink);
        transform: rotate(1deg);
        transition: 0.3s ease;
    }
    .canvas-wrapper:hover { transform: rotate(0deg) scale(1.02); box-shadow: 15px 15px 0px var(--dark); }
    
    #photostrip-canvas { max-width: 100%; height: auto; display: block; border: 2px solid var(--dark); }

    /* --- TOOLS PANEL BRUTAL --- */
    .tools-panel-neo { 
        background: #fff; padding: 30px; border-radius: 24px; 
        border: 4px solid var(--dark); box-shadow: 8px 8px 0px var(--dark);
        position: sticky; top: 120px; 
    }
    
    .panel-header-neo { 
        display: flex; align-items: center; gap: 15px; margin-bottom: 30px; 
        padding-bottom: 20px; border-bottom: 4px solid var(--dark); 
    }
    .panel-icon-neo { 
        width: 50px; height: 50px; background: #00d2d3; color: var(--dark); 
        border: 3px solid var(--dark); border-radius: 12px; display: flex; 
        align-items: center; justify-content: center; font-size: 1.5rem; 
        box-shadow: 3px 3px 0px var(--dark);
    }
    .panel-title-neo { 
        font-family: 'Uncut Sans', sans-serif; font-size: 1.5rem; 
        font-weight: 800; color: var(--dark); margin: 0; text-transform: uppercase; 
    }

    .option-group { margin-bottom: 30px; }
    .group-label { 
        font-size: 0.9rem; font-weight: 800; color: var(--dark); 
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: block; 
    }

    /* Warna Pemilihan Brutal */
    .color-grid { display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-color { 
        width: 40px; height: 40px; border-radius: 50%; cursor: pointer; 
        border: 3px solid var(--dark); transition: 0.2s; position: relative; 
        box-shadow: 2px 2px 0px var(--dark);
    }
    .btn-color:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px var(--dark); }
    .btn-color.active { 
        border-color: var(--dark); transform: translate(-2px, -2px); 
        box-shadow: 0 0 0 4px #fff, 0 0 0 7px var(--dark); 
    }
    
    /* Stiker Brutal */
    .sticker-grid { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-sticker { 
        background: #fff; border: 3px solid var(--dark); padding: 8px 16px; 
        border-radius: 12px; font-size: 0.9rem; font-weight: 800; color: var(--dark); 
        cursor: pointer; transition: 0.2s; box-shadow: 2px 2px 0px var(--dark);
        text-transform: uppercase;
    }
    .btn-sticker:hover { background: #ffeaa7; transform: translate(-2px, -2px); box-shadow: 4px 4px 0px var(--dark); }
    .btn-sticker.active { background: var(--dark); color: #fff; box-shadow: 0px 0px 0px var(--dark); transform: translate(2px, 2px); }

    /* --- ACTION BUTTONS BRUTAL --- */
    .btn-action-neo { 
        width: 100%; padding: 16px; border-radius: 15px; border: 3px solid var(--dark); 
        font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.2s; 
        display: flex; align-items: center; justify-content: center; gap: 10px; 
        margin-bottom: 15px; text-transform: uppercase; box-shadow: 4px 4px 0px var(--dark);
    }
    
    .btn-download { background: #ffeaa7; color: var(--dark); }
    .btn-download:hover { background: #f1c40f; transform: translate(2px,2px); box-shadow: none; }
    
    .btn-qr { background: #00d2d3; color: var(--dark); }
    .btn-qr:hover { background: var(--dark); color: #fff; transform: translate(2px,2px); box-shadow: none; }

    .btn-finish { background: var(--hot-pink); color: #fff; font-size: 1.2rem; padding: 18px; }
    .btn-finish:hover { background: var(--dark); color: #fff; transform: translate(2px,2px); box-shadow: none; }
    
    .btn-back-text-neo { 
        display: block; text-align: center; color: var(--dark); font-size: 1rem; 
        text-decoration: none; margin-top: 20px; font-weight: 800; transition: 0.2s; 
        text-transform: uppercase; padding: 10px; border: 3px solid transparent; border-radius: 50px;
    }
    .btn-back-text-neo:hover { border-color: var(--dark); background: #fff; box-shadow: 4px 4px 0px var(--hot-pink); transform: translate(-2px, -2px); }
    
    .hidden-panel { display: none !important; }

    /* --- MODAL QR CODE BRUTAL --- */
    .qr-modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px);
        display: flex; justify-content: center; align-items: center; z-index: 9999;
        opacity: 0; pointer-events: none; transition: 0.3s ease;
    }
    .qr-modal-overlay.active { opacity: 1; pointer-events: auto; }
    
    .qr-modal-box-neo {
        background: #fff; padding: 40px; border-radius: 24px; text-align: center;
        border: 4px solid var(--dark); box-shadow: 12px 12px 0px var(--hot-pink);
        transform: translateY(30px); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        max-width: 90%; width: 400px;
    }
    .qr-modal-overlay.active .qr-modal-box-neo { transform: translateY(0); }
    
    .qr-code-wrapper { 
        background: #fff; padding: 20px; border-radius: 15px; border: 4px solid var(--dark); 
        display: inline-block; margin: 25px 0; box-shadow: 6px 6px 0px var(--dark);
    }
    .qr-title { font-family: 'Uncut Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; text-transform: uppercase; }
    .qr-desc { font-size: 1rem; font-weight: 600; color: #444; margin-bottom: 0; }
    
    .btn-close-qr { 
        background: #fff; border: 3px solid var(--dark); padding: 12px 20px; 
        border-radius: 50px; font-weight: 800; color: var(--dark); cursor: pointer; 
        transition: 0.2s; width: 100%; text-transform: uppercase; box-shadow: 4px 4px 0px var(--dark);
    }
    .btn-close-qr:hover { background: var(--hot-pink); color: white; transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark); }

    @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* =========================================
       PENGATURAN KHUSUS LAYAR HP (MOBILE FIX)
       ========================================= */
    @media (max-width: 992px) { 
        .page-header-neo { margin-bottom: 30px; }
        .page-title-neo { font-size: 2.2rem; }
        
        .customize-grid { grid-template-columns: 1fr; gap: 30px; } 
        
        .tools-panel-neo { position: static; order: 2; padding: 20px; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); } 
        .canvas-box-neo { order: 1; min-height: 400px; padding: 20px; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); background-size: 20px 20px;} 
        
        .canvas-wrapper { padding: 10px; border-width: 3px; box-shadow: 8px 8px 0px var(--hot-pink); transform: rotate(0deg); }
        .canvas-wrapper:hover { transform: rotate(0deg) scale(1); box-shadow: 8px 8px 0px var(--hot-pink); }
        
        .btn-action-neo { padding: 14px; font-size: 1rem; border-width: 3px; box-shadow: 3px 3px 0px var(--dark); }
        .btn-finish { font-size: 1.1rem; }
        
        .qr-modal-box-neo { padding: 30px 20px; border-width: 3px; box-shadow: 6px 6px 0px var(--hot-pink); width: 95%; }
        .qr-title { font-size: 1.5rem; }
    }
</style>
@endsection

@section('content')
@php
    $capturedImages = $capturedImages ?? [];
    $template = $template ?? 'Custom';
    $poseCount = count($capturedImages);
@endphp

<div class="qr-modal-overlay" id="qr-modal">
    <div class="qr-modal-box-neo">
        <h3 class="qr-title">{{ __('Pindai QR Code') }}</h3>
        <p class="qr-desc">{{ __('Pindai QR Code ini menggunakan perangkat Anda untuk mengunduh hasil secara langsung.') }}</p>
        <div class="qr-code-wrapper" id="qr-code-container"></div>
        <button class="btn-close-qr" id="btn-close-qr">{{ __('Tutup') }}</button>
    </div>
</div>

<div class="customize-container">
    <div class="page-header-neo">
        <div class="page-badge-neo">
            <i class="bi bi-stars text-danger"></i> {{ __('Langkah Terakhir') }}
        </div>
        <h1 class="page-title-neo">{{ __('Finalisasi Hasil Foto!') }}</h1>
        <div class="page-subtitle-neo">{{ __('Layout:') }} <strong>{{ ucfirst($template) }}</strong> • {{ $poseCount }} {{ __('Jepretan') }}</div>
    </div>

    <div class="customize-grid">
        
        <div class="canvas-box-neo">
            <div class="canvas-wrapper">
                <canvas id="photostrip-canvas"></canvas>
            </div>
        </div>

        <div class="tools-panel-neo">
            <div id="customization-controls">
                <div class="panel-header-neo">
                    <div class="panel-icon-neo"><i class="bi bi-palette-fill"></i></div>
                    <h3 class="panel-title-neo">{{ __('Kustomisasi Desain') }}</h3>
                </div>

                <div class="option-group">
                    <span class="group-label">{{ __('Warna Bingkai') }}</span>
                    <div class="color-grid" id="frame-color-options">
                        <div class="btn-color active" data-color="#ffffff" style="background:#ffffff;"></div>
                        <div class="btn-color" data-color="#000000" style="background:#000000;"></div>
                        <div class="btn-color" data-color="#ff0055" style="background:#ff0055;"></div>
                        <div class="btn-color" data-color="#f1c40f" style="background:#f1c40f;"></div>
                        <div class="btn-color" data-color="#00d2d3" style="background:#00d2d3;"></div>
                        <div class="btn-color" data-color="#3498db" style="background:#3498db;"></div>
                        <div class="btn-color" data-color="#9b59b6" style="background:#9b59b6;"></div>
                    </div>
                </div>

                <div class="option-group">
                    <span class="group-label">{{ __('Warna Latar Foto') }}</span>
                    <div class="color-grid" id="photo-bg-color-options">
                        <div class="btn-color active" data-color="#e0e0e0" style="background:#e0e0e0;"></div> 
                        <div class="btn-color" data-color="#ffffff" style="background:#ffffff;"></div>
                        <div class="btn-color" data-color="#ffeaa7" style="background:#ffeaa7;"></div>
                        <div class="btn-color" data-color="#81ecec" style="background:#81ecec;"></div>
                        <div class="btn-color" data-color="#55efc4" style="background:#55efc4;"></div>
                        <div class="btn-color" data-color="#ffb8b8" style="background:#ffb8b8;"></div>
                        <div class="btn-color" data-color="#a29bfe" style="background:#a29bfe;"></div>
                    </div>
                </div>

                <div class="option-group">
                    <span class="group-label">{{ __('Pilihan Stiker') }}</span>
                    <div class="sticker-grid" id="sticker-options">
                        <button class="btn-sticker active" data-sticker="none">✨ {{ __('Polos') }}</button>
                        <button class="btn-sticker" data-sticker="girlypop">🎀 {{ __('Girly') }}</button>
                        <button class="btn-sticker" data-sticker="jellycat">🐈 {{ __('Jelly') }}</button>
                        <button class="btn-sticker" data-sticker="cute">🐶 {{ __('Cute') }}</button>
                        <button class="btn-sticker" data-sticker="mofusand">🐱 {{ __('Mofu') }}</button>
                        <button class="btn-sticker" data-sticker="shinchang">👦 {{ __('Shin') }}</button>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <button id="download-strip" class="btn-action-neo btn-download">
                    <i class="bi bi-laptop"></i> {{ __('Unduh ke Perangkat') }}
                </button>
                
                <button id="btn-show-qr" class="btn-action-neo btn-qr">
                    <i class="bi bi-qr-code-scan"></i> {{ __('Pindai dengan HP') }}
                </button>

                <form id="finish-form" action="{{ route('finish') }}" method="POST" style="width: 100%; margin-top: 15px;">
                    @csrf
                    <input type="hidden" name="final_image" id="final-image-input">
                    <button type="submit" class="btn-action-neo btn-finish">
                        {{ __('Selesai & Simpan') }} <i class="bi bi-check-circle-fill"></i>
                    </button>
                </form>

                <a href="{{ route('select.option') }}" class="btn-back-text-neo">
                    <i class="bi bi-arrow-repeat"></i> {{ __('Ulangi Dari Awal') }}
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js_extra')
<script>
// --- LOGIKA JS MURNI (Tidak ada class yang merusak fitur) ---
function drawImageCover(ctx, img, x, y, w, h) {
    const imgAspectRatio = img.width / img.height; const targetAspectRatio = w / h; let sx, sy, sWidth, sHeight; 
    if (imgAspectRatio > targetAspectRatio) { sHeight = img.height; sWidth = sHeight * targetAspectRatio; sx = (img.width - sWidth) / 2; sy = 0; } 
    else { sWidth = img.width; sHeight = sWidth / targetAspectRatio; sx = 0; sy = (img.height - sHeight) / 2; }
    ctx.drawImage(img, sx, sy, sWidth, sHeight, x, y, w, h);
}

document.addEventListener('DOMContentLoaded', async () => {
    
    setTimeout(() => {
        confetti({
            particleCount: 150,
            spread: 80,
            origin: { y: 0.6 },
            colors: ['#ff0055', '#00d2d3', '#1a1a1a', '#f1c40f']
        });
    }, 500); 

    // ===============================================
    // LOGIKA QR CODE DIRECT DOWNLOAD FOTO
    // ===============================================
    const qrModal = document.getElementById('qr-modal');
    const qrContainer = document.getElementById('qr-code-container');
    const btnQr = document.getElementById('btn-show-qr');
    const canvas = document.getElementById('photostrip-canvas'); 
    
    btnQr.addEventListener('click', async () => {
        const originalText = btnQr.innerHTML;
        btnQr.innerHTML = '<i class="bi bi-hourglass-split"></i> {{ __('Memproses...') }}';
        btnQr.disabled = true;

        const dataURL = canvas.toDataURL('image/jpeg', 0.8);

        try {
            const response = await fetch('/upload-qr-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: dataURL })
            });

            if (!response.ok) {
                const errText = await response.text();
                console.error("Server Error:", errText);
                throw new Error("Server PHP Error: " + response.status);
            }

            const data = await response.json();

            qrContainer.innerHTML = ''; 
            new QRCode(qrContainer, {
                text: data.url, 
                width: 180,
                height: 180,
                colorDark : "#1a1a1a", // Brutal black QR
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            
            qrModal.classList.add('active');
        } catch (error) {
            console.error("Detail Error QR:", error);
            alert("{{ __('Gagal membuat QR Code. Silakan coba lagi.') }}");
        } finally {
            btnQr.innerHTML = originalText;
            btnQr.disabled = false;
        }
    });

    document.getElementById('btn-close-qr').addEventListener('click', () => {
        qrModal.classList.remove('active');
    });

    // ===============================================
    // LOGIKA CANVAS BIASA 
    // ===============================================
    const ctx = canvas.getContext('2d');
    const imagesData = @json($capturedImages);
    const templateName = "{{ $template }}"; 
    const overlayUrlFromServer = "{{ $overlayUrl ?? '' }}";
    const isPredefinedTemplate = overlayUrlFromServer !== '';

    if (isPredefinedTemplate) {
        const controls = document.getElementById('customization-controls');
        if(controls) controls.classList.add('hidden-panel');
    }

    if (imagesData.length === 0) { alert("{{ __('Belum ada foto yang dipilih. Silakan kembali ke awal.') }}"); window.location.href = "{{ route('select.option') }}"; return; }

    let frameColor = '#ffffff', photoBgColor = '#e0e0e0', selectedSticker = 'none';

    const PHOTO_COUNT = imagesData.length; const IS_GRID_LAYOUT = PHOTO_COUNT >= 5; const GRID_COLUMNS = 2; const GRID_ROWS = Math.ceil(PHOTO_COUNT / GRID_COLUMNS);
    const TARGET_PHOTO_ASPECT_RATIO = 3 / 2; const BASE_WIDTH = 450; const SCALE_FACTOR = IS_GRID_LAYOUT ? 1.5 : 1; 

    const STRIP_WIDTH = Math.round(BASE_WIDTH * SCALE_FACTOR); const FRAME_PADDING = Math.round(18 * SCALE_FACTOR); const SPACING = Math.round(10 * SCALE_FACTOR); const STICKER_AREA_HEIGHT = Math.round(90 * SCALE_FACTOR); 
    let PHOTO_DRAW_WIDTH, PHOTO_DRAW_HEIGHT, FINAL_PHOTO_REGION_HEIGHT;
    
    if (IS_GRID_LAYOUT) {
        const TOTAL_SPACING_WIDTH = SPACING * (GRID_COLUMNS - 1); PHOTO_DRAW_WIDTH = Math.round((STRIP_WIDTH - (FRAME_PADDING * 2) - TOTAL_SPACING_WIDTH) / GRID_COLUMNS); PHOTO_DRAW_HEIGHT = Math.round(PHOTO_DRAW_WIDTH / TARGET_PHOTO_ASPECT_RATIO); 
        const TOTAL_SPACING_HEIGHT = SPACING * (GRID_ROWS - 1); FINAL_PHOTO_REGION_HEIGHT = (PHOTO_DRAW_HEIGHT * GRID_ROWS) + TOTAL_SPACING_HEIGHT;
    } else {
        PHOTO_DRAW_WIDTH = STRIP_WIDTH - (FRAME_PADDING * 2); PHOTO_DRAW_HEIGHT = Math.round(PHOTO_DRAW_WIDTH / TARGET_PHOTO_ASPECT_RATIO); 
        const TOTAL_SPACING_HEIGHT = SPACING * (PHOTO_COUNT - 1); FINAL_PHOTO_REGION_HEIGHT = (PHOTO_DRAW_HEIGHT * PHOTO_COUNT) + TOTAL_SPACING_HEIGHT;
    }

    const FINAL_STRIP_HEIGHT = FINAL_PHOTO_REGION_HEIGHT + (FRAME_PADDING * 2) + STICKER_AREA_HEIGHT;
    canvas.width = STRIP_WIDTH; canvas.height = FINAL_STRIP_HEIGHT;

    const loadedImages = await Promise.all(imagesData.map(dataUrl => { return new Promise(resolve => { const img = new Image(); img.onload = () => resolve(img); img.onerror = () => resolve(null); img.src = dataUrl; }); }));
    const validLoadedImages = loadedImages.filter(img => img !== null);

    const overlayImage = new Image();
    if (isPredefinedTemplate) { overlayImage.src = overlayUrlFromServer; overlayImage.crossOrigin = "Anonymous"; } 
    else { overlayImage.src = `/images/stickers/overlay_none.png`; }
    overlayImage.onload = drawStrip; overlayImage.onerror = () => { drawStrip(); };

    function drawStrip() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = isPredefinedTemplate ? '#ffffff' : frameColor; ctx.fillRect(0, 0, canvas.width, canvas.height);

        let currentY = FRAME_PADDING; let currentX = FRAME_PADDING;
        validLoadedImages.forEach((img, index) => {
            const isNewRow = IS_GRID_LAYOUT && (index % GRID_COLUMNS === 0);
            if (!IS_GRID_LAYOUT) {
                if (!isPredefinedTemplate) { ctx.fillStyle = photoBgColor; ctx.fillRect(FRAME_PADDING, currentY, PHOTO_DRAW_WIDTH, PHOTO_DRAW_HEIGHT); }
                drawImageCover(ctx, img, FRAME_PADDING, currentY, PHOTO_DRAW_WIDTH, PHOTO_DRAW_HEIGHT); currentY += PHOTO_DRAW_HEIGHT + SPACING;
            } else {
                if (isNewRow && index > 0) { currentY += PHOTO_DRAW_HEIGHT + SPACING; currentX = FRAME_PADDING; } else if (index === 0) { currentX = FRAME_PADDING; }
                if (!isPredefinedTemplate) { ctx.fillStyle = photoBgColor; ctx.fillRect(currentX, currentY, PHOTO_DRAW_WIDTH, PHOTO_DRAW_HEIGHT); }
                drawImageCover(ctx, img, currentX, currentY, PHOTO_DRAW_WIDTH, PHOTO_DRAW_HEIGHT); currentX += PHOTO_DRAW_WIDTH + SPACING;
            }
        });

        if (overlayImage.complete && overlayImage.naturalHeight !== 0) { ctx.drawImage(overlayImage, 0, 0, canvas.width, canvas.height); }
    }

    if (!isPredefinedTemplate) {
        document.querySelectorAll('#frame-color-options .btn-color').forEach(btn => { btn.addEventListener('click', (e) => { document.querySelectorAll('#frame-color-options .btn-color').forEach(b => b.classList.remove('active')); btn.classList.add('active'); frameColor = btn.dataset.color; drawStrip(); }); });
        document.querySelectorAll('#photo-bg-color-options .btn-color').forEach(btn => { btn.addEventListener('click', (e) => { document.querySelectorAll('#photo-bg-color-options .btn-color').forEach(b => b.classList.remove('active')); btn.classList.add('active'); photoBgColor = btn.dataset.color; drawStrip(); }); });
        document.querySelectorAll('#sticker-options .btn-sticker').forEach(btn => { btn.addEventListener('click', (e) => { document.querySelectorAll('#sticker-options .btn-sticker').forEach(b => b.classList.remove('active')); btn.classList.add('active'); selectedSticker = btn.dataset.sticker; overlayImage.src = `/images/stickers/overlay_${selectedSticker}.png`; }); });
    }

    document.getElementById('download-strip').addEventListener('click', () => {
        const a = document.createElement('a'); a.href = canvas.toDataURL('image/png', 1.0); a.download = `meetinframe_photostrip_${new Date().getTime()}.png`; document.body.appendChild(a); a.click(); a.remove();
    });

    const finishForm = document.getElementById('finish-form');
    if (finishForm) { finishForm.addEventListener('submit', function(e) { e.preventDefault(); document.getElementById('final-image-input').value = canvas.toDataURL('image/png', 1.0); this.submit(); }); }
});
</script>
@endsection