<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('/assets/imgs/Icon-white.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/main.css">

    <style>
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: #1a1a1f;
            z-index: 99999; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        #page-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }


        .sidebar {
            height: 100dvh;
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed {
            margin-left: -260px;
        }

        main {
            transition: margin-left 0.3s ease;
            padding-left: 280px;
        }

        body.sidebar-collapsed main {
            padding-left: 20px;
        }

        .nav-wrapper {
            flex: 1 0 auto;
        }

        .inbox-item {
            background-color: #1a1a1f !important;
            transition: background-color 0.2s ease;
        }
        .inbox-item:hover {
            background-color: #24242b !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="page-loader">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <div class="mt-3 text-white-50 fw-bold small text-uppercase tracking-wide">
            Carregando PixelPlay...
        </div>
    </div>

    <button class="btn text-white shadow" id="sidebarToggle" style="top: 20px; position: fixed; z-index: 1060;">
        <i class="bi bi-chevron-left" id="sidebarArrow" style="transition: transform 0.3s; display: inline-block;"></i>
    </button>

    <aside class="d-flex flex-column p-3 text-white sidebar border-end border-dark shadow">
        
        <div class="nav-wrapper">
            <div class="d-flex align-items-center justify-content-center">
                @if(Auth::user()->role == "organizador")
                    <a href="{{ route('org.dashboard') }}">
                        <img src="{{ asset('/assets/imgs/Icon-white.png') }}" alt="Pixelplay Icon" style="max-height: 140px; width: auto;">
                    </a>
                @else
                    <a href="{{ route('player.eventos') }}">
                        <img src="{{ asset('/assets/imgs/Icon-white.png') }}" alt="Pixelplay Icon" style="max-height: 140px; width: auto;">
                    </a>
                @endif
            </div>

            <hr class="opacity-25 my-2">

            <nav class="nav flex-column gap-1 pt-2">
                <a href="#" class="nav-link-custom position-relative" data-bs-toggle="modal" data-bs-target="#inboxModal">
                    <i class="bi bi-bell fs-5"></i> <span>Notificações</span>
                    @php
                        $unreadCount = Auth::user()->inboxes()->where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
                @if(Auth::user()->role == "organizador")
                    <a href="{{ route('org.dashboard') }}"
                        class="nav-link-custom {{ request()->routeIs('org.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 fs-5"></i> <span>Painel Geral</span>
                    </a>
                    <a href="{{ route('admin.checkin.scan') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.checkin.scan*') ? 'active' : '' }}">
                        <i class="bi bi-qr-code fs-5"></i> <span>Validador de Ingressos</span>
                    </a>
                @endif
                <a href="{{ route('player.eventos') }}" 
                    class="nav-link-custom {{ request()->routeIs('player.eventos') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event fs-5"></i> <span>Eventos</span>
                </a>
                <a href="{{ route('player.meuseventos') }}" 
                    class="nav-link-custom {{ request()->routeIs('player.meuseventos') ? 'active' : '' }}">
                    <i class="bi bi-bookmark-star fs-5"></i> <span>Minhas Inscrições</span>
                </a>
                <a href="{{ route('player.times') }}"
                    class="nav-link-custom {{ request()->routeIs('player.times*') ? 'active' : '' }}">
                    <i class="bi bi-people fs-5"></i> <span>Procurar Times</span>
                </a>
                @if(Auth::user()->team_id)
                    <a href="{{ route('player.time.show', Auth::user()->team_id) }}" class="nav-link-custom">
                        <i class="bi bi-columns-gap fs-5"></i> <span>Dashboard do Time</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="dropdown border-top py-1 border-secondary">
            <a href="#"
                class="d-flex align-items-center text-white p-2 text-decoration-none dropdown-toggle rounded-3 user-dropdown-toggle"
                data-bs-toggle="dropdown">
                <div class="position-relative me-2">
                        <img src="{{ asset(Auth::user()->img ?? '/assets/profiles/avatar/default.png') }}" class="rounded-circle border border-2 border-primary" style="width: 38px; height: 38px;" alt="Perfil">
                </div>
                <div class="d-flex flex-column text-start lh-sm">
                    <strong class="fs-6 text-truncate" style="max-width: 120px;">{{ Auth::user()->name }}</strong>
                    <span class="text-white-50 small text-uppercase fw-bold"
                        style="font-size: 0.65rem;">{{ Auth::user()->role }}</span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-white dropdown-menu-end shadow-lg border-secondary pb-2"
                style="min-width: 220px;">
                <div class="px-3 py-2 border-bottom border-secondary mb-1">
                    <span class="d-block text-muted fw-bold small">Acesso: {{ Auth::user()->email }}</span>
                </div>
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{ route('profile.show', Auth::user()->id) }}"><i
                            class="bi bi-person-vcard text-muted"></i> Meu Perfil</a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}"><i
                            class="bi bi-gear text-muted"></i> Configurações</a></li>
                <li>
                    <hr class="dropdown-divider border-secondary">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger"><i
                                class="bi bi-box-arrow-right"></i> Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <main class="py-4 px-3 px-md-5">

        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
            @if (session('msg'))
                <div class="toast show align-items-center text-white bg-dark border-0 shadow-lg" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            {{ session('msg') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg mb-2" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                {{ $error }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @yield('content')
    </main>

    <div class="modal fade" id="inboxModal" tabindex="-1" aria-labelledby="inboxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content bg-dark text-white border-secondary border-opacity-25 rounded-4 shadow-lg">
                <div class="modal-header border-bottom border-secondary border-opacity-10 p-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-open text-primary fs-5"></i>
                        <h5 class="modal-title fw-bold text-uppercase tracking-wide" id="inboxModalLabel">Central de Alertas</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @php
                        $messages = Auth::user()->inboxes()->take(8)->get();
                    @endphp

                    @if($messages->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-mailbox2 fs-1 text-muted d-block mb-2"></i>
                            <span class="text-white-50 fw-bold small d-block">Nenhuma mensagem por aqui!</span>
                            <p class="text-muted extra-small mb-0 px-4" style="font-size: 0.8rem;">Avisaremos você quando novos convites ou atualizações de torneios surgirem.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($messages as $msg)
                                <div class="list-group-item inbox-item border-bottom border-secondary border-opacity-10 text-white p-3 d-flex align-items-start justify-content-between gap-2 position-relative {{ !$msg->is_read ? 'border-start border-primary border-3 bg-dark bg-opacity-25' : '' }}">
                                    
                                    <div class="d-flex gap-3 align-items-start w-100">
                                        <div class="p-2 rounded-3 d-flex align-items-center justify-content-center mt-1 {{ !$msg->is_read ? 'bg-primary bg-opacity-10 text-white' : 'bg-secondary bg-opacity-10 text-white' }}" style="width: 38px; height: 38px; min-width: 38px;">
                                            <i class="bi {{ $msg->tournament_id ? 'bi-trophy' : 'bi-chat-left-dots' }} fs-5"></i>
                                        </div>

                                        <div class="text-wrap w-100">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <h6 class="m-0 fw-bold small {{ !$msg->is_read ? 'text-white' : 'text-white-50' }}">{{ $msg->title }}</h6>
                                                @if(!$msg->is_read)
                                                    <span class="badge bg-primary p-1 rounded-circle" style="width: 6px; height: 6px; min-width:6px;"></span>
                                                @endif
                                            </div>
                                            <p class="mb-2 text-white text-break" style="font-size: 0.85rem; line-height: 1.3;">{{ $msg->message }}</p>
                                            
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <span class="text-white-50 opacity-50 small" style="font-size: 0.7rem;">
                                                    {{ $msg->created_at->diffForHumans() }}
                                                </span>
                                                
                                                <div class="d-flex gap-2">
                                                    @if($msg->tournament_id)
                                                        <a href="{{ route('player.torneio.show', $msg->tournament_id) }}" class="btn btn-sm btn-outline-light py-0 px-2 " style="font-size: 0.75rem;">
                                                            Ver Evento
                                                        </a>
                                                    @endif

                                                     @if($msg->event_id)
                                                        <a href="{{ route('player.evento.show', $msg->event_id) }}" class="btn btn-sm btn-outline-light py-0 px-2 " style="font-size: 0.75rem;">
                                                            Ver Torneio
                                                        </a>
                                                    @endif
                                                    
                                                    @if(!$msg->is_read)
                                                        <form action="{{ route('inbox.read', $msg->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-link text-primary p-0 text-decoration-none" style="font-size: 0.75rem;">
                                                                Marcar como lida
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="d-flex justify-content-center p-3">
        <div class="icon" style="cursor: pointer" onclick="window.location.href='{{ route('landing') }}'"></div>
        <div class="d-flex flex-column justify-content-center">
            <p class="border-bottom text-center">&copy;{{ date('Y') }} Todos os direitos reservados</p>
            <div class="d-flex justify-content-center gap-3">
                <a class="nav-link" href="{{ route('faq') }}">FAQ:Central de ajuda</a>
                <a class="nav-link" href="{{ route('contato') }}">Contato</a>
                <a class="nav-link" href="{{ route('sobre') }}">Sobre nós</a>
            </div>

            <small class="text-secondary text-center px-2" style="max-width: 900px;">
                As imagens, logotipos, nomes, equipes, campeonatos e demais elementos
                relacionados aos jogos eletrônicos apresentados neste site pertencem aos
                seus respectivos titulares. Seu uso destina-se exclusivamente a fins
                acadêmicos e ilustrativos, sem finalidade comercial ou reivindicação de
                propriedade.
            </small>

        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        window.addEventListener('load', function () {
            const loader = document.getElementById('page-loader');
            loader.classList.add('hidden');
            
            setTimeout(() => {
                loader.style.display = 'none';
            }, 0);
        });

        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const arrow = document.getElementById('sidebarArrow');
        sidebar.classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-collapsed');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
            arrow.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    </script>
</body>

</html>