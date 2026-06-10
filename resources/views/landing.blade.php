
@extends('layouts.main')

@section('title', 'Pixelplay - Landing Page')


@push('styles')
    <link rel="stylesheet" href="/css/landing.css">
@endpush

@section('content')


    <section class="container py-5 position-relative">

        <div class="row mb-5 text-center justify-content-center position-absolute top-25 mt-5 w-100 z-1">
            <div class="col-12 col-md-6"> 
                <h1 class="text-white mb-4">PIXEL PLAY</h1>
                
                <div class="d-flex flex-column align-items-center gap-2 max-width-buttons mx-auto">
                    <a href="#" class="btn btn-custom w-75">Entrar</a>
                    <a href="#" class="btn btn-outline-light w-50">Criar conta</a>
                </div>
            </div>
        </div>

        <div class="row gy-3">
            <div class="col-12 col-md-6">
                <div class="img" id="game-1">Image 1</div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row gy-3">
                    <div class="col-12">
                        <div class="img" id="game-2">Image 2</div>
                    </div>
                    <div class="col-12">
                        <div class="img" id="game-3">Image 3</div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="container py-5">

        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-white mb-4">NA PIXEL PLAY VOCÊ ENCONTRA DIVERSOS TORNEIOS E EVENTOS DOS PRINCIPAIS JOGOS E-SPORTS</h2>
            </div>
        </div>

        <div class="row">
            <div class=" col-12 carousel slide" id="carouselTorneio" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselTorneio" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselTorneio" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselTorneio" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="img img-carousel d-block">Image 1</div>
                    </div>
                    <div class="carousel-item">
                        <div class="img img-carousel d-block">Image 2</div>
                    </div>
                    <div class="carousel-item">
                        <div class="img img-carousel d-block">Image 3</div>
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
            <div class="col-12">
                <h2 class="text-white mb-4">TORNEIOS E EVENTOS DOS PRINCIPAIS JOGOS E-SPORTS</h2>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-12 col-md-6">
                <div class="row gy-3">
                    <div class="col-12">
                        <div class="img img-game"></div>
                    </div>
                    <div class="col-12">
                        <div class="img img-game"></div>
                    </div>
                    <div class="col-12">
                        <div class="img img-game"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row gy-3">
                    <div class="col-12">
                        <div class="img img-game"></div>
                    </div>
                    <div class="col-12">
                        <div class="img img-game"></div>
                    </div>
                    <div class="col-12">
                        <div class="img img-game"></div>
                    </div>
                </div>
            </div>
    </section>

    <section class="container-fluid py-5 bg-white">
        <div class="row">
            <div class="col-12">
                <h2 class="text-white mb-4">CRIE JÁ SEU PRÓPRIO TIME E PARTICIPE DOS TORNEIOS</h2>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-12">
                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="img img-character"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="img img-character"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="img img-character"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row justify-content-center gy-3">
                    <div class="col-md-4">
                        <div class="img img-character"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="img img-character"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">

        <div class="row mb-5 text-center justify-content-center mt-5 w-100 z-1">
            <div class="col-12 col-md-6"> 
                <h1 class="text-white mb-4">PIXEL PLAY</h1>
                
                <div class="d-flex flex-column align-items-center gap-2 max-width-buttons mx-auto">
                    <a href="#" class="btn btn-custom w-75">Entrar</a>
                    <a href="#" class="btn btn-outline-light w-50">Criar conta</a>
                </div>
            </div>
        </div>

    </section>

@endsection