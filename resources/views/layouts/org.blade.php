<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    
    <link rel="shortcut icon" href="/assets/imgs/icon.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/main.css">
    
    @stack('styles')
</head>

<body>

    <button class="btn text-white shadow" id="sidebarToggle" style="top: 20px; position: fixed;">
        <i class="bi bi-chevron-left" id="sidebarArrow" style="transition: transform 0.3s; display: inline-block;"></i>
    </button>

    <aside class="d-flex flex-column p-3 text-white sidebar border-end border-dark shadow">
        <div class="d-flex flex-column mb-4 px-2 pt-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-shield-lock-fill text-primary fs-3 me-2"></i>
                <span class="fs-4 fw-bold tracking-wide">Pixelplay</span>
            </div>
        </div>

        <hr class="opacity-25 my-2">

        <nav class="nav flex-column gap-1 mb-auto pt-2">
            <a href="{{ route('org.dashboard') }}" class="nav-link-custom @if(request()->routeIs('org.dashboard')) active @endif">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span>Painel Geral</span>
            </a>

            <a href="#" class="nav-link-custom">
                <i class="bi bi-trophy fs-5"></i>
                <span>Gerenciar Torneios</span>
            </a>

            <a href="#" class="nav-link-custom">
                <i class="bi bi-shield-check fs-5"></i>
                <span>Validar Equipes</span>
            </a>

            <a href="#" class="nav-link-custom">
                <i class="bi bi-graph-up-arrow fs-5"></i>
                <span>Relatórios</span>
            </a>
        </nav>

        <div class="dropdown border-top border-secondary pt-3 mt-auto">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2 py-2 rounded-3 user-dropdown-toggle transition"
                data-bs-toggle="dropdown" aria-expanded="false">
                <div class="position-relative me-2">
                    @if (Auth::user()->img)
                       <img src="{{ Auth::user()->img }}" class="rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary" style="width: 38px; height: 38px;" alt="Foto de Perfil">
                    @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary" style="width: 38px; height: 38px; font-size: 0.85rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                </div>
                <div class="d-flex flex-column text-start lh-sm">
                    <strong class="fs-6 text-truncate" style="max-width: 150px;">{{ Auth::user()->name }}</strong>
                    <span class="text-white-50 small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Organizador</span>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-white dropdown-menu-end shadow-lg border-secondary pb-2" style="min-width: 220px;">
                <div class="px-3 py-2 border-bottom border-secondary mb-1">
                    <span class="d-block text-muted fw-bold small">Acesso Administrativo:</span>
                    <span class="d-block text-truncate small">{{ Auth::user()->email }}</span>
                </div>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}">
                        <i class="bi bi-gear text-muted fs-5"></i>
                        <span>Configurações</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider border-secondary"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                            <span>Sair do Painel</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <main class="py-4 px-3 px-md-5">
        
        @if (session('msg'))
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1060;">
                <div id="systemToast" class="toast align-items-center text-white bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2 py-3">
                            <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                            <span class="fw-medium">{{ session('msg') }}</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="d-flex justify-content-between p-3">
        <div class="icon"></div>
        <div class="d-flex flex-column justify-content-center">
            <p class="border-bottom text-center">&copy;{{ date('Y') }} Pixelplay Org - Painel Administrativo</p>
            <div class="d-flex justify-content-between gap-3">
                <a class="nav-link" href="#">Suporte da Org</a>
                <a class="nav-link" href="#">Termos e Diretrizes</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const arrow = document.getElementById('sidebarArrow');

        let isCollapsed = true; 

        if(isCollapsed) {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
            arrow.style.transform = 'rotate(180deg)';
        }

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');

            const collapsedNow = sidebar.classList.contains('collapsed');
            arrow.style.transform = collapsedNow ? 'rotate(180deg)' : 'rotate(0deg)';
        });

        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('systemToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>

</body>
</html>