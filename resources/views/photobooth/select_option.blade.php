@extends('layouts.app')

@section('title', 'Pilih Frame - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* Background sudah diatur polos solid dari app.blade.php */

    .container-selection {
        max-width: 1200px;
        margin: 20px auto 80px;
        padding: 0 20px;
    }

    /* --- HEADER NEO-BRUTALISM --- */
    .page-header-neo {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeDown 0.8s ease;
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

    /* --- SECTION HEADER --- */
    .selection-section {
        margin-bottom: 70px;
        animation: fadeUp 0.8s ease;
    }

    .section-head-neo {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 4px solid var(--dark);
    }

    .section-icon-neo {
        width: 50px; height: 50px;
        border: 3px solid var(--dark);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        box-shadow: 4px 4px 0px var(--dark);
        flex-shrink: 0; 
    }
    
    .section-title-neo {
        font-family: 'Uncut Sans', sans-serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        text-transform: uppercase;
    }
    
    .section-subtitle-neo {
        color: #555;
        font-weight: 700;
        font-size: 1rem;
        margin-left: auto;
    }

    /* --- GRID & CARD BRUTAL --- */
    .options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 35px;
    }

    .option-card-neo {
        background: #fff;
        border: 4px solid var(--dark);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 8px 8px 0px var(--dark);
        transition: 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .option-card-neo:hover {
        transform: translate(-4px, -4px);
        box-shadow: 12px 12px 0px var(--hot-pink);
    }

    .theme-custom .option-card-neo:hover { box-shadow: 12px 12px 0px #f1c40f; } 
    .theme-official .option-card-neo:hover { box-shadow: 12px 12px 0px var(--hot-pink); } 
    .theme-community .option-card-neo:hover { box-shadow: 12px 12px 0px #00d2d3; } 

    /* Image Wrapper */
    .card-img-box-neo {
        height: 200px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        border-bottom: 4px solid var(--dark);
    }
    
    .theme-custom .card-img-box-neo { background: #ffeaa7; }
    .theme-official .card-img-box-neo { background: var(--pale-pink); }
    .theme-community .card-img-box-neo { background: #c7ecee; }
    
    .card-img-box-neo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 20px;
        transition: 0.3s ease;
        filter: drop-shadow(4px 4px 0px rgba(0,0,0,0.15));
    }

    .option-card-neo:hover .card-img-box-neo img {
        transform: scale(1.1) rotate(2deg);
    }

    /* Content Bawah Kartu */
    .card-body-neo {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title-neo {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 8px;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-meta-neo {
        font-size: 0.95rem;
        font-weight: 700;
        color: #555;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-price-neo {
        background: #00d2d3;
        color: var(--dark);
        padding: 4px 12px;
        border-radius: 50px;
        border: 2px solid var(--dark);
        font-weight: 800;
        font-size: 0.8rem;
        box-shadow: 2px 2px 0px var(--dark);
        text-transform: uppercase;
    }
    .badge-price-neo.paid { background: #ffeaa7; }

    /* Tombol Pilih Brutal */
    .btn-select-neo {
        margin-top: auto;
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: 3px solid var(--dark);
        font-weight: 800;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        text-transform: uppercase;
        box-shadow: 4px 4px 0px var(--dark);
    }

    .btn-select-neo:hover {
        transform: translate(2px, 2px);
        box-shadow: 0px 0px 0px var(--dark);
    }

    .btn-custom { background: #fff; color: var(--dark); }
    .btn-custom:hover { background: #f1c40f; }

    .btn-official { background: var(--hot-pink); color: white; }
    .btn-official:hover { background: var(--dark); color: white; }

    .btn-creator { background: var(--dark); color: white; }
    .btn-creator:hover { background: #00d2d3; color: var(--dark); }

    /* Creator Tag */
    .creator-tag {
        position: absolute; top: 12px; right: 12px;
        background: #fff; border: 2px solid var(--dark);
        color: var(--dark); font-weight: 800; font-size: 0.75rem;
        padding: 4px 10px; border-radius: 50px;
        box-shadow: 2px 2px 0px var(--dark);
        z-index: 2;
    }

    /* Empty State Komunitas */
    .empty-state-neo {
        grid-column: 1/-1; text-align: center; padding: 60px 20px;
        background: #fff; border-radius: 20px; border: 4px dashed var(--dark);
        box-shadow: 8px 8px 0px var(--dark);
    }

    /* ANIMASI */
    @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* HP RESPONSIVE */
    @media (max-width: 768px) {
        .page-header-neo { margin-bottom: 40px; }
        .page-title-neo { font-size: 2.2rem; letter-spacing: -1px; }
        .page-desc-neo { font-size: 1rem; }
        
        .selection-section { margin-bottom: 50px; }
        
        .section-head-neo { 
            flex-direction: column; 
            align-items: flex-start; 
            gap: 10px; 
            padding-bottom: 15px;
        }
        .section-subtitle-neo { 
            margin-left: 0; 
            font-size: 0.9rem; 
            line-height: 1.4;
        }

        .options-grid { grid-template-columns: 1fr; gap: 25px; }

        .option-card-neo { box-shadow: 4px 4px 0px var(--dark); }
        .option-card-neo:hover { transform: translate(-2px, -2px); }
        
        .theme-custom .option-card-neo:hover { box-shadow: 6px 6px 0px #f1c40f; } 
        .theme-official .option-card-neo:hover { box-shadow: 6px 6px 0px var(--hot-pink); } 
        .theme-community .option-card-neo:hover { box-shadow: 6px 6px 0px #00d2d3; } 
    }
</style>
@endsection

@section('content')
<div class="container-selection">

    <div class="page-header-neo">
        <div class="page-badge-neo">
            <i class="bi bi-magic text-danger"></i> Langkah 1
        </div>
        <h1 class="page-title-neo">Pilih Gaya Frame</h1>
        <p class="page-desc-neo">Tentukan jumlah pose dan gaya frame. Pilih kanvas polos untuk kreasi bebas, atau gunakan template menarik yang sudah tersedia.</p>
    </div>

    <div class="selection-section theme-custom">
        <div class="section-head-neo">
            <div class="section-icon-neo" style="background: #ffeaa7; color: var(--dark);">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <div>
                <h3 class="section-title-neo">Custom Layout</h3>
                <span class="section-subtitle-neo">Frame polos, edit stiker dan warna latar sesuai kreativitas Anda.</span>
            </div>
        </div>

        <form method="POST" action="{{ route('start.session') }}">
            @csrf
            <input type="hidden" name="option_type" value="custom">
            
            <div class="options-grid">
                <div class="option-card-neo">
                    <div class="card-img-box-neo">
                        <img src="{{ asset('images/custom_4pose.png') }}" alt="4 Pose">
                    </div>
                    <div class="card-body-neo">
                        <h4 class="card-title-neo">Classic Strip</h4>
                        <div class="card-meta-neo">
                            <span><i class="bi bi-images"></i> 4 Pose</span>
                            <span class="badge-price-neo">Gratis</span>
                        </div>
                        <button type="submit" name="custom_type" value="Custom A" class="btn-select-neo btn-custom">
                            Pilih Layout Ini
                        </button>
                    </div>
                </div>

                <div class="option-card-neo">
                    <div class="card-img-box-neo">
                        <img src="{{ asset('images/custom_3pose.png') }}" alt="3 Pose">
                    </div>
                    <div class="card-body-neo">
                        <h4 class="card-title-neo">Triple Fun</h4>
                        <div class="card-meta-neo">
                            <span><i class="bi bi-images"></i> 3 Pose</span>
                            <span class="badge-price-neo">Gratis</span>
                        </div>
                        <button type="submit" name="custom_type" value="Custom B" class="btn-select-neo btn-custom">
                            Pilih Layout Ini
                        </button>
                    </div>
                </div>

                <div class="option-card-neo">
                    <div class="card-img-box-neo">
                        <img src="{{ asset('images/custom_2pose.png') }}" alt="2 Pose">
                    </div>
                    <div class="card-body-neo">
                        <h4 class="card-title-neo">Duo Shot</h4>
                        <div class="card-meta-neo">
                            <span><i class="bi bi-images"></i> 2 Pose</span>
                            <span class="badge-price-neo">Gratis</span>
                        </div>
                        <button type="submit" name="custom_type" value="Custom C" class="btn-select-neo btn-custom">
                            Pilih Layout Ini
                        </button>
                    </div>
                </div>

                <div class="option-card-neo">
                    <div class="card-img-box-neo">
                        <img src="{{ asset('images/custom_6pose.png') }}" alt="6 Pose">
                    </div>
                    <div class="card-body-neo">
                        <h4 class="card-title-neo">Grid Collage</h4>
                        <div class="card-meta-neo">
                            <span><i class="bi bi-images"></i> 6 Pose</span>
                            <span class="badge-price-neo">Gratis</span>
                        </div>
                        <button type="submit" name="custom_type" value="Custom D" class="btn-select-neo btn-custom">
                            Pilih Layout Ini
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="selection-section theme-official">
        <div class="section-head-neo">
            <div class="section-icon-neo" style="background: var(--hot-pink); color: white;">
                <i class="bi bi-stars"></i>
            </div>
            <div>
                <h3 class="section-title-neo">Official Templates</h3>
                <span class="section-subtitle-neo">Koleksi desain eksklusif langsung dari MeetinFrame.</span>
            </div>
        </div>

        <div class="options-grid">
            @php
                $officialTemplates = [
                    ['name' => 'Template 1', 'img' => 'template1.png', 'pose' => 3, 'title' => 'Vibe Pop Retro'],
                    ['name' => 'Template 2', 'img' => 'template2.png', 'pose' => 3, 'title' => 'Boom Cheer Yellow'],
                    ['name' => 'Template 3', 'img' => 'template3.png', 'pose' => 3, 'title' => 'Cosmic Fun Purple'],
                    ['name' => 'Template 4', 'img' => 'template4.png', 'pose' => 4, 'title' => 'Soft Pastel Bliss'],
                    ['name' => 'Template 5', 'img' => 'template5.png', 'pose' => 3, 'title' => 'Happy Halloween'],
                    ['name' => 'Template 6', 'img' => 'template6.png', 'pose' => 3, 'title' => 'Candy Sweet Pink'],
                    ['name' => 'Template 7', 'img' => 'template7.png', 'pose' => 4, 'title' => 'Love Happy Day'],
                    ['name' => 'Template 8', 'img' => 'template8.png', 'pose' => 3, 'title' => 'Sunkissed Groove'],
                ];
            @endphp

            @foreach($officialTemplates as $t)
            <div class="option-card-neo">
                <div class="card-img-box-neo">
                    <img src="{{ asset('images/' . $t['img']) }}" alt="{{ $t['name'] }}">
                </div>
                <div class="card-body-neo">
                    <h4 class="card-title-neo">{{ $t['title'] }}</h4>
                    <div class="card-meta-neo">
                        <span><i class="bi bi-camera"></i> {{ $t['pose'] }} Pose</span>
                        <span class="badge-price-neo">Gratis</span>
                    </div>
                    
                    <form action="{{ route('start.session') }}" method="POST" style="width:100%;">
                        @csrf
                        <input type="hidden" name="template" value="{{ $t['name'] }}">
                        <input type="hidden" name="pose_count" value="{{ $t['pose'] }}">
                        <button type="submit" class="btn-select-neo btn-official">
                            Gunakan Template
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="selection-section theme-community">
        <div class="section-head-neo">
            <div class="section-icon-neo" style="background: #00d2d3; color: var(--dark);">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <h3 class="section-title-neo">Community Creations</h3>
                <span class="section-subtitle-neo">Beragam karya kreatif dari komunitas pengguna.</span>
            </div>
        </div>

        <div class="options-grid">
            @forelse($creatorTemplates ?? [] as $tmp)
                <div class="option-card-neo">
                    <div class="card-img-box-neo">
                        <div class="creator-tag">
                            <i class="bi bi-person-fill"></i> Creator
                        </div>
                        <img src="{{ asset('storage/' . $tmp->image_path) }}" alt="{{ $tmp->name }}">
                    </div>
                    
                    <div class="card-body-neo">
                        <h4 class="card-title-neo" title="{{ $tmp->name }}">{{ $tmp->name }}</h4>
                        
                        <div style="font-size: 0.95rem; font-weight: 700; color: #555; margin-bottom: 15px;">
                            Oleh 
                            @if($tmp->creator)
                                <a href="{{ route('community.show', $tmp->creator->id) }}" style="color: var(--hot-pink); text-decoration: none;">
                                    {{ $tmp->creator->name }}
                                </a>
                            @else
                                Anonim
                            @endif
                        </div>
                        
                        <div class="card-meta-neo">
                            <span><i class="bi bi-camera"></i> {{ $tmp->layout_type }} Pose</span>
                            @if($tmp->price == 0)
                                <span class="badge-price-neo">Gratis</span>
                            @else
                                <span class="badge-price-neo paid">Rp {{ number_format($tmp->price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <form action="{{ route('start.session') }}" method="POST" style="width:100%;">
                            @csrf
                            <input type="hidden" name="template" value="{{ $tmp->name }}">
                            <input type="hidden" name="pose_count" value="{{ $tmp->layout_type }}">
                            <button type="submit" class="btn-select-neo btn-creator">
                                Gunakan Karya Ini
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state-neo">
                    <i class="bi bi-folder-x" style="font-size: 4rem; color: var(--dark); margin-bottom: 15px; display:block;"></i>
                    <h3 style="font-family: 'Uncut Sans', sans-serif; font-weight: 800; text-transform: uppercase;">Belum Ada Karya</h3>
                    <p style="font-weight: 600; color: #555; margin-bottom: 20px;">Komunitas masih menunggu karya terbaikmu. Jadilah yang pertama mengunggah template di sini!</p>
                    <a href="{{ route('creator') }}" class="btn-select-neo btn-official" style="display: inline-flex; width: auto; padding: 12px 30px;">
                        Unggah Karya Sekarang <i class="bi bi-rocket-takeoff-fill ms-2"></i>
                    </a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection