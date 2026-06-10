<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <!-- Css -->
        <link rel="stylesheet" href="/css/main.css">
        <!-- Favicon -->
        <!-- <link rel="shortcut icon" href="/assets/favicon/" type="image/x-icon"> -->
    </head>
    <body>
        
        <header class="d-flex justify-content-start py-3">
            <div class="icon"></div>
        </header>

        @yield('content')

        <footer class="d-flex justify-content-between text-white p-3">
            <div class="icon"></div>
            <div class="d-flex flex-column justify-content-center">
                <p class="border-bottom text-center">&copy;2026 Todos os direitos reservados</p>
                <div class="d-flex justify-content-between gap-3">
                    <a href="#">FAQ:Central de ajuda</a>
                    <a href="#">Contato</a>
                    <a href="#">Sobre nós</a>
                </div>
            </div>
        </footer>
    </body>
</html>
