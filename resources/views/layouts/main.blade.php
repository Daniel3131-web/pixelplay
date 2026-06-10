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

<body>

    <header class="d-flex justify-content-start py-3">
        <div class="icon"></div>
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
</body>

</html>