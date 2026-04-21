@extends('layouts.app')

@section('title', 'Komunitas Kreator - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .container-community {
        max-width: 1200px;
        margin: 40px auto 80px;
        padding: 0 20px;
    }

    /* --- HEADER BRUTAL --- */
    .com-header-neo {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeDown 0.8s ease;
    }
    .com-badge-neo {
        display: inline-block; background: #fff; color: var(--dark); border: 3px solid var(--dark);
        padding: 8px 24px; border-radius: 50px; font-weight: 800; font-size: 0.95rem;
        margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;
        box-shadow: 4px 4px 0px #00d2d3; transform: rotate(-2deg);
    }
    .com-title-neo { 
        font-family: 'Uncut Sans', sans-serif; font-size: 3.5rem; font-weight: 800; 
        color: var(--dark); margin-bottom: 10px; text-transform: uppercase; letter-spacing: -2px;
    }
    .com-desc-neo { color: #444; font-size: 1.15rem; font-weight: 600; max-width: 600px; margin: 0 auto; line-height: 1.6;}

    /* --- GRID LAYOUT --- */
    .creator-grid-neo {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 35px;
        animation: fadeUp 0.8s ease;
    }

    /* --- CREATOR CARD BRUTAL --- */
    .creator-card-neo {
        background: #fff; border-radius: 24px; overflow: hidden;
        box-shadow: 8px 8px 0px var(--dark); border: 4px solid var(--dark);
        transition: 0.2s ease-in-out; text-decoration: none;
        display: flex; flex-direction: column; align-items: center; position: relative;
    }
    .creator-card-neo:hover {
        transform: translate(-4px, -4px); box-shadow: 12px 12px 0px var(--hot-pink);
    }

    /* Mini Header di Kartu */
    .card-mini-cover-neo {
        height: 110px; width: 100%; border-bottom: 4px solid var(--dark);
        background: var(--hot-pink);
        background-image: radial-gradient(#fff 2px, transparent 2px);
        background-size: 15px 15px;
    }
    /* Variasi warna cover */
    .creator-card-neo:nth-child(2n) .card-mini-cover-neo { background-color: #00d2d3; }
    .creator-card-neo:nth-child(3n) .card-mini-cover-neo { background-color: #ffeaa7; }

    .avatar-box-neo {
        width: 100px; height: 100px; border-radius: 50%; border: 4px solid var(--dark);
        background: #fff; overflow: hidden; margin-top: -50px; 
        box-shadow: 4px 4px 0px var(--dark); z-index: 2; position: relative;
    }
    .avatar-box-neo img { width: 100%; height: 100%; object-fit: cover; }

    .card-body-neo { padding: 20px 20px 30px; text-align: center; width: 100%; }
    
    .c-name-neo { font-family: 'Uncut Sans', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 5px; text-transform: uppercase;}
    .c-role-neo { font-size: 0.85rem; color: #555; margin-bottom: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    
    .c-stat-box-neo {
        background: #f9f9f9; border: 3px solid var(--dark); padding: 8px 16px; border-radius: 15px;
        display: inline-flex; align-items: center; gap: 8px; color: var(--dark); font-size: 0.95rem; font-weight: 800;
        box-shadow: 2px 2px 0px var(--dark); text-transform: uppercase;
    }
    .creator-card-neo:hover .c-stat-box-neo { background: #ffeaa7; transform: translate(-2px,-2px); box-shadow: 4px 4px 0px var(--dark);}

    /* Empty State */
    .empty-community-neo {
        grid-column: 1/-1; text-align: center; padding: 60px 20px;
        background: #fff; border-radius: 20px; border: 4px dashed var(--dark);
        box-shadow: 8px 8px 0px var(--dark);
    }
    .btn-main-neo {
        display: inline-flex; padding: 15px 30px; border-radius: 15px; border: 4px solid var(--dark);
        background: var(--hot-pink); color: white; font-weight: 800; font-size: 1.1rem;
        text-decoration: none; text-transform: uppercase; box-shadow: 6px 6px 0px var(--dark); transition: 0.2s;
    }
    .btn-main-neo:hover { transform: translate(2px, 2px); box-shadow: none; color: white; background: var(--dark); }

    @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* LAYAR HP */
    @media (max-width: 768px) {
        .com-title-neo { font-size: 2.5rem; letter-spacing: -1px; }
        .creator-grid-neo { grid-template-columns: 1fr; gap: 25px; }
        .creator-card-neo { box-shadow: 6px 6px 0px var(--dark); border-width: 3px; }
        .creator-card-neo:hover { transform: translate(-2px, -2px); box-shadow: 8px 8px 0px var(--hot-pink); }
    }
</style>
@endsection

@section('content')
<div class="container-community">
    
    <div class="com-header-neo">
        <span class="com-badge-neo">Global Talent</span>
        <h2 class="com-title-neo">Komunitas MeetinFrame</h2>
        <p class="com-desc-neo">Temukan kreator berbakat, jelajahi karya unik, dan dapatkan inspirasi frame terbaik.</p>
    </div>

    <div class="creator-grid-neo">
        @forelse($creators as $creator)
            <a href="{{ route('community.show', $creator->id) }}" class="creator-card-neo">
                <div class="card-mini-cover-neo"></div>
                
                <div class="avatar-box-neo">
                    <img src="{{ $creator->profile_picture ? asset('storage/' . $creator->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($creator->name).'&background=random' }}" alt="{{ $creator->name }}">
                </div>
                
                <div class="card-body-neo">
                    <div class="c-name-neo">{{ $creator->name }}</div>
                    <div class="c-role-neo">Content Creator</div>
                    
                    <div class="c-stat-box-neo">
                        <i class="bi bi-grid-fill" style="color: var(--hot-pink);"></i> {{ $creator->templates_count }} Karya
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-community-neo">
                <i class="bi bi-people-fill" style="font-size: 4rem; margin-bottom: 15px; display: block; color: var(--dark);"></i>
                <h3 style="font-family: 'Uncut Sans', sans-serif; font-weight: 800; text-transform: uppercase;">Belum Ada Kreator</h3>
                <p style="font-weight: 600; color: #555; margin-bottom: 20px;">Belum ada kreator yang bergabung di komunitas ini. Jadilah yang pertama!</p>
                <a href="{{ route('creator') }}" class="btn-main-neo">Mulai Berkarya <i class="bi bi-stars ms-2"></i></a>
            </div>
        @endforelse
    </div>

</div>
@endsection