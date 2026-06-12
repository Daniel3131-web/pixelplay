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
        <div class="d-flex flex-column flex-shrink-0 p-3 shadow-lg sidebar collapsed">
            <a href="/"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none sidebar-text">
                <span class="fs-4">Pixelplay</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active d-flex justify-content-center gap-3 align-items-center" aria-current="page">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trophy-fill" viewBox="0 0 16 16">
                            <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5q0 .807-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33 33 0 0 1 2.5.5m.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935m10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935"/>
                        </svg>
                        <span class="sidebar-text">Torneios e Eventos</span>
                    </a>
                    <a href="#" class="nav-link">
                        <svg class="bi pe-none me-2" width="16" height="16">
                            <use xlink:href="#home"></use>
                        </svg>
                        <span class="sidebar-text">Meus Torneios e Eventos</span>
                    </a>
                    <a href="#" class="nav-link">
                        <svg class="bi pe-none me-2" width="16" height="16">
                            <use xlink:href="#home"></use>
                        </svg>
                        <span class="sidebar-text">Meu Time</span>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/daniel3131-web.png" alt="" width="32" height="32"
                        class="rounded-circle me-2">
                    <strong class="sidebar-text">Daniel</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="#">Meu perfil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Minha carteira</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Configurações</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-red" href="#">SAIR</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="d-flex flex-column py-3">
        @yield('content')
    </main>

    <footer class="d-flex justify-content-between text-white p-3">
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