<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MeetinFrame - @yield('title', __('Photobooth Online'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=uncut-sans@400,600,700,800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --hot-pink: #ff0055;
            --soft-pink: #ff85a1;
            --pale-pink: #fff0f3;
            --light-bg: #fcfcfc;
            --dark: #1a1a1a;
            --thick-border: 3px solid #1a1a1a;
            --neo-shadow: 8px 8px 0px #1a1a1a;
        }

        body {
            /* Kunci font latin agar aksara korea tidak mengubah style */
            font-family: 'Plus Jakarta Sans', 'Malgun Gothic', sans-serif;
            background-color: var(--pale-pink); 
            color: var(--dark);
            min-height: 100vh;
        }

        /* --- MEGA BOLD NAVBAR --- */
        .nav-wrapper {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1050;
            padding: 25px;
        }

        .navbar-neo {
            background: white;
            border: var(--thick-border);
            box-shadow: var(--neo-shadow);
            border-radius: 24px;
            padding: 15px 30px;
            transition: 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .navbar-neo:hover {
            transform: translate(-2px, -2px);
            box-shadow: 12px 12px 0px #1a1a1a;
        }

        .brand-text {
            /* Pastikan Uncut Sans sekarang dipanggil dengan benar */
            font-family: 'Uncut Sans', Arial, sans-serif !important; 
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -2px;
            color: var(--dark);
            text-transform: lowercase;
            text-decoration: none;
            -webkit-font-smoothing: antialiased;
        }

        .brand-text span {
            color: var(--hot-pink);
            font-style: italic;
        }

        /* Nav Links */
        .nav-link-neo {
            font-weight: 800;
            font-size: 1rem;
            color: var(--dark) !important;
            padding: 10px 15px !important;
            margin: 0 2px;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: 0.2s;
            text-decoration: none;
        }

        .nav-link-neo:hover {
            background: var(--soft-pink);
            border-color: var(--dark);
            transform: rotate(-2deg);
        }

        .nav-link-neo.active {
            background: var(--hot-pink);
            color: white !important;
            border-color: var(--dark);
            transform: scale(1.05) rotate(2deg);
        }

        /* Button "Darderdor" */
        .btn-neo-main {
            background: var(--hot-pink);
            color: white;
            border: var(--thick-border);
            box-shadow: 4px 4px 0px #1a1a1a;
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-neo-main:hover {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px #1a1a1a;
            color: white;
            background: var(--dark);
        }

        /* Dropdown Bahasa */
        .lang-dropdown-toggle {
            background: #fff; color: var(--dark); border: 3px solid var(--dark);
            padding: 10px 15px; border-radius: 12px; font-weight: 800;
            display: flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.2s;
            box-shadow: 3px 3px 0px var(--dark);
        }
        .lang-dropdown-toggle:hover { background: #ffeaa7; transform: translate(-2px,-2px); box-shadow: 5px 5px 0px var(--dark); }
        .lang-menu-neo {
            border: 3px solid var(--dark); border-radius: 16px; padding: 10px;
            box-shadow: 6px 6px 0px var(--dark); margin-top: 10px !important;
        }
        .lang-item { font-weight: 700; border-radius: 8px; transition: 0.2s; }
        .lang-item:hover { background: var(--hot-pink); color: white; transform: translateX(5px); }

        main {
            padding-top: 160px;
            padding-bottom: 50px;
        }

        .sticker { position: fixed; z-index: -1; opacity: 0.5; pointer-events: none; filter: drop-shadow(4px 4px 0px rgba(0,0,0,0.1)); }

        .footer-brutal { background: var(--dark); color: white; padding: 80px 40px; margin-top: 50px; border-top: 10px solid var(--hot-pink); }
        .footer-big-text { font-family: 'Uncut Sans', Arial, sans-serif !important; font-weight: 800; font-size: 5vw; line-height: 0.8; margin-bottom: 40px; color: var(--soft-pink); text-transform: uppercase; -webkit-font-smoothing: antialiased; }
        .footer-grid-link h5 { font-weight: 800; color: var(--hot-pink); margin-bottom: 25px; }
        .footer-grid-link a { color: #ccc; text-decoration: none; display: block; margin-bottom: 15px; font-weight: 600; font-size: 1.1rem; }
        .footer-grid-link a:hover { color: var(--soft-pink); text-decoration: underline; }

        @media (max-width: 991px) {
            .nav-wrapper { padding: 15px; }
            .navbar-neo { padding: 12px 20px; border-radius: 16px; }
            .brand-text { font-size: 1.5rem; }
            
            .navbar-collapse {
                background: white; border: var(--thick-border); border-radius: 16px;
                padding: 20px; margin-top: 15px; box-shadow: 6px 6px 0px var(--dark);
            }

            .nav-link-neo { margin: 5px 0; text-align: center; display: block; }
            .auth-buttons-mobile { flex-direction: column; width: 100%; margin-top: 15px; gap: 15px !important; }
            .auth-buttons-mobile .btn-neo-main, .auth-buttons-mobile .dropdown { width: 100%; }
            .lang-dropdown-toggle { width: 100%; justify-content: center; }
            
            main { padding-top: 110px; }
            .footer-brutal { padding: 50px 20px; }
            .footer-big-text { font-size: 13vw; margin-bottom: 30px; }
            .sticker { display: none !important; }
        }
    </style>

    @yield('head_extra')
</head>
<body>

    <i class="bi bi-stars sticker" style="top: 20%; left: 5%; font-size: 5rem; color: var(--hot-pink);"></i>
    <i class="bi bi-camera-fill sticker" style="bottom: 10%; right: 5%; font-size: 8rem; color: var(--soft-pink); transform: rotate(-15deg);"></i>
    <i class="bi bi-heart-fill sticker" style="top: 15%; right: 10%; font-size: 4rem; color: var(--hot-pink); transform: rotate(20deg);"></i>

    <div class="nav-wrapper">
        <nav class="navbar navbar-expand-lg navbar-neo">
            <div class="container-fluid p-0">
                <a class="navbar-brand brand-text" href="{{ route('home') }}">
                    meetin<span>frame</span>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMega">
                    <i class="bi bi-list" style="font-size: 2.2rem; color: var(--dark);"></i>
                </button>

                <div class="collapse navbar-collapse" id="navMega">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link-neo {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Beranda') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-neo {{ Request::is('select-option') ? 'active' : '' }}" href="{{ route('select.option') }}">{{ __('Mulai Foto') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-neo {{ Request::is('couple*') ? 'active' : '' }}" href="{{ route('couple.lobby') }}">
                                {{ __('Foto Couple') }} <i class="bi bi-heart-fill" style="color: var(--hot-pink);"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-neo {{ Request::is('creator*') ? 'active' : '' }}" href="{{ route('creator') }}">{{ __('Studio') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-neo {{ Request::is('community') ? 'active' : '' }}" href="{{ route('community.index') }}">{{ __('Komunitas') }}</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-3 auth-buttons-mobile">
                        <div class="dropdown">
                            <button class="lang-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-globe-americas"></i> {{ strtoupper(app()->getLocale()) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end lang-menu-neo">
                                <li><a class="dropdown-item lang-item" href="{{ url('lang/id') }}">🇮🇩 Indonesia</a></li>
                                <li><a class="dropdown-item lang-item" href="{{ url('lang/en') }}">🇬🇧 English</a></li>
                                <li><a class="dropdown-item lang-item" href="{{ url('lang/ko') }}">🇰🇷 한국어</a></li>
                            </ul>
                        </div>

                        @auth
                            <a href="{{ route('creator') }}" class="btn-neo-main">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-neo-main">{{ __('Masuk') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer-brutal">
        <div class="container-fluid">
            <div class="footer-big-text">
                {!! __("Let's Make Some<br>Memories!") !!}
            </div>
            
            <div class="row">
                <div class="col-lg-5 mb-5">
                    <p class="h4 fw-bold mb-4" style="max-width: 450px; line-height: 1.6;">
                        {{ __('Photobooth online paling gokil buat kamu yang bosen sama gaya yang itu-itu aja.') }}
                    </p>
                    <div class="d-flex gap-4">
                        <a href="#" class="text-white h2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white h2"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-white h2"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 footer-grid-link">
                    <h5>{{ __('MENU') }}</h5>
                    <a href="{{ route('home') }}">{{ __('Beranda') }}</a>
                    <a href="{{ route('select.option') }}">{{ __('Booth') }}</a>
                    <a href="{{ route('couple.lobby') }}">{{ __('Couple Mode') }}</a>
                </div>
                <div class="col-6 col-lg-2 footer-grid-link">
                    <h5>{{ __('STUDIO') }}</h5>
                    <a href="{{ route('creator') }}">{{ __('Frame Creator') }}</a>
                    <a href="{{ route('community.index') }}">{{ __('Community Hub') }}</a>
                    <a href="#">{{ __('Assets') }}</a>
                </div>
                <div class="col-lg-3">
                    <div class="p-4" style="border: 4px solid var(--soft-pink); border-radius: 20px;">
                        <h5 class="fw-bold text-white mb-3" style="font-size: 1.2rem;">{{ __('Newsletter Coy!') }}</h5>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{{ __('Email lo...') }}" style="border: 2px solid var(--dark); font-weight: 600;">
                            <button class="btn btn-danger fw-bold" style="border: 2px solid var(--dark);">{{ __('KIRIM') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-5 mb-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center fw-bold opacity-50">
                &copy; {{ date('Y') }} MeetinFrame. No AI, Pure Passion.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.nav-link-neo').forEach(link => {
            link.addEventListener('click', () => {
                const navbarCollapse = document.getElementById('navMega');
                if (navbarCollapse.classList.contains('show')) {
                    new bootstrap.Collapse(navbarCollapse).toggle();
                }
            });
        });
    </script>
    @yield('js_extra')
</body>
</html>