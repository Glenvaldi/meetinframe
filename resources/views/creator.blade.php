@extends('layouts.app')

@section('title', __('Creator Studio') . ' - MeetinFrame')

@section('head_extra')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* --- GLOBAL SETTINGS --- */
    .dashboard-container {
        max-width: 1200px;
        margin: 40px auto 80px;
        padding: 0 20px;
        animation: fadeUp 0.8s ease;
    }

    /* --- HEADER PAGE --- */
    .dash-header {
        margin-bottom: 40px;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 20px;
    }
    .dash-title h2 { 
        font-family: 'Uncut Sans', sans-serif; font-size: 2.8rem; font-weight: 800; 
        color: var(--dark); margin: 0; text-transform: uppercase; letter-spacing: -1px;
    }
    .dash-title p { margin: 5px 0 0; color: var(--dark); font-weight: 600; font-size: 1.1rem; }

    .btn-logout-neo {
        background: #fff; border: 3px solid var(--dark); color: var(--dark);
        padding: 12px 25px; border-radius: 15px; font-weight: 800; font-size: 1rem;
        transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
        box-shadow: 4px 4px 0px var(--dark); text-transform: uppercase; cursor: pointer;
    }
    .btn-logout-neo:hover { background: #ffb8b8; transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark); }

    /* --- GRID LAYOUT --- */
    .dash-grid {
        display: grid; grid-template-columns: 350px 1fr; gap: 40px;
        align-items: start;
    }

    /* --- CARD BASE BRUTAL --- */
    .studio-card-neo {
        background: #fff; border-radius: 24px; overflow: hidden;
        border: 4px solid var(--dark); box-shadow: 8px 8px 0px var(--dark);
        margin-bottom: 30px; position: relative;
    }

    /* --- 1. PROFILE CARD --- */
    .profile-header-bg {
        height: 120px; 
        background: var(--hot-pink);
        border-bottom: 4px solid var(--dark);
        position: relative;
    }
    .profile-header-bg::after {
        content: ''; position: absolute; inset: 0;
        background-image: radial-gradient(#fff 2px, transparent 2px);
        background-size: 15px 15px; opacity: 0.3;
    }
    
    .profile-avatar-container {
        width: 120px; height: 120px; border-radius: 50%;
        border: 4px solid var(--dark); background: #fff; overflow: hidden;
        margin: -60px auto 15px auto; position: relative; z-index: 2;
        box-shadow: 4px 4px 0px var(--dark);
    }
    .profile-avatar-container img { width: 100%; height: 100%; object-fit: cover; }

    .profile-body { padding: 0 25px 30px; text-align: center; }
    
    .user-name { font-family: 'Uncut Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 5px; text-transform: uppercase; line-height: 1.1;}
    .user-email { font-size: 0.95rem; color: #555; margin-bottom: 20px; font-weight: 700; }
    
    .badge-status {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 800; 
        text-transform: uppercase; border: 3px solid var(--dark); box-shadow: 2px 2px 0px var(--dark);
    }
    .badge-new { background: #ffeaa7; color: var(--dark); }
    .badge-pro { background: #00d2d3; color: var(--dark); }

    .profile-divider { border-top: 3px dashed var(--dark); margin: 25px 0; opacity: 0.2; }
    
    .join-info {
        font-size: 0.9rem; color: var(--dark); display: inline-flex; align-items: center; gap: 8px;
        font-weight: 700; background: #f1f2f6; padding: 10px 15px; border-radius: 12px; border: 2px solid var(--dark);
    }

    /* --- 2. HISTORY CARD --- */
    .card-header-clean {
        padding: 20px 25px; border-bottom: 4px solid var(--dark);
        display: flex; justify-content: space-between; align-items: center; background: #ffeaa7;
    }
    .ch-title { font-weight: 800; color: var(--dark); font-size: 1.2rem; display: flex; align-items: center; gap: 10px; text-transform: uppercase; }
    .ch-badge { background: var(--dark); color: #fff; padding: 4px 12px; border-radius: 50px; font-size: 0.9rem; font-weight: 800; }

    .history-scroll { max-height: 450px; overflow-y: auto; padding: 20px; background: #fdfdfd;}
    .history-scroll::-webkit-scrollbar { width: 8px; }
    .history-scroll::-webkit-scrollbar-track { background: #fff; border-left: 2px solid var(--dark); }
    .history-scroll::-webkit-scrollbar-thumb { background: var(--dark); border-radius: 0; }

    .history-item {
        display: flex; align-items: center; gap: 15px; padding: 15px;
        border: 3px solid var(--dark); border-radius: 16px; margin-bottom: 15px; background: #fff;
        transition: 0.2s; box-shadow: 4px 4px 0px rgba(0,0,0,0.1);
    }
    .history-item:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--hot-pink); }

    .h-img { width: 65px; height: 65px; border-radius: 12px; object-fit: cover; border: 3px solid var(--dark); background: #eee;}
    
    .btn-mini {
        width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        border: 3px solid var(--dark); cursor: pointer; transition: 0.2s; text-decoration: none; font-size: 1.1rem;
        box-shadow: 2px 2px 0px var(--dark);
    }
    .btn-mini:hover { transform: translate(-2px,-2px); box-shadow: 4px 4px 0px var(--dark); }
    .btn-mini-dl { background: #00d2d3; color: var(--dark); }
    .btn-mini-del { background: #ffb8b8; color: var(--dark); }

    /* --- 3. RIGHT COLUMN (STATS) --- */
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
    
    .stat-widget {
        border-radius: 20px; padding: 25px 20px; text-align: center;
        border: 4px solid var(--dark); box-shadow: 6px 6px 0px var(--dark);
        transition: 0.3s; position: relative; overflow: hidden; color: var(--dark);
    }
    .stat-widget.s-1 { background: #ffeaa7; }
    .stat-widget.s-2 { background: #81ecec; }
    .stat-widget.s-3 { background: #ffb8b8; }

    .stat-widget:hover { transform: translate(-4px, -4px); box-shadow: 10px 10px 0px var(--dark); }
    
    .stat-val { font-family: 'Uncut Sans', sans-serif; font-size: 3rem; font-weight: 800; line-height: 1; margin-bottom: 5px; }
    .stat-lbl { font-size: 0.95rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    /* --- 4. FORMS --- */
    .content-section {
        background: #fff; border-radius: 24px; padding: 35px; margin-bottom: 40px;
        border: 4px solid var(--dark); box-shadow: 8px 8px 0px var(--dark);
    }
    .sec-title { font-family: 'Uncut Sans', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--dark); margin-bottom: 30px; display: flex; align-items: center; gap: 10px; text-transform: uppercase;}
    
    .form-label { font-weight: 800; font-size: 1rem; color: var(--dark); margin-bottom: 10px; display: block; text-transform: uppercase;}
    .form-input {
        width: 100%; padding: 15px 20px; border-radius: 15px; border: 3px solid var(--dark);
        background: #fff; font-size: 1rem; font-weight: 600; transition: 0.2s; color: var(--dark);
        box-shadow: inset 4px 4px 0px rgba(0,0,0,0.05);
    }
    .form-input:focus { outline: none; background: #fff; box-shadow: 4px 4px 0px #00d2d3; transform: translate(-2px, -2px); }

    .upload-box {
        border: 4px dashed var(--dark); border-radius: 20px; padding: 40px; text-align: center;
        background: #f9f9f9; cursor: pointer; position: relative; transition: 0.2s;
    }
    .upload-box:hover { border-color: var(--hot-pink); background: #ffeef0; transform: translate(-2px, -2px); box-shadow: 6px 6px 0px var(--dark);}
    .upload-box input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .ub-icon { font-size: 2.5rem; color: var(--dark); margin-bottom: 10px; display: block;}
    
    .btn-submit-neo {
        width: 100%; padding: 18px; border-radius: 15px; border: 4px solid var(--dark);
        font-weight: 800; font-size: 1.2rem; cursor: pointer; transition: 0.2s;
        text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 10px;
        box-shadow: 6px 6px 0px var(--dark);
    }
    .btn-submit-neo:hover { transform: translate(2px, 2px); box-shadow: none; }
    
    .btn-primary-color { background: #00d2d3; color: var(--dark); }
    .btn-red-color { background: var(--hot-pink); color: #fff; }

    /* --- 5. COLLECTION GRID --- */
    .grid-col { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 25px; }
    .col-card {
        background: #fff; border-radius: 20px; overflow: hidden; border: 4px solid var(--dark);
        transition: 0.2s; position: relative; box-shadow: 6px 6px 0px rgba(0,0,0,0.1);
    }
    .col-card:hover { transform: translate(-4px, -4px); box-shadow: 10px 10px 0px var(--hot-pink); }
    
    .col-thumb {
        height: 180px; background: #ffeaa7; display: flex; align-items: center; justify-content: center;
        padding: 15px; border-bottom: 4px solid var(--dark);
    }
    .col-thumb img { max-width: 100%; max-height: 100%; object-fit: contain; filter: drop-shadow(4px 4px 0px rgba(0,0,0,0.2)); }
    .col-meta { padding: 15px; text-align: center; }
    .col-name { font-weight: 800; font-size: 1.1rem; color: var(--dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 8px; text-transform: uppercase;}
    .col-badge { display: inline-block; font-size: 0.8rem; padding: 4px 12px; border-radius: 50px; font-weight: 800; border: 2px solid var(--dark); text-transform: uppercase; margin: 2px;}

    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* =========================================
       PENGATURAN KHUSUS LAYAR HP (MOBILE FIX)
       ========================================= */
    @media (max-width: 992px) { 
        .dash-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .dash-title h2 { font-size: 2.2rem; }
        .btn-logout-neo { width: 100%; justify-content: center; }
        
        .dash-grid { grid-template-columns: 1fr; gap: 30px; } 
        
        .stats-row { grid-template-columns: 1fr; gap: 15px; margin-bottom: 30px; }
        
        .content-section { padding: 25px 20px; border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
        .sec-title { font-size: 1.4rem; }
        
        .form-input, .btn-submit-neo { padding: 14px; font-size: 1rem; border-width: 3px; }
        .upload-box { padding: 25px; border-width: 3px; }

        .studio-card-neo { border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
        .grid-col { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
        .col-card { border-width: 3px; box-shadow: 4px 4px 0px var(--dark); }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">

    <div class="dash-header">
        <div class="dash-title">
            <h2>{{ __('Creator Studio') }}</h2>
            <p>{{ __('Selamat datang kembali, ') }}{{ Auth::user()->name }}! 👋</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button class="btn-logout-neo">
                <i class="bi bi-box-arrow-right"></i> {{ __('Keluar Akun') }}
            </button>
        </form>
    </div>

    <div class="dash-grid">
        
        <div class="sidebar-wrapper">
            
            <div class="studio-card-neo">
                <div class="profile-header-bg"></div>
                
                <div class="profile-avatar-container">
                    <img id="avatar-preview" src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}">
                </div>
                
                <div class="profile-body">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                    
                    @if($roleTitle == 'Newbie')
                        <span class="badge-status badge-new">{{ __('Newbie 🐣') }}</span>
                    @else
                        <span class="badge-status badge-pro">
                            <i class="bi bi-patch-check-fill"></i> {{ __('Content Creator') }}
                        </span>
                    @endif

                    <div class="profile-divider"></div>
                    
                    <div class="join-info">
                        <i class="bi bi-calendar2-week-fill text-danger"></i> {{ __('Bergabung:') }} {{ Auth::user()->created_at->translatedFormat('M Y') }}
                    </div>
                </div>
            </div>

            <div class="studio-card-neo">
                <div class="card-header-clean">
                    <span class="ch-title"><i class="bi bi-clock-history"></i> {{ __('Riwayat Foto') }}</span>
                    <span class="ch-badge">{{ $histories->count() }}</span>
                </div>

                <div class="history-scroll">
                    @if($histories->isEmpty())
                        <div style="text-align:center; padding:40px 0; color:var(--dark); opacity:0.6;">
                            <i class="bi bi-camera-fill" style="font-size:3rem; margin-bottom:10px; display:block;"></i>
                            <p style="font-size:1rem; font-weight:700; text-transform:uppercase;">{{ __('Belum ada sesi foto') }}</p>
                        </div>
                    @else
                        @foreach($histories as $hist)
                            <div class="history-item">
                                <div class="h-thumb-wrap">
                                    @if($hist->image_path)
                                        <img src="{{ asset('storage/' . $hist->image_path) }}" class="h-img">
                                    @else
                                        <div class="h-img" style="display:flex; align-items:center; justify-content:center;"><i class="bi bi-image-alt"></i></div>
                                    @endif
                                </div>
                                <div class="h-content" style="flex:1;">
                                    <div style="font-weight:800; font-size:0.95rem; color:var(--dark);">{{ $hist->created_at->translatedFormat('d M Y') }}</div>
                                    <div style="font-weight:600; font-size:0.85rem; color:#666;">{{ $hist->created_at->format('H:i') }} WIB</div>
                                </div>
                                <div style="display:flex; gap:8px;">
                                    @if($hist->image_path)
                                        <a href="{{ asset('storage/' . $hist->image_path) }}" download class="btn-mini btn-mini-dl" title="{{ __('Download') }}">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('creator.delete_history', $hist->id) }}" method="POST" onsubmit="return confirm('{{ __('Hapus kenangan ini secara permanen?') }}');" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-mini btn-mini-del" title="{{ __('Hapus') }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            
            <div class="stats-row">
                <div class="stat-widget s-1">
                    <div class="stat-val">{{ $sessionCount }}</div>
                    <div class="stat-lbl">{{ __('Total Sesi') }}</div>
                </div>
                <div class="stat-widget s-2">
                    <div class="stat-val">{{ $templateCount }}</div>
                    <div class="stat-lbl">{{ __('Karya Dibuat') }}</div>
                </div>
                <div class="stat-widget s-3">
                    <div class="stat-val"><i class="bi bi-check-lg"></i></div>
                    <div class="stat-lbl">{{ __('Akun Aktif') }}</div>
                </div>
            </div>

            <div class="content-section">
                <h3 class="sec-title"><i class="bi bi-person-bounding-box" style="color:var(--hot-pink);"></i> {{ __('Update Profil') }}</h3>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label">{{ __('Nama Kreator') }}</label>
                        <input type="text" name="name" class="form-input" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group" style="margin-bottom:25px;">
                        <label class="form-label">{{ __('Ganti Foto Profil') }}</label>
                        <div class="upload-box" id="upload-trigger-profile">
                            <i class="bi bi-cloud-arrow-up-fill ub-icon"></i>
                            <p style="margin:0; font-size:1rem; font-weight:700; color:var(--dark);">{{ __('Klik atau Drag Foto ke Sini') }}</p>
                            <input type="file" name="profile_picture" id="profile-input" accept="image/*">
                        </div>
                    </div>
                    <button type="submit" class="btn-submit-neo btn-primary-color">{{ __('Simpan Perubahan') }}</button>
                </form>
            </div>

            <div class="content-section">
                <h3 class="sec-title"><i class="bi bi-palette-fill" style="color:#00d2d3;"></i> {{ __('Upload Frame Baru') }}</h3>
                
                <form action="{{ route('creator.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label">{{ __('Nama Frame (Karya)') }}</label>
                        <input type="text" name="template_name" class="form-input" placeholder="{{ __('Berikan nama yang unik...') }}">
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:20px;">
                        <div class="form-group">
                            <label class="form-label">{{ __('Layout Pose') }}</label>
                            <select name="layout_type" class="form-input">
                                <option value="3">{{ __('3 Pose (Strip)') }}</option>
                                <option value="4">{{ __('4 Pose (Strip)') }}</option>
                                <option value="6">{{ __('6 Pose (Grid)') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('Harga (IDR)') }}</label>
                            <input type="number" name="price" class="form-input" placeholder="{{ __('Isi 0 jika Gratis') }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:25px;">
                        <label class="form-label">{{ __('File Frame (Wajib PNG Transparan)') }}</label>
                        <div class="upload-box">
                            <i class="bi bi-file-earmark-image-fill ub-icon"></i>
                            <p style="margin:0; font-size:1rem; font-weight:700; color:var(--dark);">{{ __('Pilih File PNG (Max 2MB)') }}</p>
                            <input type="file" name="template_file" id="template-input" accept="image/png">
                        </div>
                        <img id="template-preview" src="#" style="display:none; width:100%; height:200px; object-fit:contain; margin-top:15px; border-radius:15px; background:#eee; border:4px solid var(--dark);">
                    </div>

                    @if ($errors->any())
                        <div style="background:#ffb8b8; color:var(--dark); padding:15px 20px; border-radius:15px; margin-bottom:25px; border:3px solid var(--dark); font-weight:700; box-shadow:4px 4px 0px var(--dark);">
                            <ul style="margin:0; padding-left:20px;">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn-submit-neo btn-red-color">
                        <i class="bi bi-rocket-takeoff-fill"></i> {{ __('Publish Frame Sekarang!') }}
                    </button>
                </form>
            </div>

            <div class="content-section" style="background:transparent; border:none; box-shadow:none; padding:0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                    <h3 class="sec-title" style="margin:0;"><i class="bi bi-folder-fill" style="color:#f1c40f;"></i> {{ __('Karya Saya') }}</h3>
                    <span style="background:var(--dark); padding:6px 16px; border-radius:50px; font-size:0.9rem; font-weight:800; color:#fff; border:3px solid #fff; box-shadow:0 0 0 3px var(--dark);">{{ $templates->count() }} {{ __('Item') }}</span>
                </div>

                <div class="grid-col">
                    @if($templates->isEmpty())
                        <div style="grid-column:1/-1; text-align:center; color:var(--dark); padding:50px; background:#fff; border-radius:24px; border:4px dashed var(--dark);">
                            <p style="font-size:1.2rem; font-weight:800; margin:0; text-transform:uppercase;">{{ __('Belum ada karya nih.') }}</p>
                        </div>
                    @else
                        @foreach($templates as $tmp)
                            <div class="col-card">
                                <div class="col-thumb">
                                    <img src="{{ asset('storage/' . $tmp->image_path) }}">
                                </div>
                                <div class="col-meta">
                                    <div class="col-name" title="{{ $tmp->name }}">{{ $tmp->name }}</div>
                                    <div>
                                        <span class="col-badge" style="background: #eee;">{{ $tmp->layout_type }} {{ __('POSE') }}</span>
                                        <span class="col-badge" style="background: {{ $tmp->price == 0 ? '#00d2d3' : '#ffb8b8' }};">{{ $tmp->price == 0 ? __('FREE') : __('PAID') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('js_extra')
<script>
    // Preview Avatar
    const profileInput = document.getElementById('profile-input');
    const avatarPreview = document.getElementById('avatar-preview');
    if(profileInput) {
        profileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { avatarPreview.src = e.target.result; }
                reader.readAsDataURL(file);
            }
        });
    }

    // Preview Template Frame
    const templateInput = document.getElementById('template-input');
    const templatePreview = document.getElementById('template-preview');
    if(templateInput) {
        templateInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    templatePreview.src = e.target.result;
                    templatePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endsection