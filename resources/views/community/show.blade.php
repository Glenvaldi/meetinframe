@extends('layouts.app')

@section('title', $creator->name . ' - ' . __('Profil'))

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .container-profile {
        max-width: 1100px;
        margin: 40px auto 80px;
        padding: 0 20px;
    }

    /* --- HEADER PROFIL BRUTAL --- */
    .profile-hero-neo {
        background: #fff; border-radius: 30px; overflow: hidden;
        border: 4px solid var(--dark); box-shadow: 12px 12px 0px var(--dark);
        margin-bottom: 60px; position: relative; animation: fadeDown 0.8s ease;
    }

    .ph-cover-neo {
        height: 200px; background: #00d2d3; border-bottom: 4px solid var(--dark);
        background-image: radial-gradient(#fff 2px, transparent 2px); background-size: 20px 20px;
    }

    .ph-avatar-neo {
        width: 150px; height: 150px; border-radius: 50%; background: #fff;
        border: 6px solid var(--dark); overflow: hidden; position: relative;
        margin: -75px auto 15px; box-shadow: 6px 6px 0px var(--dark);
    }
    .ph-avatar-neo img { width: 100%; height: 100%; object-fit: cover; }

    .ph-info-neo { text-align: center; padding: 0 20px 40px; }
    .ph-name-neo { font-family: 'Uncut Sans', sans-serif; font-size: 2.5rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; text-transform: uppercase; letter-spacing: -1px;}
    
    .ph-badge-neo { 
        background: var(--hot-pink); color: #fff; padding: 8px 24px; border-radius: 50px; 
        font-size: 0.9rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        display: inline-flex; align-items: center; gap: 8px; border: 3px solid var(--dark); box-shadow: 3px 3px 0px var(--dark);
    }
    .ph-meta-neo { margin-top: 25px; color: var(--dark); font-size: 1rem; font-weight: 700; background: #ffeaa7; display: inline-block; padding: 10px 20px; border-radius: 15px; border: 3px solid var(--dark); }

    /* --- GRID TEMPLATE BRUTAL --- */
    .section-title-neo {
        font-family: 'Uncut Sans', sans-serif; font-size: 2rem; font-weight: 800; color: var(--dark); 
        margin-bottom: 30px; display: flex; align-items: center; gap: 10px; text-transform: uppercase;
    }
    
    .template-grid-neo {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 35px; animation: fadeUp 0.8s ease;
    }

    .t-card-neo {
        background: #fff; border-radius: 20px; overflow: hidden; border: 4px solid var(--dark);
        box-shadow: 8px 8px 0px var(--dark); transition: 0.2s; display: flex; flex-direction: column; height: 100%;
    }
    .t-card-neo:hover { transform: translate(-4px, -4px); box-shadow: 12px 12px 0px var(--hot-pink); }

    .t-img-box-neo {
        height: 200px; background: #c7ecee; display: flex; align-items: center; justify-content: center; 
        border-bottom: 4px solid var(--dark); position: relative; overflow: hidden;
    }
    .t-img-box-neo img { width: 100%; height: 100%; object-fit: contain; padding: 20px; transition: 0.3s; filter: drop-shadow(4px 4px 0px rgba(0,0,0,0.15));}
    .t-card-neo:hover .t-img-box-neo img { transform: scale(1.1) rotate(2deg); }

    .t-body-neo { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .t-title-neo { font-size: 1.3rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .t-meta-neo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-weight: 700; font-size: 0.95rem; color: #555; }
    .t-price-badge { background: #ffeaa7; color: var(--dark); padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 800; border: 2px solid var(--dark); box-shadow: 2px 2px 0px var(--dark); text-transform: uppercase; }
    .t-price-badge.free { background: #00d2d3; }

    .btn-use-template-neo {
        margin-top: auto; width: 100%; padding: 14px; border-radius: 12px; border: 3px solid var(--dark);
        background: var(--dark); color: white; font-weight: 800; cursor: pointer; transition: 0.2s; 
        font-size: 1rem; text-transform: uppercase; box-shadow: 4px 4px 0px rgba(0,0,0,0.2);
    }
    .btn-use-template-neo:hover { background: #00d2d3; color: var(--dark); box-shadow: none; transform: translate(2px, 2px); }

    /* LAYAR HP */
    @media (max-width: 768px) {
        .profile-hero-neo { border-width: 3px; box-shadow: 8px 8px 0px var(--dark); }
        .ph-name-neo { font-size: 2rem; }
        .template-grid-neo { grid-template-columns: 1fr; gap: 25px; }
        .t-card-neo { box-shadow: 6px 6px 0px var(--dark); border-width: 3px; }
        .t-card-neo:hover { transform: translate(-2px, -2px); box-shadow: 8px 8px 0px var(--hot-pink); }
    }
</style>
@endsection

@section('content')
<div class="container-profile">
    
    <div class="profile-hero-neo">
        <div class="ph-cover-neo"></div>
        
        <div class="ph-avatar-neo">
            <img src="{{ $creator->profile_picture ? asset('storage/' . $creator->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($creator->name).'&background=random' }}">
        </div>
        
        <div class="ph-info-neo">
            <div class="ph-name-neo">{{ $creator->name }}</div>
            <span class="ph-badge-neo"><i class="bi bi-patch-check-fill"></i> {{ __('Content Creator') }}</span>
            
            <br>
            <div class="ph-meta-neo">
                {{ __('Bergabung:') }} {{ $creator->created_at->translatedFormat('F Y') }} • 
                <strong>{{ $creator->templates->count() }}</strong> {{ __('Karya') }}
            </div>
        </div>
    </div>

    <h3 class="section-title-neo"><i class="bi bi-grid-fill" style="color: var(--hot-pink);"></i> {{ __('Koleksi Template') }}</h3>
    
    <div class="template-grid-neo">
        @forelse($creator->templates as $tmp)
            <div class="t-card-neo">
                <div class="t-img-box-neo">
                    <img src="{{ asset('storage/' . $tmp->image_path) }}">
                </div>
                
                <div class="t-body-neo">
                    <div class="t-title-neo" title="{{ $tmp->name }}">{{ $tmp->name }}</div>
                    
                    <div class="t-meta-neo">
                        <span><i class="bi bi-camera"></i> {{ $tmp->layout_type }} {{ __('Pose') }}</span>
                        @if($tmp->price == 0)
                            <span class="t-price-badge free">{{ __('Gratis') }}</span>
                        @else
                            <span class="t-price-badge">Rp {{ number_format($tmp->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <form action="{{ route('start.session') }}" method="POST" style="width: 100%;">
                        @csrf
                        <input type="hidden" name="template" value="{{ $tmp->name }}">
                        <input type="hidden" name="pose_count" value="{{ $tmp->layout_type }}">
                        <button type="submit" class="btn-use-template-neo">
                            {{ __('Gunakan Karya Ini') }} <i class="bi bi-rocket-takeoff ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 20px; border: 4px dashed var(--dark); box-shadow: 8px 8px 0px var(--dark);">
                <i class="bi bi-folder-x" style="font-size: 4rem; color: var(--dark); margin-bottom: 15px; display: block;"></i>
                <h3 style="font-family: 'Uncut Sans', sans-serif; font-weight: 800; text-transform: uppercase;">{{ __('Belum Ada Karya') }}</h3>
                <p style="font-weight: 600; color: #555;">{{ __('Kreator ini belum mempublikasikan karya apa pun.') }}</p>
            </div>
        @endforelse
    </div>

</div>
@endsection