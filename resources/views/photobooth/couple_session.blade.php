@extends('layouts.app')

@section('title', ($title ?? __('Sesi Couple')) . ' - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation/selfie_segmentation.js" crossorigin="anonymous"></script>

<style>
    .session-container { max-width: 1280px; margin: 20px auto 80px; padding: 0 20px; position: relative; }

    /* --- HEADER ROOM BRUTAL --- */
    .room-header { 
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;
        background: #fff; padding: 15px 25px; border-radius: 20px; margin-bottom: 25px;
        border: 4px solid var(--dark); box-shadow: 8px 8px 0px var(--dark);
    }
    
    .room-code-box { 
        background: #ffeaa7; border: 3px solid var(--dark); color: var(--dark); 
        padding: 8px 25px; border-radius: 50px; font-weight: 800; font-size: 1.2rem; 
        letter-spacing: 2px; box-shadow: 4px 4px 0px var(--dark);
    }
    
    .connection-status { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1rem; color: var(--dark); text-transform: uppercase;}
    .status-dot { width: 14px; height: 14px; border-radius: 50%; border: 3px solid var(--dark); background: #f1c40f; animation: blink 1.5s infinite; }
    .status-dot.connected { background: #00d2d3; animation: none; }
    .status-dot.disconnected { background: #ff4757; animation: none; }
    @keyframes blink { 50% { opacity: 0.3; transform: scale(0.8); } }

    .btn-exit-room {
        background: #fff; border: 3px solid var(--dark); color: var(--dark);
        padding: 8px 20px; border-radius: 50px; font-weight: 800; text-decoration: none;
        box-shadow: 4px 4px 0px var(--dark); transition: 0.2s; text-transform: uppercase;
    }
    .btn-exit-room:hover { background: #ffb8b8; transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark); color: var(--dark); }

    /* --- MAIN LAYOUT --- */
    .session-grid { display: grid; grid-template-columns: 1fr 400px; gap: 30px; align-items: start; animation: fadeUp 0.6s ease; }
    
    .left-column { display: flex; flex-direction: column; gap: 25px; }

    /* --- VIDEO STAGE (KIRI ATAS) --- */
    .video-stage { 
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px; 
        background: var(--dark); padding: 25px; border-radius: 24px; position: relative; transition: 0.3s; 
        border: 4px solid var(--dark); box-shadow: 10px 10px 0px var(--hot-pink);
    }
    
    .video-stage.layout-pip { display: block; height: 500px; padding: 0; overflow: hidden; }
    .video-stage.layout-pip #remote-wrapper { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 1; border: none; border-radius: 20px; }
    .video-stage.layout-pip #local-wrapper { 
        position: absolute; bottom: 30px; width: 180px; height: 240px; z-index: 2; 
        border: 4px solid var(--dark); box-shadow: 6px 6px 0px var(--dark); border-radius: 15px; transition: 0.5s ease; 
    }
    .video-stage.layout-pip-right #local-wrapper { right: 30px; left: auto; }
    .video-stage.layout-pip-left #local-wrapper { left: 30px; right: auto; }
    .video-stage.layout-pip .video-label { display: none; } 
    
    .video-wrapper { 
        position: relative; width: 100%; aspect-ratio: 3/4; background: #333; 
        border-radius: 16px; overflow: hidden; border: 4px solid #fff; transition: all 0.3s ease; 
        box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
    }
    .video-wrapper.is-speaking { border-color: #00d2d3; box-shadow: 0 0 0 4px var(--dark), 0 0 20px #00d2d3; }

    video { width: 100%; height: 100%; object-fit: cover; transition: filter 0.3s ease, transform 0.3s ease; background: #1a1a1a; }
    video.is-mirrored { transform: scaleX(-1); }
    
    .video-label { 
        position: absolute; bottom: 15px; left: 15px; background: #fff; color: var(--dark); 
        padding: 5px 15px; border-radius: 50px; font-size: 0.85rem; font-weight: 800; 
        border: 3px solid var(--dark); box-shadow: 2px 2px 0px var(--dark); z-index: 10; text-transform: uppercase;
    }
    
    .floating-actions { position: absolute; top: 15px; right: 15px; display: flex; gap: 10px; z-index: 10; }
    .btn-action-float { 
        background: #fff; color: var(--dark); border: 3px solid var(--dark); width: 45px; height: 45px; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        cursor: pointer; transition: 0.2s; font-size: 1.2rem; box-shadow: 2px 2px 0px var(--dark);
    }
    .btn-action-float:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px var(--dark); background: #ffeaa7;}
    .btn-action-float.off { background: var(--hot-pink); color: white; }

    .cam-off-overlay { position: absolute; inset: 0; background: var(--dark); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; font-weight: 800; opacity: 0; pointer-events: none; transition: 0.3s; z-index: 5; text-transform: uppercase; }
    .cam-off-overlay.active { opacity: 1; }
    .cam-off-overlay i { font-size: 3.5rem; margin-bottom: 10px; color: var(--hot-pink); }

    /* --- CHAT CONTAINER (KIRI BAWAH) --- */
    .chat-container { 
        background: #fff; border: 4px solid var(--dark); border-radius: 20px; 
        padding: 20px; box-shadow: 8px 8px 0px var(--dark); display: flex; flex-direction: column; 
        height: 250px; position: relative; 
    }
    .chat-header { font-family: 'Uncut Sans', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; text-transform: uppercase; border-bottom: 3px solid var(--dark); padding-bottom: 5px; display: flex; justify-content: space-between; align-items: center; }
    .chat-messages { flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; margin-bottom: 12px; padding-right: 5px; }
    .chat-messages::-webkit-scrollbar { width: 6px; }
    .chat-messages::-webkit-scrollbar-thumb { background: var(--dark); border-radius: 10px; }
    
    .chat-bubble { padding: 8px 14px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; max-width: 85%; line-height: 1.4; word-wrap: break-word; border: 2px solid var(--dark); }
    .chat-bubble.me { background: var(--hot-pink); color: white; align-self: flex-end; border-bottom-right-radius: 0; box-shadow: -2px 2px 0px var(--dark); }
    .chat-bubble.them { background: #ffeaa7; color: var(--dark); align-self: flex-start; border-bottom-left-radius: 0; box-shadow: 2px 2px 0px var(--dark); }
    .chat-system { text-align: center; color: #888; font-size: 0.85rem; font-weight: 700; align-self: center; margin: 5px 0; border: 1px dashed #ccc; padding: 4px 10px; border-radius: 20px;}
    
    .chat-bottom-wrapper { display: flex; gap: 15px; align-items: center; }
    .emoji-bar { display: flex; gap: 8px; }
    .emoji-btn { background: #f1f2f6; border: 2px solid var(--dark); font-size: 1.2rem; cursor: pointer; transition: 0.2s; border-radius: 10px; padding: 4px 8px; box-shadow: 2px 2px 0px var(--dark); }
    .emoji-btn:hover { transform: scale(1.1) translateY(-2px); box-shadow: 3px 3px 0px var(--dark); background: #fff; }

    .chat-input-wrap { display: flex; gap: 10px; flex: 1; }
    .chat-input-wrap input { flex: 1; padding: 10px 15px; border-radius: 50px; border: 3px solid var(--dark); background: #f9f9f9; color: var(--dark); outline: none; font-size: 0.95rem; font-weight: 600; }
    .chat-input-wrap input:focus { background: #fff; box-shadow: 0 0 0 3px rgba(0,210,211,0.3); }
    .chat-input-wrap button { background: #00d2d3; border: 3px solid var(--dark); width: 45px; height: 45px; border-radius: 50%; color: var(--dark); cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 2px 2px 0px var(--dark); }
    .chat-input-wrap button:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px var(--dark); }

    .flying-emoji { position: fixed; bottom: 0; font-size: 4rem; z-index: 9999; opacity: 1; pointer-events: none; animation: flyUp 2.5s cubic-bezier(0.25, 1, 0.5, 1) forwards; filter: drop-shadow(4px 4px 0px rgba(0,0,0,0.2)); }
    @keyframes flyUp { 0% { transform: translateY(0) scale(0.5) rotate(0deg); opacity: 1; } 50% { transform: translateY(-40vh) scale(1.2) rotate(15deg); opacity: 1; } 100% { transform: translateY(-80vh) scale(1) rotate(-15deg); opacity: 0; } }

    /* --- SIDEBAR PANEL (KANAN) --- */
    .sidebar-panel { background: #fff; border: 4px solid var(--dark); border-radius: 24px; padding: 25px; box-shadow: 8px 8px 0px var(--dark); display: flex; flex-direction: column; gap: 20px; }

    .section-title { font-size: 0.9rem; color: var(--dark); margin-bottom: 12px; text-transform: uppercase; font-weight: 800; border-bottom: 3px solid var(--dark); padding-bottom: 5px; }
    
    .filter-wrap { display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-start; }
    .filter-btn { background: #fff; border: 3px solid var(--dark); padding: 8px 14px; border-radius: 12px; font-size: 0.85rem; font-weight: 800; color: var(--dark); cursor: pointer; transition: 0.2s; box-shadow: 2px 2px 0px var(--dark); text-transform: uppercase; }
    .filter-btn:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px var(--dark); }
    .filter-btn.active { background: var(--dark); color: #fff; box-shadow: 0px 0px 0px var(--dark); transform: translate(2px, 2px); }

    .custom-timer-select { background: #fff; color: var(--dark); border: 3px solid var(--dark); padding: 12px; border-radius: 12px; font-weight: 800; outline: none; cursor: pointer; width: 100%; transition: 0.2s; box-shadow: 4px 4px 0px var(--dark); font-size: 0.95rem; text-transform: uppercase; }
    .custom-timer-select:focus { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark); }

    /* --- ACTION BAR --- */
    .action-area { text-align: center; padding-top: 10px; }
    
    .btn-shutter-sync { background: var(--hot-pink); color: white; width: 100%; padding: 20px; border-radius: 20px; font-size: 1.3rem; font-weight: 800; border: 4px solid var(--dark); cursor: pointer; box-shadow: 6px 6px 0px var(--dark); transition: 0.2s; display: inline-flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px; text-transform: uppercase; }
    .btn-shutter-sync:disabled { background: #ccc; box-shadow: 0px 0px 0px var(--dark); cursor: not-allowed; transform: translate(6px, 6px); color: #666; }
    .btn-shutter-sync:hover:not(:disabled) { transform: translate(-2px, -2px); box-shadow: 8px 8px 0px var(--dark); background: #d9044b; }

    .btn-pose-generator { background: #00d2d3; color: var(--dark); width: 100%; padding: 12px; border-radius: 12px; font-weight: 800; border: 3px solid var(--dark); cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px; box-shadow: 4px 4px 0px var(--dark); text-transform: uppercase; margin-bottom: 15px;}
    .btn-pose-generator:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark); }

    .btn-undo-group { display: flex; gap: 12px; margin-top: 15px; }
    .btn-undo-group button { flex: 1; padding: 10px; border-radius: 12px; font-weight: 800; font-size: 0.9rem; border: 3px solid var(--dark); cursor: pointer; transition: 0.2s; box-shadow: 3px 3px 0px var(--dark); text-transform: uppercase; }
    .btn-undo { background: #ffeaa7; color: var(--dark); }
    .btn-undo:hover { transform: translate(-2px, -2px); box-shadow: 5px 5px 0px var(--dark); }
    .btn-reset { background: #ffb8b8; color: var(--dark); }
    .btn-reset:hover { background: var(--hot-pink); color: #fff; transform: translate(-2px, -2px); box-shadow: 5px 5px 0px var(--dark); }

    .btn-next-step {
        background: #00d2d3; color: var(--dark); width: 100%; padding: 18px; border-radius: 15px;
        border: 4px solid var(--dark); font-weight: 800; font-size: 1.2rem; cursor: pointer; 
        transition: 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 6px 6px 0px var(--dark); text-transform: uppercase; margin-top: 15px;
    }
    .btn-next-step:hover { background: var(--dark); color: #fff; transform: translate(2px, 2px); box-shadow: none; }

    /* --- OVERLAYS & THUMBNAILS --- */
    #pose-idea-text { position: absolute; top: 5%; left: 50%; transform: translateX(-50%); z-index: 60; background: #fff; color: var(--dark); padding: 15px 30px; border-radius: 50px; font-size: 1.5rem; font-weight: 800; border: 4px solid var(--dark); text-align: center; white-space: nowrap; box-shadow: 6px 6px 0px var(--hot-pink); opacity: 0; pointer-events: none; transition: 0.3s; }
    #pose-idea-text.show { opacity: 1; animation: popBounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes popBounce { 0% { transform: translate(-50%, -20px) scale(0.8); } 100% { transform: translate(-50%, 0) scale(1); } }

    .thumbs-container { display: flex; justify-content: center; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
    .thumb-item { width: 70px; height: 100px; border-radius: 12px; object-fit: cover; border: 3px solid var(--dark); box-shadow: 3px 3px 0px var(--dark); }
    #sync-countdown { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-family: 'Uncut Sans', sans-serif; font-size: 12rem; font-weight: 900; color: #fff; text-shadow: 10px 10px 0px var(--hot-pink); z-index: 50; display: none; }
    #flash-overlay { position: fixed; inset: 0; background: #fff; opacity: 0; pointer-events: none; z-index: 9999; transition: opacity 0.1s; }
    #ai-loading { font-size: 0.95rem; color: var(--dark); background: #ffeaa7; padding: 10px; border-radius: 10px; border: 2px solid var(--dark); font-weight: 800; display: none; text-align: center; margin-top: 15px; box-shadow: 3px 3px 0px var(--dark); text-transform: uppercase; }
    #next-form { display: none; }

    /* HP RESPONSIVE */
    @media (max-width: 992px) { 
        .session-grid { grid-template-columns: 1fr; gap: 25px; } 
        .room-header { flex-direction: column; align-items: flex-start; gap: 15px; padding: 15px; border-width: 3px; }
        .room-code-box { width: 100%; text-align: center; border-width: 3px; }
        
        .video-stage { padding: 15px; border-width: 3px; box-shadow: 6px 6px 0px var(--hot-pink); height: auto; }
        .video-stage.layout-pip { height: 450px; }
        .video-stage.layout-pip #local-wrapper { width: 120px; height: 160px; bottom: 20px; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
        .video-stage.layout-pip-right #local-wrapper { right: 20px; }
        .video-stage.layout-pip-left #local-wrapper { left: 20px; }
        
        .video-wrapper { border-width: 3px; }
        .btn-action-float { width: 35px; height: 35px; font-size: 1rem; border-width: 2px; }

        .chat-container { padding: 15px; border-width: 3px; box-shadow: 6px 6px 0px var(--dark); height: 280px; }
        .chat-bottom-wrapper { flex-direction: column; align-items: stretch; }
        .emoji-bar { justify-content: center; margin-bottom: 5px; }

        .sidebar-panel { padding: 20px; border-width: 3px; box-shadow: 6px 6px 0px var(--dark); }
        .btn-shutter-sync { padding: 15px; font-size: 1.2rem; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
        .btn-next-step { font-size: 1.1rem; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
    }
</style>
@endsection

@section('content')
<div id="flash-overlay"></div>

<div class="session-container">
    
    <div class="room-header">
        <a href="{{ route('couple.lobby') }}" class="btn-exit-room">
            <i class="bi bi-box-arrow-left"></i> {{ __('Keluar') }}
        </a>
        <div class="room-code-box" id="copy-code-btn" style="cursor:pointer;" title="{{ __('Klik untuk Salin') }}">
            {{ __('ROOM:') }} <span id="room-code-text">{{ $room_code }}</span> <i class="bi bi-copy ms-2"></i>
        </div>
        <div class="connection-status">
            <div class="status-dot" id="status-dot"></div>
            <span id="status-text">{{ __('Menunggu Rekan...') }}</span>
        </div>
    </div>

    <div class="session-grid">
        
        <div class="left-column">
            <div class="video-stage" id="main-video-stage">
                
                <div id="pose-idea-text">{{ __('Ide Pose: Ekspresi Lucu! 🤪') }}</div>

                <div class="video-wrapper" id="local-wrapper">
                    <video id="local-video" class="is-mirrored" autoplay playsinline muted></video>
                    <div class="cam-off-overlay" id="local-cam-off">
                        <i class="bi bi-camera-video-off-fill"></i><span>{{ __('Kamera Mati') }}</span>
                    </div>
                    <div class="video-label"><i class="bi bi-person-fill"></i> {{ __('Anda') }}</div>
                    <div class="floating-actions">
                        <button id="btn-mirror" class="btn-action-float" title="{{ __('Balik Kamera') }}"><i class="bi bi-symmetry-vertical"></i></button>
                        <button id="btn-cam" class="btn-action-float" title="{{ __('Kamera') }}"><i class="bi bi-camera-video-fill"></i></button>
                        <button id="btn-mic" class="btn-action-float" title="{{ __('Mikrofon') }}"><i class="bi bi-mic-fill"></i></button>
                    </div>
                </div>

                <div class="video-wrapper" id="remote-wrapper">
                    <video id="remote-video" class="is-mirrored" autoplay playsinline></video>
                    <div class="cam-off-overlay" id="remote-cam-off">
                        <i class="bi bi-camera-video-off-fill"></i><span>{{ __('Kamera Mati') }}</span>
                    </div>
                    <div class="video-label"><i class="bi bi-people-fill"></i> {{ __('Rekan') }}</div>
                </div>

                <div id="sync-countdown">3</div>
            </div>

            <div class="chat-container">
                <div class="chat-header">
                    <span><i class="bi bi-chat-dots-fill" style="color: var(--hot-pink);"></i> {{ __('Obrolan Ruangan') }}</span>
                </div>
                <div class="chat-messages" id="chat-messages">
                    <div class="chat-system">{{ __('Tulis pesan untuk rekan Anda di sini...') }}</div>
                </div>
                <div class="chat-bottom-wrapper">
                    <div class="emoji-bar">
                        <button class="emoji-btn" data-emoji="❤️">❤️</button>
                        <button class="emoji-btn" data-emoji="😂">😂</button>
                        <button class="emoji-btn" data-emoji="🔥">🔥</button>
                        <button class="emoji-btn" data-emoji="✨">✨</button>
                    </div>
                    <div class="chat-input-wrap">
                        <input type="text" id="chat-input" placeholder="{{ __('Ketik pesan Anda...') }}" autocomplete="off">
                        <button id="btn-send-chat"><i class="bi bi-send-fill"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-panel">
            
            <div>
                <div class="section-title">{{ __('Layout Bingkai') }}</div>
                <div class="filter-wrap">
                    <button class="filter-btn layout-btn active" data-layout="split"><i class="bi bi-layout-split"></i> {{ __('Kiri-Kanan') }}</button>
                    <button class="filter-btn layout-btn" data-layout="pip-right"><i class="bi bi-pip"></i> {{ __('PiP Kanan') }}</button>
                    <button class="filter-btn layout-btn" data-layout="pip-left"><i class="bi bi-pip"></i> {{ __('PiP Kiri') }}</button>
                </div>
            </div>

            <div>
                <div class="section-title">{{ __('Virtual Background (AI)') }}</div>
                <div class="filter-wrap">
                    <button class="filter-btn bg-btn active" data-bg="none">🚫 {{ __('Transparan') }}</button>
                    <button class="filter-btn bg-btn" data-bg="neon">🎆 {{ __('Neon Vibes') }}</button>
                    <button class="filter-btn bg-btn" data-bg="beach">🏖️ {{ __('Pantai') }}</button>
                </div>
                <small style="color: #666; font-size: 0.75rem; font-weight: 600; display: block; margin-top: 8px;">
                    <i class="bi bi-info-circle-fill" style="color: var(--hot-pink);"></i> {!! __('Efek AI akan diterapkan <b>setelah foto diambil</b>.') !!}
                </small>
            </div>

            <div>
                <div class="section-title">{{ __('Filter Lensa (Sinkron)') }}</div>
                <div class="filter-wrap">
                    <button class="filter-btn style-btn active" data-filter="none">Ori</button>
                    <button class="filter-btn style-btn" data-filter="bw">B&W</button>
                    <button class="filter-btn style-btn" data-filter="retro">📺 {{ __('Retro') }}</button>
                    <button class="filter-btn style-btn" data-filter="blur">🕶️ {{ __('Blur') }}</button>
                    <button class="filter-btn style-btn" data-filter="alien">👽 {{ __('Alien') }}</button>
                    <button class="filter-btn style-btn" data-filter="ghost">👻 {{ __('Ghost') }}</button>
                    <button class="filter-btn style-btn" data-filter="cyber">🤖 {{ __('Cyber') }}</button>
                    <button class="filter-btn style-btn" data-filter="thermal">🔥 {{ __('Thermal') }}</button>
                </div>
            </div>

            <div class="action-area">
                <p style="font-weight: 800; font-size: 1rem; color: var(--dark); margin-bottom: 10px; text-transform: uppercase;">{{ __('Slot Terisi:') }} <span style="color: var(--hot-pink);" id="pose-count-text">0</span> / {{ $poseCount }}</p>
                
                <select id="timer-setting" class="custom-timer-select">
                    <option value="3">⏳ {{ __('Durasi: 3 Detik') }}</option>
                    <option value="5">⏳ {{ __('Durasi: 5 Detik') }}</option>
                    <option value="10">⏳ {{ __('Durasi: 10 Detik') }}</option>
                </select>

                <button id="btn-snap" class="btn-shutter-sync" disabled>
                    <i class="bi bi-camera-fill"></i> {{ __('Ambil Foto Bersama!') }}
                </button>
                
                <button id="btn-ide-pose" class="btn-pose-generator">
                    <i class="bi bi-dice-5-fill"></i> {{ __('Dapatkan Ide Pose') }}
                </button>

                <div id="ai-loading"><i class="bi bi-cpu-fill"></i> {{ __('Menerapkan Efek Latar...') }}</div>
                
                <button id="btn-next" class="btn-next-step" style="display:none;">
                    {{ __('Desain Frame') }} <i class="bi bi-arrow-right-circle-fill"></i>
                </button>

                <div class="btn-undo-group" id="undo-group" style="display:none;">
                    <button id="btn-undo" class="btn-undo"><i class="bi bi-arrow-counterclockwise"></i> {{ __('Batalkan') }}</button>
                    <button id="btn-reset" class="btn-reset"><i class="bi bi-trash-fill"></i> {{ __('Ulangi Semua') }}</button>
                </div>
                
                <div class="thumbs-container" id="thumbs-container"></div>
            </div>
        </div>
    </div>

    <canvas id="merge-canvas" style="display: none;"></canvas>

    <form id="next-form" method="POST" action="{{ route('customize.frame') }}">
        @csrf
        <input type="hidden" name="image_data" id="image-data">
        <input type="hidden" name="template" value="Custom">
    </form>
</div>
@endsection

@section('js_extra')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    
    // Fitur Salin Kode Room
    const copyBtn = document.getElementById('copy-code-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const code = document.getElementById('room-code-text').innerText;
            navigator.clipboard.writeText(code);
            alert("{{ __('Kode Ruangan berhasil disalin: ') }}" + code);
        });
    }

    const roomCode = '{{ $room_code }}';
    const maxPoses = {{ $poseCount }};
    let capturedImages = [];
    
    const localVideo = document.getElementById('local-video');
    const remoteVideo = document.getElementById('remote-video');
    const statusText = document.getElementById('status-text');
    const statusDot = document.getElementById('status-dot');
    const btnSnap = document.getElementById('btn-snap');
    const btnNext = document.getElementById('btn-next');
    
    const btnMic = document.getElementById('btn-mic');
    const btnCam = document.getElementById('btn-cam');
    const btnMirror = document.getElementById('btn-mirror');
    const localCamOff = document.getElementById('local-cam-off');
    const remoteCamOff = document.getElementById('remote-cam-off');

    const btnUndo = document.getElementById('btn-undo');
    const btnReset = document.getElementById('btn-reset');
    const undoGroup = document.getElementById('undo-group');
    const countdownEl = document.getElementById('sync-countdown');
    const flashOverlay = document.getElementById('flash-overlay');
    const mergeCanvas = document.getElementById('merge-canvas');
    const ctx = mergeCanvas.getContext('2d');
    const poseCountText = document.getElementById('pose-count-text');
    const thumbsContainer = document.getElementById('thumbs-container');
    const aiLoading = document.getElementById('ai-loading');
    const mainVideoStage = document.getElementById('main-video-stage');
    
    const chatInput = document.getElementById('chat-input');
    const btnSendChat = document.getElementById('btn-send-chat');
    const chatMessages = document.getElementById('chat-messages');
    
    const btnIdePose = document.getElementById('btn-ide-pose');
    const poseIdeaText = document.getElementById('pose-idea-text');
    const timerSetting = document.getElementById('timer-setting'); 
    let poseTimer;

    const filterCSS = { 
        'none': 'none', 
        'bw': 'grayscale(100%)', 
        'retro': 'sepia(80%) contrast(120%) saturate(120%) hue-rotate(-15deg)',
        'blur': 'blur(5px)',
        'alien': 'hue-rotate(90deg) saturate(250%) contrast(150%)', 
        'ghost': 'invert(100%) grayscale(50%) opacity(0.8)',
        'cyber': 'hue-rotate(180deg) saturate(300%) contrast(150%)',
        'thermal': 'invert(100%) hue-rotate(180deg) saturate(400%)'
    };

    let currentFilter = 'none';
    let currentLayout = 'split';
    let currentBg = 'none';
    let countdownValue = 3; 

    // PERBAIKAN LINK GAMBAR BACKGROUND AI
    const bgImages = { 'neon': new Image(), 'beach': new Image() };
    bgImages['neon'].crossOrigin = "Anonymous"; bgImages['neon'].src = 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?w=800&q=80';
    bgImages['beach'].crossOrigin = "Anonymous"; bgImages['beach'].src = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80';

    let localStream, peer, dataConnection;
    let isMicMuted = false, isCamOff = false, isLocalMirrored = true, isRemoteMirrored = true; 

    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playSfxPop() { if(audioCtx.state === 'suspended') audioCtx.resume(); const osc = audioCtx.createOscillator(); const gain = audioCtx.createGain(); osc.connect(gain); gain.connect(audioCtx.destination); osc.type = 'sine'; osc.frequency.setValueAtTime(800, audioCtx.currentTime); osc.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 0.1); gain.gain.setValueAtTime(0.3, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1); osc.start(); osc.stop(audioCtx.currentTime + 0.1); }
    function playSfxShutter() { if(audioCtx.state === 'suspended') audioCtx.resume(); const osc = audioCtx.createOscillator(); const gain = audioCtx.createGain(); osc.connect(gain); gain.connect(audioCtx.destination); osc.type = 'square'; osc.frequency.setValueAtTime(100, audioCtx.currentTime); gain.gain.setValueAtTime(0.4, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15); osc.start(); osc.stop(audioCtx.currentTime + 0.15); }

    const poseIdeas = [
        "{{ __('Saranghae 🫶') }}", 
        "{{ __('Gaya Peace ✌️') }}", 
        "{{ __('Ekspresi Lucu! 🤪') }}", 
        "{{ __('Cubit Pipi Virtual 🤏') }}", 
        "{{ __('Gaya Elegan 😎') }}", 
        "{{ __('Pose Bebas!') }}"
    ];
    function showPose(text) { poseIdeaText.innerText = "{{ __('Pose: ') }}" + text; poseIdeaText.classList.add('show'); clearTimeout(poseTimer); poseTimer = setTimeout(() => { poseIdeaText.classList.remove('show'); }, 4000); }
    btnIdePose.addEventListener('click', () => { const random = poseIdeas[Math.floor(Math.random() * poseIdeas.length)]; showPose(random); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'POSE', text: random }); });

    function spawnFlyingEmoji(emojiChar) { const el = document.createElement('div'); el.className = 'flying-emoji'; el.innerText = emojiChar; el.style.left = (Math.random() * 80 + 10) + '%'; document.body.appendChild(el); setTimeout(() => el.remove(), 2500); }
    document.querySelectorAll('.emoji-btn').forEach(btn => { btn.addEventListener('click', () => { const emj = btn.dataset.emoji; spawnFlyingEmoji(emj); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'EMOJI', emoji: emj }); }); });

    timerSetting.addEventListener('change', (e) => {
        countdownValue = parseInt(e.target.value);
        if(dataConnection && dataConnection.open) dataConnection.send({ type: 'SET_TIMER', val: countdownValue });
    });

    const selfieSegmentation = new SelfieSegmentation({locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation/${file}`});
    selfieSegmentation.setOptions({ modelSelection: 1 });

    function removeBgAndCrop(videoElement, targetW, targetH, isMirrored) {
        return new Promise((resolve) => {
            const snapCanvas = document.createElement('canvas'); snapCanvas.width = targetW; snapCanvas.height = targetH; const snapCtx = snapCanvas.getContext('2d');
            if (videoElement.videoWidth === 0) { snapCtx.fillStyle = '#1a1b1e'; snapCtx.fillRect(0, 0, targetW, targetH); resolve(snapCanvas); return; }

            const vRatio = videoElement.videoWidth / videoElement.videoHeight; const tRatio = targetW / targetH; let sx, sy, sW, sH;
            if (vRatio > tRatio) { sH = videoElement.videoHeight; sW = sH * tRatio; sx = (videoElement.videoWidth - sW) / 2; sy = 0; } 
            else { sW = videoElement.videoWidth; sH = sW / tRatio; sx = 0; sy = (videoElement.videoHeight - sH) / 2; }

            snapCtx.save();
            if (isMirrored) { snapCtx.translate(targetW, 0); snapCtx.scale(-1, 1); }
            snapCtx.drawImage(videoElement, sx, sy, sW, sH, 0, 0, targetW, targetH);
            snapCtx.restore();

            selfieSegmentation.onResults((results) => {
                const outCanvas = document.createElement('canvas'); outCanvas.width = targetW; outCanvas.height = targetH; const outCtx = outCanvas.getContext('2d');

                if (currentBg !== 'none' && bgImages[currentBg].complete) {
                    const bgImg = bgImages[currentBg]; const bgRatio = bgImg.width / bgImg.height; let bx, by, bW, bH;
                    if (bgRatio > tRatio) { bH = bgImg.height; bW = bH * tRatio; bx = (bgImg.width - bW) / 2; by = 0; }
                    else { bW = bgImg.width; bH = bW / tRatio; bx = 0; by = (bgImg.height - bH) / 2; }
                    outCtx.drawImage(bgImg, bx, by, bW, bH, 0, 0, targetW, targetH);
                }

                const tempCanvas = document.createElement('canvas'); tempCanvas.width = targetW; tempCanvas.height = targetH; const tCtx = tempCanvas.getContext('2d');
                tCtx.drawImage(results.segmentationMask, 0, 0, targetW, targetH);
                tCtx.globalCompositeOperation = 'source-in';
                tCtx.drawImage(snapCanvas, 0, 0, targetW, targetH);

                outCtx.globalCompositeOperation = 'source-over';
                outCtx.drawImage(tempCanvas, 0, 0, targetW, targetH);
                resolve(outCanvas);
            });
            selfieSegmentation.send({image: snapCanvas});
        });
    }

    function monitorSpeech(stream, wrapperId) {
        if(stream.getAudioTracks().length === 0) return;
        const source = audioCtx.createMediaStreamSource(stream); const analyser = audioCtx.createAnalyser(); analyser.fftSize = 256; source.connect(analyser);
        const dataArray = new Uint8Array(analyser.frequencyBinCount); const wrapper = document.getElementById(wrapperId);
        function checkLevel() { analyser.getByteFrequencyData(dataArray); let sum = 0; for(let i=0; i<dataArray.length; i++) sum += dataArray[i]; let average = sum / dataArray.length; if (average > 15) wrapper.classList.add('is-speaking'); else wrapper.classList.remove('is-speaking'); requestAnimationFrame(checkLevel); }
        checkLevel();
    }

    try { localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true }); localVideo.srcObject = localStream; monitorSpeech(localStream, 'local-wrapper'); } 
    catch (err) { alert("{{ __('Harap izinkan akses kamera dan mikrofon Anda.') }}"); return; }

    const hostId = `flashsy_${roomCode}_1`; const guestId = `flashsy_${roomCode}_2`;
    peer = new Peer(hostId);

    peer.on('error', (err) => {
        if (err.type === 'unavailable-id') {
            peer.destroy(); peer = new Peer(guestId);
            peer.on('open', () => {
                statusText.innerText = "{{ __('Menghubungkan...') }}"; dataConnection = peer.connect(hostId); setupDataConnection(dataConnection);
                const call = peer.call(hostId, localStream);
                call.on('stream', (rStream) => { remoteVideo.srcObject = rStream; setConnectedUI(); monitorSpeech(rStream, 'remote-wrapper'); });
            });
        }
    });

    peer.on('connection', (conn) => { dataConnection = conn; setupDataConnection(dataConnection); });
    peer.on('call', (call) => { call.answer(localStream); call.on('stream', (rStream) => { remoteVideo.srcObject = rStream; setConnectedUI(); monitorSpeech(rStream, 'remote-wrapper'); }); });

    function setConnectedUI() { if(audioCtx.state === 'suspended') audioCtx.resume(); statusText.innerText = "{{ __('Terhubung!') }}"; statusText.style.color = "#00d2d3"; statusDot.classList.remove('disconnected'); statusDot.classList.add('connected'); btnSnap.disabled = false; playSfxPop(); appendSystemChat("{{ __('🤝 Rekan Anda telah bergabung ke dalam ruangan!') }}"); }
    function handleDisconnect() { statusText.innerText = "{{ __('Koneksi Terputus!') }}"; statusText.style.color = "#ff4757"; statusDot.classList.remove('connected'); statusDot.classList.add('disconnected'); btnSnap.disabled = true; remoteVideo.srcObject = null; playSfxPop(); appendSystemChat("{{ __('⚠️ Rekan Anda meninggalkan ruangan atau koneksi terputus.') }}"); }

    function setupDataConnection(conn) {
        conn.on('data', (data) => { 
            if (data.type === 'START_SNAP') runCaptureSequence(); 
            else if (data.type === 'CHAT') { appendChat('them', data.text); playSfxPop(); }
            else if (data.type === 'SET_FILTER') applyFilterVisual(data.filterName);
            else if (data.type === 'UNDO') executeUndo();
            else if (data.type === 'RESET') executeReset();
            else if (data.type === 'TOGGLE_CAM') remoteCamOff.classList.toggle('active', data.isOff);
            else if (data.type === 'TOGGLE_MIRROR') { isRemoteMirrored = data.isMirrored; if(isRemoteMirrored) remoteVideo.classList.add('is-mirrored'); else remoteVideo.classList.remove('is-mirrored'); }
            else if (data.type === 'EMOJI') spawnFlyingEmoji(data.emoji);
            else if (data.type === 'POSE') showPose(data.text);
            else if (data.type === 'SET_LAYOUT') applyLayout(data.layout);
            else if (data.type === 'SET_BG') applyBg(data.bg);
            else if (data.type === 'SET_TIMER') { countdownValue = data.val; timerSetting.value = data.val; } 
        });
        conn.on('close', handleDisconnect);
    }

    function appendChat(sender, text) { const div = document.createElement('div'); div.className = `chat-bubble ${sender}`; div.innerText = text; chatMessages.appendChild(div); chatMessages.scrollTop = chatMessages.scrollHeight; if(audioCtx.state === 'suspended') audioCtx.resume(); }
    function appendSystemChat(text) { const div = document.createElement('div'); div.className = 'chat-system'; div.innerText = text; chatMessages.appendChild(div); chatMessages.scrollTop = chatMessages.scrollHeight; }
    function sendChat() { const text = chatInput.value.trim(); if(text && dataConnection && dataConnection.open) { dataConnection.send({ type: 'CHAT', text: text }); appendChat('me', text); chatInput.value = ''; } }
    btnSendChat.addEventListener('click', sendChat); chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendChat(); });

    btnMic.addEventListener('click', () => { const audioTrack = localStream.getAudioTracks()[0]; if (audioTrack) { isMicMuted = !isMicMuted; audioTrack.enabled = !isMicMuted; btnMic.innerHTML = isMicMuted ? '<i class="bi bi-mic-mute-fill"></i>' : '<i class="bi bi-mic-fill"></i>'; btnMic.classList.toggle('off', isMicMuted); } });
    btnCam.addEventListener('click', () => { const videoTrack = localStream.getVideoTracks()[0]; if (videoTrack) { isCamOff = !isCamOff; videoTrack.enabled = !isCamOff; btnCam.innerHTML = isCamOff ? '<i class="bi bi-camera-video-off-fill"></i>' : '<i class="bi bi-camera-video-fill"></i>'; btnCam.classList.toggle('off', isCamOff); localCamOff.classList.toggle('active', isCamOff); if (dataConnection && dataConnection.open) dataConnection.send({ type: 'TOGGLE_CAM', isOff: isCamOff }); } });
    btnMirror.addEventListener('click', () => { isLocalMirrored = !isLocalMirrored; if (isLocalMirrored) { localVideo.classList.add('is-mirrored'); btnMirror.classList.remove('off'); } else { localVideo.classList.remove('is-mirrored'); btnMirror.classList.add('off'); } if (dataConnection && dataConnection.open) dataConnection.send({ type: 'TOGGLE_MIRROR', isMirrored: isLocalMirrored }); });

    function applyFilterVisual(filterName) { currentFilter = filterName; localVideo.style.filter = filterCSS[filterName]; remoteVideo.style.filter = filterCSS[filterName]; document.querySelectorAll('.style-btn').forEach(b => { if(b.dataset.filter === filterName) b.classList.add('active'); else b.classList.remove('active'); }); }
    document.querySelectorAll('.style-btn').forEach(btn => { btn.addEventListener('click', () => { applyFilterVisual(btn.dataset.filter); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'SET_FILTER', filterName: currentFilter }); }); });

    function applyLayout(layout) {
        currentLayout = layout;
        mainVideoStage.classList.remove('layout-pip', 'layout-pip-right', 'layout-pip-left');
        if(layout.startsWith('pip')) {
            mainVideoStage.classList.add('layout-pip');
            if(layout === 'pip-right') mainVideoStage.classList.add('layout-pip-right');
            else mainVideoStage.classList.add('layout-pip-left');
        }
        document.querySelectorAll('.layout-btn').forEach(b => { if(b.dataset.layout === layout) b.classList.add('active'); else b.classList.remove('active'); });
    }
    document.querySelectorAll('.layout-btn').forEach(btn => { btn.addEventListener('click', () => { applyLayout(btn.dataset.layout); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'SET_LAYOUT', layout: currentLayout }); }); });

    function applyBg(bg) { 
        currentBg = bg; 
        document.querySelectorAll('.bg-btn').forEach(b => { if(b.dataset.bg === bg) b.classList.add('active'); else b.classList.remove('active'); }); 
        
        if(bg !== 'none') {
            const btn = document.querySelector('.bg-btn.active');
            const originalText = btn.innerHTML;
            btn.innerHTML = "{{ __('Aktif! (Tampil saat jepret)') }}";
            setTimeout(() => { btn.innerHTML = originalText; }, 2000);
        }
    }
    document.querySelectorAll('.bg-btn').forEach(btn => { btn.addEventListener('click', () => { applyBg(btn.dataset.bg); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'SET_BG', bg: currentBg }); }); });


    function updateThumbsUI() { poseCountText.innerText = capturedImages.length; thumbsContainer.innerHTML = ''; capturedImages.forEach(src => { const thumb = document.createElement('img'); thumb.src = src; thumb.className = 'thumb-item'; thumbsContainer.appendChild(thumb); }); undoGroup.style.display = capturedImages.length > 0 ? 'flex' : 'none'; if (capturedImages.length < maxPoses) { btnSnap.style.display = 'inline-flex'; btnSnap.disabled = (dataConnection && !dataConnection.open); btnNext.style.display = 'none'; } else { btnSnap.style.display = 'none'; btnNext.style.display = 'flex'; } }
    function executeUndo() { capturedImages.pop(); updateThumbsUI(); } function executeReset() { capturedImages = []; updateThumbsUI(); }
    btnUndo.addEventListener('click', () => { executeUndo(); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'UNDO' }); });
    btnReset.addEventListener('click', () => { if(confirm("{{ __('Apakah Anda yakin ingin menghapus semua foto?') }}")) { executeReset(); if(dataConnection && dataConnection.open) dataConnection.send({ type: 'RESET' }); } });

    btnSnap.addEventListener('click', () => { if (capturedImages.length >= maxPoses) return; if(audioCtx.state === 'suspended') audioCtx.resume(); if (dataConnection && dataConnection.open) dataConnection.send({ type: 'START_SNAP' }); runCaptureSequence(); });

    async function runCaptureSequence() {
        btnSnap.disabled = true; undoGroup.style.display = 'none';
        countdownEl.style.display = 'block';
        
        for (let i = countdownValue; i > 0; i--) { countdownEl.innerText = i; playSfxPop(); await new Promise(r => setTimeout(r, 1000)); }
        countdownEl.style.display = 'none';

        flashOverlay.style.opacity = 1; playSfxShutter(); setTimeout(() => flashOverlay.style.opacity = 0, 150);
        aiLoading.style.display = 'block';

        const fW = 800; const fH = 600; 
        mergeCanvas.width = fW; mergeCanvas.height = fH;
        ctx.clearRect(0, 0, fW, fH); 
        ctx.filter = filterCSS[currentFilter];

        if (currentLayout === 'split') {
            const halfW = fW / 2;
            const localImg = await removeBgAndCrop(localVideo, halfW, fH, isLocalMirrored);
            const remoteImg = await removeBgAndCrop(remoteVideo, halfW, fH, isRemoteMirrored); 
            ctx.drawImage(localImg, 0, 0); ctx.drawImage(remoteImg, halfW, 0); 
        } 
        else if (currentLayout.startsWith('pip')) {
            const remoteImg = await removeBgAndCrop(remoteVideo, fW, fH, isRemoteMirrored);
            ctx.drawImage(remoteImg, 0, 0);

            const localImg = await removeBgAndCrop(localVideo, 200, 266, isLocalMirrored);
            ctx.strokeStyle = '#ffffff'; ctx.lineWidth = 6;
            
            if (currentLayout === 'pip-right') {
                ctx.strokeRect(570, 300, 200, 266); 
                ctx.drawImage(localImg, 570, 300);
            } else {
                ctx.strokeRect(30, 300, 200, 266); 
                ctx.drawImage(localImg, 30, 300);
            }
        }

        ctx.filter = 'none'; 
        capturedImages.push(mergeCanvas.toDataURL('image/png'));
        aiLoading.style.display = 'none';
        updateThumbsUI();
    }

    btnNext.addEventListener('click', () => { document.getElementById('image-data').value = JSON.stringify(capturedImages); if(localStream) localStream.getTracks().forEach(track => track.stop()); document.getElementById('next-form').submit(); });
});
</script>
@endsection