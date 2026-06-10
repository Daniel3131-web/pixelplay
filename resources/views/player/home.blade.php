
@extends('layouts.player')

@section('title', 'Pixelplay - Home')


@push('styles')
    <link rel="stylesheet" href="/css/player/home.css">
@endpush

@section('content')

    <section class="container py-5">
            <div class="row justify-content-between align-items-center">
                <div class="col-8">
                    <h2 class="text-white mb-4">LISTA DE TORNEIOS</h2>
                </div>
                <div class="col">
                    <div class="row">
                        <select class="w-100 h-100 p-2" name="filtro" id="filtro">
                            <option value="torneios">Torneios</option>
                            <option value="torneios">Eventos</option>
                        </select>
                    </div>
                </div>
            </div>
    </section>

    <section class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="row gy-4 justify-content-center align-items-center">
                    @for ($i=0; $i<=10; $i++)
                        {{-- Card --}}
                        <div class="col-md-8">
                            <div class="row justify-content-center align-items-center">
                                <div class="card w-100">
                                    <img src="/assets/cards/card.png" class="img-thumbnail" alt="Foto do Torneio ou Evento">
                                    <div class="card-img-overlay">
                                        <h5 class="card-title">TORNEIO NOME</h5>
                                    </div>
                                    <div class="card-body">
                                        <div>DATA: 12/07</div>
                                        <div>PARTICIPANTES: 0/10</div>
                                        <div>PREMIAÇÂO: R$10.000,00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
    
@endsection