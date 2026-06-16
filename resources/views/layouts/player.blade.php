<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/main.css">
    <!-- FAVICON -->
    <!-- <link rel="shortcut icon" href="/assets/favicon/" type="image/x-icon"> -->
    <!-- CSS STACK -->
    @stack('styles')
</head>

<body class="sidebar-collapsed">

    <header class="container-fluid z-3 p-0 position-relative">
        {{-- Botão de toggle da sidebar --}}
        <button class="btn position-fixed" id="sidebarToggle" style="top: 5%; left: 270px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                class="bi bi-chevron-left text-white" id="sidebarArrow" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
            </svg>
        </button>
        <div class="d-flex flex-column flex-shrink-0 p-3 py-5 shadow-lg sidebar collapsed">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('player.torneios') }}"
                        class="nav-link @if(request()->routeIs('player.torneios')) active @endif"
                        @if(request()->routeIs('player.torneios')) aria-current="page" @endif>
                        <span class="sidebar-text">Torneios e Eventos</span>
                    </a>

                    <a href="#" class="nav-link">
                        <span class="sidebar-text">Meus Torneios e Eventos</span>
                    </a>

                    <a href="{{ route('player.times') }}"
                        class="nav-link @if(request()->routeIs('player.times')) active @endif"
                        @if(request()->routeIs('player.times')) aria-current="page" @endif>
                        <span class="sidebar-text">Times</span>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown border-top border-secondary pt-3 mt-2">
                <a href="#"
                    class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2 py-1 rounded-3 user-dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="position-relative me-2">
                        <img src="https://github.com/daniel3131-web.png" alt="Avatar de Daniel" width="36" height="36"
                            class="rounded-circle border border-2 border-primary shadow-sm">
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-1"
                            style="width: 10px; height: 10px;"></span>
                    </div>
                    <div class="d-flex flex-column text-start lh-sm sidebar-text">
                        <strong class="fs-6">Daniel</strong>
                        <span class="text-muted small" style="font-size: 0.75rem;">Jogador</span>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border-secondary animated-dropdown"
                    style="min-width: 200px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                            <i class="bi bi-person-vcard text-secondary fs-5"></i>
                            <span>Meu perfil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                            <i class="bi bi-wallet2 text-success fs-5"></i>
                            <span>Minha carteira</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider border-secondary">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger-hover" href="/profile">
                            <i class="bi bi-gear text-secondary fs-5"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider border-secondary">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" href="#">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                            <span>Sair da conta</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="d-flex flex-column py-3">
        @if (session('msg'))
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Mensagem</h5>
                            <button type="button" class="btn-close" onclick="this.closest('.modal').remove()"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="msg">{{ session('msg') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                onclick="this.closest('.modal').remove()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="d-flex justify-content-between p-3">
        <div class="icon"></div>
        <div class="d-flex flex-column justify-content-center">
            <p class="border-bottom text-center">&copy;{{ date('Y') }} Todos os direitos reservados</p>
            <div class="d-flex justify-content-between gap-3">
                <a class="nav-link" href="#">FAQ:Central de ajuda</a>
                <a class="nav-link" href="#">Contato</a>
                <a class="nav-link" href="#">Sobre nós</a>
            </div>
        </div>
    </footer>

    <!-- JS BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS STACK -->
    @stack('scripts')

    <script>
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const arrow = document.getElementById('sidebarArrow');

        // começa fechada
        sidebar.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
        arrow.style.transform = 'rotate(180deg)';

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');

            arrow.style.transform = sidebar.classList.contains('collapsed')
                ? 'rotate(180deg)'
                : 'rotate(0deg)';
        });

    </script>

</body>

</html>