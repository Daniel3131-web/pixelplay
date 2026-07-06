@extends('layouts.guest_main')

@section('title', 'Pixelplay - Landing Page')

@push('styles')
    <link rel="stylesheet" href="/css/landing.css">
@endpush

@section('content')

    <section class="container py-5 position-relative hero-section">

        <div
            class="row mb-5 text-center justify-content-center position-absolute top-50 start-50 translate-middle w-100 z-3">
            <div class="col-12 col-md-6">
                <h1 class="text-white mb-4">PIXEL PLAY</h1>
                <div class="d-flex flex-column align-items-center gap-2 max-width-buttons mx-auto">
                    <a href="{{ route('login') }}" class="btn btn-primary w-75">Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light w-50">Criar conta</a>
                </div>
            </div>
        </div>

        @php

            $images1 = range(1, 4);
            shuffle($images1);
            $image1 = $images1[0];

            $images2 = range(1, 4);
            shuffle($images2);
            $image2 = $images2[1];
            $image3 = $images2[2];
        @endphp

        <div class="row g-3 hero-grid">
            <div class="col-12 col-md-6">
                <div class="img img-hero"
                    style="background-image: url({{ asset('/assets/landing/hero/tall0' . $image1 . '.png') }})"></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="row g-3 h-100 content-stack">
                    <div class="col-12 h-50-custom">
                        <div class="img img-hero"
                            style="background-image: url({{ asset('/assets/landing/hero/landing0' . $image2 . '.png') }})">
                        </div>
                    </div>
                    <div class="col-12 h-50-custom">
                        <div class="img img-hero"
                            style="background-image: url({{ asset('/assets/landing/hero/landing0' . $image3 . '.png') }})">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="container py-5">

        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="text-white mb-4">NA PIXEL PLAY VOCÊ ENCONTRA DIVERSOS EVENTOS E TORNEIOS DOS PRINCIPAIS JOGOS
                    E-SPORTS</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class=" col-10 carousel slide" id="carouselTorneio" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselTorneio" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselTorneio" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselTorneio" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('/assets/landing/events/event01.png') }}" class="d-block w-100 img-carousel-custom"
                            alt="Torneio 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('/assets/landing/events/event02.png') }}" class="d-block w-100 img-carousel-custom"
                            alt="Torneio 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('/assets/landing/events/event03.png') }}" class="d-block w-100 img-carousel-custom"
                            alt="Torneio 3">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselTorneio" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselTorneio" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

            </div>
        </div>

    </section>

    <section class="container py-5">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="text-white mb-4">EVENTOS E TORNEIOS DOS PRINCIPAIS JOGOS E-SPORTS</h2>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-12 col-md-6">
                <div class="row gy-3 flex-column align-items-center">
                    <div class="col-md-8">
                        <div class="img img-game "
                            style="background-image: url('{{ asset('/assets/landing/game01.png') }}')"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="img img-game "
                            style="background-image: url('{{ asset('/assets/landing/game02.png') }}')"></div>
                    </div>

                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row gy-3 flex-column align-items-center">
                    <div class="col-md-8">
                        <div class="img img-game"
                            style="background-image: url('{{ asset('/assets/landing/game03.png') }}')"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="img img-game"
                            style="background-image: url('{{ asset('/assets/landing/game04.png') }}')"></div>
                    </div>
                </div>
            </div>
    </section>

    <section class="container-fluid py-5 bg-white">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">CRIE JÁ SEU PRÓPRIO TIME E PARTICIPE DOS TORNEIOS</h2>
            </div>
        </div>
        <div class="row gy-3 justify-content-center">
            <div class="col-10">
                <div class="row gy-3 justify-content-center">
                    <div class="col-md-3">
                        <div class="img img-character"
                            style="background-image: url('{{ asset('/assets/landing/character01.png') }}')"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="img img-character"
                            style="background-image: url('{{ asset('/assets/landing/character02.png') }}')"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="img img-character"
                            style="background-image: url('{{ asset('/assets/landing/character03.png') }}')"></div>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div class="row justify-content-center gy-3">
                    <div class="col-md-3">
                        <div class="img img-character"
                            style="background-image: url('{{ asset('/assets/landing/character04.png') }}')"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="img img-character"
                            style="background-image: url('{{ asset('/assets/landing/character05.png') }}')"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5 video" style="background-image: url('{{ asset('/assets/landing/video.gif') }}')">

        <div class="row mb-5 text-center justify-content-center mt-5 w-100 z-1 position-relative">
            <div class="col-12 col-md-6 z-2">
                <h1 class="text-white mb-4">PIXEL PLAY</h1>
                <div class="d-flex flex-column align-items-center gap-2 max-width-buttons mx-auto">
                    <a href=" {{ route('login') }} " class="btn btn-primary w-75">Entrar</a>
                    <a href=" {{ route('register') }}" class="btn btn-outline-light w-50">Criar conta</a>
                </div>
            </div>
        </div>

    </section>

@endsection