@extends('layouts.app')

@section('title', __('Mulai Sesi Foto') . ' - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .container-session {
        max-width: 1280px;
        margin: 20px auto 60px;
        padding: 0 20px;
    }

    /* --- MENU PILIHAN AWAL (CHOICE) --- */
    #choice-menu {
        text-align: center;
        padding: 60px 0;
        animation: fadeDown 0.8s ease;
    }
    
    .choice-header h2 {
        font-family: 'Uncut Sans', sans-serif;
        font-size: 3.5rem; 
        font-weight: 800; 
        color: var(--dark); 
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: -2px;
    }
    .choice-header p { 
        font-size: 1.1rem; 
        font-weight: 600;
        color: #444; 
    }

    .choice-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px; 
        margin: 50px auto 0; 
        max-width: 800px;
    }

    .choice-card-neo {
        background: #fff;
        border: 4px solid var(--dark);
        border-radius: 24px;
        padding: 40px 30px;
        text-align: center;
        cursor: pointer;
        box-shadow: 8px 8px 0px var(--dark);
        transition: 0.2s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    .choice-card-neo:hover {
        transform: translate(-4px, -4px);
        box-shadow: 12px 12px 0px var(--hot-pink);
    }
    
    #choose-upload:hover { box-shadow: 12px 12px 0px #00d2d3; }

    .icon-circle-neo {
        width: 80px; height: 80px;
        border: 4px solid var(--dark);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 25px;
        font-size: 2.5rem;
        box-shadow: 4px 4px 0px var(--dark);
    }
    
    #choose-camera .icon-circle-neo { background: #ffeaa7; color: var(--dark); }
    #choose-upload .icon-circle-neo { background: #81ecec; color: var(--dark); }

    .choice-title { font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; text-transform: uppercase; }
    .choice-desc { font-size: 1rem; font-weight: 600; color: #555; line-height: 1.6; }

    /* --- PHOTOBOOTH INTERFACE --- */
    .booth-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        align-items: start;
        animation: fadeUp 0.6s ease;
    }

    /* Panel General Brutal */
    .panel-neo {
        background: #fff;
        border: 4px solid var(--dark);
        border-radius: 24px;
        padding: 25px;
        box-shadow: 8px 8px 0px var(--dark);
    }

    /* Panel Kamera (Kiri) */
    #camera-wrapper, #upload-preview-wrap {
        width: 100%;
        height: 520px;
        background: var(--dark);
        border: 4px solid var(--dark);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }

    #video-feed, #preview-canvas { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
    }
    #preview-canvas { 
        pointer-events: none; 
        z-index: 10; 
    }

    #countdown-display {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        font-family: 'Uncut Sans', sans-serif;
        font-size: 12rem; font-weight: 800; color: #fff;
        text-shadow: 8px 8px 0px var(--hot-pink);
        display: none; z-index: 50;
        animation: pulseCount 1s infinite;
    }
    @keyframes pulseCount { 0% { transform: translate(-50%,-50%) scale(0.8); opacity: 0; } 50% { transform: translate(-50%,-50%) scale(1.1); opacity: 1; } 100% { transform: translate(-50%,-50%) scale(1); opacity: 0; } }

    #flash-overlay { position: fixed; inset: 0; background: #fff; opacity: 0; pointer-events: none; z-index: 9999; transition: opacity 0.1s; }

    /* Controls Bar Bawah Kamera */
    .controls-bar {
        display: flex; justify-content: center; gap: 15px; margin-top: 25px;
        padding: 15px; background: #fff; border: 4px solid var(--dark); border-radius: 50px; 
        width: fit-content; margin-left: auto; margin-right: auto;
        box-shadow: 4px 4px 0px var(--dark);
    }

    .btn-ctrl {
        width: 50px; height: 50px; border-radius: 50%; border: 3px solid var(--dark);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; cursor: pointer; transition: 0.2s;
        background: #fff; color: var(--dark); font-weight: 800;
    }
    .btn-ctrl:hover { background: #ffeaa7; transform: translate(-2px, -2px); box-shadow: 2px 2px 0px var(--dark); }

    .btn-shutter-main {
        height: 60px; padding: 0 40px; border-radius: 50px;
        background: var(--hot-pink); border: 3px solid var(--dark);
        color: white; font-weight: 800; font-size: 1.2rem;
        display: flex; align-items: center; gap: 10px; cursor: pointer;
        box-shadow: 4px 4px 0px var(--dark); transition: 0.2s;
        text-transform: uppercase;
    }
    .btn-shutter-main:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark); background: var(--dark); }

    /* Sidebar (Kanan) */
    .panel-section { margin-bottom: 30px; padding-bottom: 25px; border-bottom: 4px solid var(--dark); }
    .panel-section:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    
    .section-label { font-size: 0.9rem; font-weight: 800; color: var(--dark); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: flex; justify-content: space-between; }

    /* Progress Bar Brutal */
    .progress-track { background: #fff; border: 3px solid var(--dark); height: 24px; border-radius: 50px; overflow: hidden; margin-bottom: 8px; position: relative; }
    .progress-fill { height: 100%; background: #00d2d3; border-right: 3px solid var(--dark); width: 0%; transition: width 0.4s ease; }
    .progress-stat { font-size: 1rem; font-weight: 800; color: var(--dark); }

    /* Filter Chips Brutal */
    .filter-wrap { display: flex; gap: 10px; flex-wrap: wrap; }
    .filter-btn {
        background: #fff; border: 3px solid var(--dark); padding: 8px 16px; border-radius: 12px;
        font-size: 0.9rem; font-weight: 800; color: var(--dark); cursor: pointer; transition: 0.2s;
        box-shadow: 2px 2px 0px var(--dark); text-transform: uppercase;
    }
    .filter-btn:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px var(--dark); }
    .filter-btn.active { background: var(--dark); color: #fff; box-shadow: 0px 0px 0px var(--dark); transform: translate(2px, 2px); }

    /* Thumbnails */
    .thumbs-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; }
    .thumb-item { width: 100%; aspect-ratio: 3/2; border-radius: 12px; object-fit: cover; border: 3px solid var(--dark); animation: popIn 0.3s ease; }
    @keyframes popIn { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    /* Action Buttons */
    .btn-action-neo { width: 100%; padding: 16px; border-radius: 15px; border: 3px solid var(--dark); font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 15px; text-transform: uppercase; box-shadow: 4px 4px 0px var(--dark); }
    
    .btn-next { background: #00d2d3; color: var(--dark); }
    .btn-next:hover { background: var(--dark); color: #fff; transform: translate(2px,2px); box-shadow: none; }
    
    .btn-secondary-action { background: #ffeaa7; color: var(--dark); }
    .btn-secondary-action:hover { background: #f1c40f; transform: translate(2px,2px); box-shadow: none; }
    
    .btn-danger-action { background: #ffb8b8; color: var(--dark); }
    .btn-danger-action:hover { background: var(--hot-pink); color: #fff; transform: translate(2px,2px); box-shadow: none; }

    /* Back Button */
    .link-back-neo { color: var(--dark); text-decoration: none; font-size: 1rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; text-transform: uppercase; border: 3px solid transparent; padding: 8px 16px; border-radius: 50px; }
    .link-back-neo:hover { border-color: var(--dark); background: #fff; box-shadow: 4px 4px 0px var(--hot-pink); transform: translate(-2px, -2px); }

    @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* HP RESPONSIVE */
    @media (max-width: 992px) {
        .choice-header h2 { font-size: 2.5rem; }
        .booth-grid { grid-template-columns: 1fr; }
        
        #camera-wrapper, #upload-preview-wrap { height: 400px; border-width: 3px; }
        
        .controls-bar { width: 100%; padding: 10px; border-width: 3px; gap: 10px; flex-wrap: wrap; }
        .btn-shutter-main { flex: 1; padding: 0 15px; font-size: 1.1rem; justify-content: center; }
        
        .panel-neo { padding: 20px; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
        .btn-action-neo { padding: 12px; font-size: 1rem; border-width: 3px; box-shadow: 3px 3px 0px var(--dark); }
    }
</style>
@endsection

@section('content')
<div id="flash-overlay"></div>

<div class="container-session">

    <div id="choice-menu">
        <div class="choice-header">
            <h2>{{ __('Mulai Sesi Foto') }}</h2>
            <p>{{ __('Pilih metode masukan! Ingin mengambil foto secara langsung atau mengunggah foto yang sudah ada?') }}</p>
        </div>
        
        <div class="choice-grid">
            <div class="choice-card-neo" id="choose-camera">
                <div class="icon-circle-neo">
                    <i class="bi bi-camera-fill"></i>
                </div>
                <div class="choice-title">{{ __('Gunakan Kamera') }}</div>
                <div class="choice-desc">{{ __('Ambil foto Anda secara langsung menggunakan webcam atau kamera perangkat. Sangat interaktif!') }}</div>
            </div>

            <div class="choice-card-neo" id="choose-upload">
                <div class="icon-circle-neo">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                </div>
                <div class="choice-title">{{ __('Unggah File') }}</div>
                <div class="choice-desc">{{ __('Gunakan foto terbaik Anda yang sudah tersedia di galeri perangkat.') }}</div>
            </div>
        </div>
        
        <div style="margin-top: 60px;">
            <a href="{{ route('select.option') }}" class="link-back-neo">
                <i class="bi bi-arrow-left" style="font-size: 1.3rem;"></i> {{ __('Kembali ke Pilihan Frame') }}
            </a>
        </div>
    </div>

    <div id="photobooth-app" style="display:none;">
        
        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <button id="back-to-menu-from-camera" class="link-back-neo" style="background:none; cursor:pointer;">
                <i class="bi bi-arrow-left" style="font-size: 1.2rem;"></i> {{ __('KEMBALI') }}
            </button>
            <span style="font-family: 'Uncut Sans', sans-serif; font-weight: 800; color: var(--dark); font-size: 1.5rem; text-transform: uppercase;">
                {{ __('Studio') }} <span style="color: var(--hot-pink);">{{ __('Aktif') }}</span>
            </span>
            <div style="width: 100px;"></div> 
        </div>

        <div class="booth-grid">
            
            <div class="panel-neo" style="padding: 15px;">
                
                <div id="camera-wrapper">
                    <video id="video-feed" autoplay playsinline muted></video>
                    <canvas id="preview-canvas"></canvas>
                    <div id="countdown-display">3</div>
                </div>

                <div id="upload-preview-wrap" style="display:none; background: #ffeaa7;">
                    <div style="text-align: center; color: var(--dark);">
                        <i class="bi bi-images" style="font-size: 5rem; margin-bottom: 15px; display: block;"></i>
                        <h3 style="font-weight: 800; text-transform: uppercase;">{{ __('Mode Unggah') }}</h3>
                        <span style="font-size: 1rem; font-weight: 600;">{{ __('Silakan isi slot foto Anda melalui panel di sebelah kanan.') }}</span>
                    </div>
                </div>

                <div id="camera-controls" class="controls-bar">
                    <button id="switch-camera" class="btn-ctrl" title="{{ __('Ganti Kamera') }}">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                    
                    <button id="start-shooting" class="btn-shutter-main">
                        <i class="bi bi-camera-fill"></i> {{ __('Mulai Memotret!') }}
                    </button>
                    
                    <button id="single-capture" class="btn-ctrl" title="{{ __('Ambil 1 Foto') }}">
                        <i class="bi bi-record-circle"></i>
                    </button>

                    <button id="mirror-toggle" class="btn-ctrl" title="{{ __('Mirror') }}">
                        <i class="bi bi-symmetry-vertical"></i>
                    </button>
                </div>
            </div>

            <div class="panel-neo sidebar-panel">

                <div class="panel-section">
                    <div class="section-label">{{ __('Waktu Timer') }}</div>
                    <div class="filter-wrap">
                        <button class="filter-btn active" data-timer="3">{{ __('3 Dtk') }}</button>
                        <button class="filter-btn" data-timer="5">{{ __('5 Dtk') }}</button>
                        <button class="filter-btn" data-timer="10">{{ __('10 Dtk') }}</button>
                    </div>
                </div>
                
                <div class="panel-section">
                    <div class="section-label">
                        <span>{{ __('Slot Foto Terisi') }}</span>
                        <span id="progress-text" style="color: var(--hot-pink);">0 / {{ $poseCount ?? 3 }}</span>
                    </div>
                    <div class="progress-track">
                        <div id="progress-bar" class="progress-fill"></div>
                    </div>
                </div>

                <div class="panel-section">
                    <div class="section-label">{{ __('Filter Lensa') }}</div>
                    <div class="filter-wrap">
                        <button class="filter-btn active" data-filter="none">{{ __('Ori') }}</button>
                        <button class="filter-btn" data-filter="bw">{{ __('B&W') }}</button>
                        <button class="filter-btn" data-filter="sepia">{{ __('Klasik') }}</button>
                        <button class="filter-btn" data-filter="soft">{{ __('Cerah') }}</button>
                    </div>
                </div>

                <div class="panel-section">
                    <div class="section-label">{{ __('Preview Hasil') }}</div>
                    <div id="pose-container" class="thumbs-container"></div>
                    <div id="thumbs" style="display:none;"></div> 
                </div>

                <div id="upload-section" class="panel-section" style="display:none;">
                    <div class="section-label">{{ __('Pilih Foto Anda') }}</div>
                    <div id="upload-slots" style="display:flex; flex-direction:column; gap:12px;"></div>
                    <button id="upload-add-single" class="btn-action-neo btn-secondary-action">
                        <i class="bi bi-plus-lg"></i> {{ __('Isi Slot Kosong') }}
                    </button>
                </div>

                <div style="margin-top: 15px;">
                    <button id="next-button" class="btn-action-neo btn-next" style="display:none;">
                        {{ __('Lanjut Desain Frame') }} <i class="bi bi-arrow-right-circle-fill"></i>
                    </button>
                    
                    <button id="upload-submit" class="btn-action-neo btn-next" style="display:none;">
                        {{ __('Lanjut Desain Frame') }} <i class="bi bi-stars"></i>
                    </button>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                        <button id="delete-last" class="btn-action-neo btn-secondary-action" style="margin:0;">
                            <i class="bi bi-arrow-counterclockwise"></i> {{ __('Batal 1') }}
                        </button>
                        <button id="reset-shots" class="btn-action-neo btn-danger-action" style="margin:0;">
                            <i class="bi bi-trash-fill"></i> {{ __('Ulangi') }}
                        </button>
                    </div>
                </div>

                <div style="display:none;">
                    <input id="interval-input" type="number" value="2">
                    <input id="timer-input" type="number" value="{{ $timer ?? 3 }}">
                    <input id="required-input" type="number" value="{{ $poseCount ?? 3 }}">
                    
                    <form id="next-form" method="POST" action="{{ route('customize.frame') }}">
                        @csrf
                        <input type="hidden" name="image_data" id="image-data">
                        <input type="hidden" name="template" id="image-template">
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('js_extra')
<script>
document.addEventListener('DOMContentLoaded', () => {

const poseCount = Number({{ $poseCount ?? 3 }});
let defaultTimer = Number({{ $timer ?? 3 }});
const initialTemplateName = "{{ $template ?? '' }}"; 

const choiceMenu = document.getElementById('choice-menu');
const photoboothApp = document.getElementById('photobooth-app');
const chooseCamera = document.getElementById('choose-camera');
const chooseUpload = document.getElementById('choose-upload');
const backFromCamera = document.getElementById('back-to-menu-from-camera');

const cameraWrapper = document.getElementById('camera-wrapper');
const uploadPreviewWrap = document.getElementById('upload-preview-wrap');
const cameraControls = document.getElementById('camera-controls');
const uploadSection = document.getElementById('upload-section');

const video = document.getElementById('video-feed');
const previewCanvas = document.getElementById('preview-canvas');
const ctx = previewCanvas.getContext ? previewCanvas.getContext('2d') : null;
const countdownEl = document.getElementById('countdown-display');
const flashOverlay = document.getElementById('flash-overlay');

const poseContainer = document.getElementById('pose-container');
const thumbs = document.getElementById('thumbs');
const progressBar = document.getElementById('progress-bar');
const progressText = document.getElementById('progress-text');

const nextButton = document.getElementById('next-button');
const uploadSubmit = document.getElementById('upload-submit');
const uploadAddSingle = document.getElementById('upload-add-single');
const uploadSlots = document.getElementById('upload-slots');

const imageDataInput = document.getElementById('image-data');
const templateInput = document.getElementById('image-template');

let currentFilter = 'none';
const filters = { 'none': 'none', 'bw': 'grayscale(100%)', 'soft': 'brightness(110%) contrast(95%)', 'sepia': 'sepia(100%)' };
let mirror = true;
let capturedPhotos = [];
let uploadedPhotos = Array(poseCount).fill(null);
let cameraStream = null;

function switchMode(mode) {
    choiceMenu.style.display = 'none';
    photoboothApp.style.display = 'block';
    
    if (mode === 'camera') {
        cameraWrapper.style.display = 'flex';
        uploadPreviewWrap.style.display = 'none';
        cameraControls.style.display = 'flex';
        uploadSection.style.display = 'none';
        startCamera();
    } else {
        cameraWrapper.style.display = 'none';
        uploadPreviewWrap.style.display = 'flex';
        cameraControls.style.display = 'none';
        uploadSection.style.display = 'block';
        stopCameraStream();
        setupUploadSlots();
    }
}

chooseCamera.addEventListener('click', () => switchMode('camera'));
chooseUpload.addEventListener('click', () => switchMode('upload'));

backFromCamera.addEventListener('click', () => {
    photoboothApp.style.display = 'none';
    choiceMenu.style.display = 'block';
    stopCameraStream();
    capturedPhotos = [];
    updateUI();
});

async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        video.srcObject = stream;
        cameraStream = stream;
        video.style.transform = 'scaleX(-1)'; 
        requestAnimationFrame(drawLoop);
    } catch (err) {
        alert("{{ __('Gagal mengakses kamera. Pastikan izin telah diberikan.') }}");
    }
}

function stopCameraStream() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
    }
}

function drawLoop() {
    if (!video || !ctx) return;
    if (video.readyState >= 2) {
        previewCanvas.width = video.videoWidth;
        previewCanvas.height = video.videoHeight;
        ctx.filter = filters[currentFilter];
        
        ctx.save();
        if (mirror) {
            ctx.translate(previewCanvas.width, 0);
            ctx.scale(-1, 1);
        }
        ctx.drawImage(video, 0, 0, previewCanvas.width, previewCanvas.height);
        ctx.restore();
    }
    requestAnimationFrame(drawLoop);
}

document.getElementById('mirror-toggle').addEventListener('click', () => {
    mirror = !mirror;
    video.style.transform = mirror ? 'scaleX(-1)' : 'scaleX(1)';
});

function captureNow() {
    const tmp = document.createElement('canvas');
    tmp.width = previewCanvas.width;
    tmp.height = previewCanvas.height;
    const tctx = tmp.getContext('2d');
    tctx.filter = filters[currentFilter];
    
    if (mirror) {
        tctx.translate(tmp.width, 0);
        tctx.scale(-1, 1);
    }
    tctx.drawImage(previewCanvas, 0, 0);
    
    return tmp.toDataURL('image/png');
}

function addPhoto(dataUrl) {
    capturedPhotos.push(dataUrl);
    updateUI();
}

async function doCountdown(seconds) {
    countdownEl.style.display = 'block';
    for (let i = seconds; i > 0; i--) {
        countdownEl.textContent = i;
        await new Promise(r => setTimeout(r, 1000));
    }
    countdownEl.style.display = 'none';
    
    flashOverlay.style.opacity = 1;
    setTimeout(() => flashOverlay.style.opacity = 0, 150);
}

document.getElementById('single-capture').addEventListener('click', async () => {
    if (capturedPhotos.length >= poseCount) return alert("{{ __('Slot foto sudah penuh!') }}");
    await doCountdown(defaultTimer);
    addPhoto(captureNow());
});

document.getElementById('start-shooting').addEventListener('click', async () => {
    if (capturedPhotos.length >= poseCount) return alert("{{ __('Slot foto sudah penuh!') }}");
    
    while (capturedPhotos.length < poseCount) {
        await doCountdown(defaultTimer);
        addPhoto(captureNow());
        if (capturedPhotos.length < poseCount) {
            await new Promise(r => setTimeout(r, 2000)); 
        }
    }
});

function setupUploadSlots() {
    uploadSlots.innerHTML = '';
    for (let i = 0; i < poseCount; i++) {
        const div = document.createElement('div');
        div.style.cssText = "border:3px solid var(--dark); padding:10px; border-radius:12px; background:#fff; display:flex; align-items:center; gap:10px; box-shadow: 2px 2px 0px var(--dark);";
        div.innerHTML = `
            <div style="font-weight:900; font-size:1.2rem; color:var(--dark); width:30px; text-align:center;">${i+1}</div>
            <input type="file" accept="image/*" style="font-size:0.85rem; width:100%; font-weight:600; cursor:pointer;">
        `;
        
        const input = div.querySelector('input');
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    capturedPhotos[i] = ev.target.result;
                    updateUI();
                };
                reader.readAsDataURL(file);
            }
        });
        uploadSlots.appendChild(div);
    }
}

function updateUI() {
    const count = capturedPhotos.filter(x => x).length;
    const pct = Math.round((count / poseCount) * 100);
    progressBar.style.width = pct + '%';
    progressText.textContent = `${count} / ${poseCount}`;

    poseContainer.innerHTML = '';
    capturedPhotos.forEach(imgData => {
        if (imgData) {
            const img = document.createElement('img');
            img.src = imgData;
            img.className = 'thumb-item';
            poseContainer.appendChild(img);
        }
    });

    const isFull = count >= poseCount;
    if (cameraWrapper.style.display !== 'none') {
        nextButton.style.display = isFull ? 'flex' : 'none';
    } else {
        uploadSubmit.style.display = isFull ? 'flex' : 'none';
    }
}

document.getElementById('delete-last').addEventListener('click', () => {
    if(capturedPhotos.length > 0) {
        capturedPhotos.pop();
        updateUI();
    }
});

document.getElementById('reset-shots').addEventListener('click', () => {
    if(confirm("{{ __('Apakah Anda yakin ingin mengulang semua foto?') }}")) {
        capturedPhotos = [];
        updateUI();
    }
});

document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.dataset.filter;
    });
});

document.querySelectorAll('[data-timer]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-timer]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        defaultTimer = parseInt(btn.dataset.timer);
        if(document.getElementById('timer-input')) {
            document.getElementById('timer-input').value = defaultTimer;
        }
    });
});

function submitForm() {
    imageDataInput.value = JSON.stringify(capturedPhotos.filter(x => x));
    
    const dropdownValue = document.getElementById('template-select') ? document.getElementById('template-select').value : null;
    
    if (initialTemplateName && initialTemplateName !== 'Custom') {
        templateInput.value = initialTemplateName;
    } else {
        templateInput.value = dropdownValue;
    }

    document.getElementById('next-form').submit();
}

nextButton.addEventListener('click', submitForm);
uploadSubmit.addEventListener('click', submitForm);

if (uploadAddSingle) uploadAddSingle.addEventListener('click', () => {
    const firstEmptyInput = uploadSlots.querySelector('input[type=file]:not([value])');
    if (firstEmptyInput) firstEmptyInput.click();
    else alert("{{ __('Semua slot foto Anda sudah terisi!') }}");
});

});
</script>
@endsection