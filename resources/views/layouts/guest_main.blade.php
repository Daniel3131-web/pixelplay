<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}">
    
    <link rel="shortcut icon" href="{{ asset('/assets/imgs/Icon-white.png') }}" type="image/x-icon">
    
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
    </style>

    @stack('styles')
</head>

<body class="sidebar-collapsed">

    <div id="page-loader">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <div class="mt-3 text-white-50 fw-bold small text-uppercase tracking-wide" style="letter-spacing: 1px;">
            Carregando PixelPlay...
        </div>
    </div>
    <header class="d-flex justify-content-start py-3">
        <div class="icon"></div>
    </header>

    <main class="d-flex flex-column py-3">
        @yield('content')
    </main>

    <footer class="d-flex justify-content-between p-3">
        <img class="icon" style="cursor: pointer" onclick="window.location.href='{{ route('landing') }}'"></div>
        <div class="d-flex flex-column justify-content-center">
            <p class="border-bottom text-center">&copy;{{ date('Y') }} Todos os direitos reservados</p>
            <div class="d-flex justify-content-between gap-3">
                <a class="nav-link" href="{{ route('faq') }}">FAQ:Central de ajuda</a>
                <a class="nav-link" href="{{ route('contato') }}">Contato</a>
                <a class="nav-link" href="{{ route('sobre') }}">Sobre nós</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        window.addEventListener('load', function () {
            const loader = document.getElementById('page-loader');
            loader.classList.add('hidden');
            
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        });
    </script>

    @stack('scripts')
</body>

</html>