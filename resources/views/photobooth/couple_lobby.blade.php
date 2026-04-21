@extends('layouts.app')

@section('title', __('Lobby Foto Couple') . ' - MeetinFrame')

@section('head_extra')
<style>
    /* --- GLOBAL STYLE KHUSUS LOBBY --- */
    .lobby-container {
        max-width: 1100px;
        margin: 40px auto 80px;
        padding: 0 20px;
        animation: fadeUp 0.8s ease;
    }

    /* --- HEADER LOBBY --- */
    .page-header-neo {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .page-badge-neo {
        display: inline-block;
        background: #fff;
        border: 3px solid var(--dark);
        padding: 8px 24px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--dark);
        box-shadow: 4px 4px 0px var(--hot-pink);
        margin-bottom: 20px;
        transform: rotate(-2deg);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .page-title-neo {
        font-family: 'Uncut Sans', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--dark);
        text-transform: uppercase;
        letter-spacing: -2px;
        margin-bottom: 10px;
    }

    .page-desc-neo {
        color: var(--dark);
        font-size: 1.15rem;
        font-weight: 600;
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* --- ALERT BRUTAL --- */
    .alert-neo {
        background: #ffb8b8;
        border: 4px solid var(--dark);
        border-radius: 15px;
        padding: 15px 20px;
        font-weight: 800;
        color: var(--dark);
        box-shadow: 6px 6px 0px var(--dark);
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
    }
    .alert-neo .btn-close {
        background: none;
        border: 3px solid var(--dark);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 1;
        transition: 0.2s;
    }
    .alert-neo .btn-close:hover {
        background: var(--dark);
        color: #fff;
        transform: scale(1.1);
    }

    /* --- GRID LAYOUT --- */
    .lobby-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    /* --- CARD STYLE BRUTAL --- */
    .lobby-card-neo {
        background: #fff;
        border-radius: 24px;
        padding: 40px;
        border: 4px solid var(--dark);
        text-align: center;
        position: relative;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
    }
    
    .card-create { box-shadow: 12px 12px 0px var(--hot-pink); }
    .card-create:hover { transform: translate(-4px, -4px); box-shadow: 16px 16px 0px var(--hot-pink); }
    
    .card-join { box-shadow: 12px 12px 0px #00d2d3; }
    .card-join:hover { transform: translate(-4px, -4px); box-shadow: 16px 16px 0px #00d2d3; }

    .card-icon-neo {
        width: 80px; height: 80px;
        border: 4px solid var(--dark);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 25px; font-size: 2.5rem;
        box-shadow: 4px 4px 0px var(--dark);
    }
    .icon-create { background: #ffeaa7; color: var(--dark); }
    .icon-join { background: #81ecec; color: var(--dark); }

    .card-title-neo { font-family: 'Uncut Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 15px; text-transform: uppercase; }
    .card-desc-neo { font-size: 1rem; color: #555; font-weight: 600; margin-bottom: 30px; line-height: 1.6; }

    /* --- FORM ELEMENTS BRUTAL --- */
    .pose-selection {
        display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 30px;
    }
    .pose-radio { display: none; }
    .pose-label {
        background: #fff; border: 3px solid var(--dark); padding: 10px 20px; border-radius: 12px;
        font-weight: 800; color: var(--dark); cursor: pointer; transition: 0.2s;
        box-shadow: 3px 3px 0px var(--dark); text-transform: uppercase;
    }
    .pose-label:hover { transform: translate(-2px, -2px); box-shadow: 5px 5px 0px var(--dark); }
    .pose-radio:checked + .pose-label {
        background: var(--dark); color: #fff; box-shadow: 0px 0px 0px var(--dark); transform: translate(3px, 3px);
    }

    .input-code-neo {
        width: 100%; padding: 18px; border-radius: 15px; border: 4px solid var(--dark);
        font-size: 1.5rem; font-weight: 800; text-align: center; letter-spacing: 5px;
        text-transform: uppercase; color: var(--dark); margin-bottom: 30px; transition: 0.2s;
        box-shadow: inset 4px 4px 0px rgba(0,0,0,0.05);
    }
    .input-code-neo:focus { outline: none; background: #fff; box-shadow: 6px 6px 0px #00d2d3; transform: translate(-2px, -2px); }
    .input-code-neo::placeholder { color: #aaa; font-weight: 700; letter-spacing: 2px; }

    /* --- BUTTONS BRUTAL --- */
    .btn-action-neo {
        width: 100%; padding: 18px; border-radius: 15px; border: 4px solid var(--dark);
        font-weight: 800; font-size: 1.2rem; cursor: pointer; transition: 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;
        text-transform: uppercase; margin-top: auto;
    }
    
    .btn-create-neo { background: var(--hot-pink); color: white; box-shadow: 6px 6px 0px var(--dark); }
    .btn-create-neo:hover { transform: translate(2px, 2px); box-shadow: none; background: var(--dark); color: white;}
    
    .btn-join-neo { background: #00d2d3; color: var(--dark); box-shadow: 6px 6px 0px var(--dark); }
    .btn-join-neo:hover { transform: translate(2px, 2px); box-shadow: none; background: var(--dark); color: white;}

    /* OR Divider untuk Mobile */
    .divider-mobile-neo { display: none; text-align: center; margin: 30px 0; font-weight: 800; color: var(--dark); font-size: 1.5rem; font-family: 'Uncut Sans', sans-serif; text-transform: uppercase; }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* =========================================
       PENGATURAN KHUSUS LAYAR HP (MOBILE FIX)
       ========================================= */
    @media (max-width: 768px) {
        .page-title-neo { font-size: 2.2rem; }
        .page-desc-neo { font-size: 1rem; }
        
        .lobby-grid { grid-template-columns: 1fr; gap: 0; }
        .divider-mobile-neo { display: block; }
        
        .lobby-card-neo { padding: 30px 20px; }
        
        .card-create { box-shadow: 8px 8px 0px var(--hot-pink); }
        .card-create:hover { transform: translate(-2px, -2px); box-shadow: 10px 10px 0px var(--hot-pink); }
        
        .card-join { box-shadow: 8px 8px 0px #00d2d3; }
        .card-join:hover { transform: translate(-2px, -2px); box-shadow: 10px 10px 0px #00d2d3; }

        .btn-action-neo { font-size: 1.1rem; padding: 15px; box-shadow: 4px 4px 0px var(--dark); }
    }
</style>
@endsection

@section('content')
<div class="lobby-container">

    <div class="page-header-neo">
        <span class="page-badge-neo">{{ __('🌐 Multipemain') }}</span>
        <h1 class="page-title-neo">{{ __('Sesi Foto Jarak Jauh') }}</h1>
        <p class="page-desc-neo">{{ __('Ajak rekan, sahabat, atau pasangan Anda untuk berfoto dalam satu bingkai dari lokasi yang berbeda secara real-time!') }}</p>
    </div>

    @if(session('error'))
    <div class="alert-neo">
        <div><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ session('error') }}</div>
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif

    <div class="lobby-grid">
        
        <div class="lobby-card-neo card-create">
            <div class="card-icon-neo icon-create">
                <i class="bi bi-house-add-fill"></i>
            </div>
            <h2 class="card-title-neo">{{ __('Buat Ruangan Baru') }}</h2>
            <p class="card-desc-neo">{{ __('Pilih jumlah pose dan bagikan kode ruangan yang terbentuk kepada rekan atau pasangan Anda.') }}</p>

            <form action="{{ route('couple.create') }}" method="POST" style="display: flex; flex-direction: column; flex-grow: 1;">
                @csrf
                <div class="pose-selection">
                    <input type="radio" id="pose2" name="pose_count" value="2" class="pose-radio" required>
                    <label for="pose2" class="pose-label">{{ __('2 Pose') }}</label>

                    <input type="radio" id="pose3" name="pose_count" value="3" class="pose-radio" checked>
                    <label for="pose3" class="pose-label">{{ __('3 Pose') }}</label>

                    <input type="radio" id="pose4" name="pose_count" value="4" class="pose-radio">
                    <label for="pose4" class="pose-label">{{ __('4 Pose') }}</label>

                    <input type="radio" id="pose6" name="pose_count" value="6" class="pose-radio">
                    <label for="pose6" class="pose-label">{{ __('6 Pose') }}</label>
                </div>

                <button type="submit" class="btn-action-neo btn-create-neo">
                    {{ __('Buat Ruangan') }} <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </form>
        </div>

        <div class="divider-mobile-neo">{{ __('ATAU') }}</div>

        <div class="lobby-card-neo card-join">
            <div class="card-icon-neo icon-join">
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <h2 class="card-title-neo">{{ __('Bergabung ke Ruangan') }}</h2>
            <p class="card-desc-neo">{{ __('Sudah menerima kode ruangan dari rekan Anda? Masukkan kodenya di bawah ini untuk segera bergabung.') }}</p>

            <form action="{{ route('couple.join') }}" method="POST" style="display: flex; flex-direction: column; flex-grow: 1;">
                @csrf
                <input type="text" name="room_code" class="input-code-neo" placeholder="KODE6X" maxlength="6" required autocomplete="off">

                <button type="submit" class="btn-action-neo btn-join-neo">
                    {{ __('Bergabung Sekarang') }} <i class="bi bi-people-fill"></i>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection