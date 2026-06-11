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
                    <select class="w-100 h-100 p-2 rounded" name="filtro" id="filtro">
                        <option value="torneios">Torneios</option>
                        <option value="eventos">Eventos</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row py-3">
            <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </div>
    </section>

    

    <section class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="scroll-frame z-2 row p-4 g-5 border border-2 rounded justify-content-center overflow-y-auto" style="max-height: 500px; padding-bottom: 1rem;">
                    @for ($i=0;$i<=10;$i++)
                        <!-- CARD -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0">
                                <img src="/assets/cards/card.png" class="card-img-top" alt="Foto do Torneio ou Evento" style="height: 200px; object-fit: cover;">
                                <div class="card-img-overlay d-flex flex-column justify-content-between p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span class="badge bg-danger fs-6 shadow-sm opacity-100 d-flex align-items-center justify-content-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                                <circle cx="8" cy="8" r="8"/>
                                            </svg>
                                            LIVE
                                        </span>
                                        <span class="badge bg-success fs-6 shadow-sm opacity-100">Aberto</span>
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column bg-light rounded-bottom">
                                        <div class="card-title fw-bold text mb-4">
                                            <h5>Torneio de Dragon Ball Fighter Z</h5>
                                            <span class="d-block text-muted fw-bold">ID 0000000000</span>
                                        </div>
                                        
                                    <div class="row text-center align-items-center mt-auto">
                                        <div class="col-3 border-end">
                                            <span class="d-block text-muted fw-bold">DATA</span>
                                            <span class="fw-bold">12/07</span>
                                        </div>
                                        <div class="col-4 border-end">
                                            <span class="d-block text-muted fw-bold">VAGAS</span>
                                            <span class="fw-bold">0/10</span>
                                        </div>
                                        <div class="col-5">
                                            <span class="d-block text-muted fw-bold">PREMIAÇÃO</span>
                                            <span class="fw-bold text-success">R$ 10.000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- FIM DO CARD -->
                    @endfor
                </div>
            </div>
        </div>
    </section>

@endsection