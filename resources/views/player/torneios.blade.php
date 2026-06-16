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
                <div class="scroll-frame z-2 row p-4 g-5 justify-content-center overflow-y-auto" style="max-height: 500px; padding-bottom: 1rem;">
                    @foreach ($Tournaments as $Tournament)
                        <!-- CARD -->
                        <div class="col-12 col-md-6" onclick="window.location.href='/torneio/{{ $Tournament->id }}'">
                            <div class="card border-2">
                                <img src="{{ $Tournament->img }}" class="card-img-top" alt="banner do Torneio ou Evento" style="height: 150px; object-fit: cover;">
                                {{-- <div class="card-img-overlay d-flex flex-column justify-content-between p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        @if ($Tournament->live == true)
                                            <span class="badge bg-danger fs-6 shadow-sm opacity-100 d-flex align-items-center justify-content-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                                    <circle cx="8" cy="8" r="8"/>
                                                </svg>
                                                LIVE
                                            </span>
                                        @else
                                            <div></div>
                                        @endif
                                        
                                        @if ($Tournament->status == 'Aberto')
                                            <span class="badge bg-success fs-6 shadow-sm opacity-100">Aberto</span>
                                        @elseif ($Tournament->status == 'Agendado')
                                            <span class="badge bg-info fs-6 shadow-sm opacity-100">Agendado</span>
                                        @elseif ($Tournament->status == 'Em andamento')
                                            <span class="badge bg-warning text-dark fs-6 shadow-sm opacity-100">Em andamento</span>
                                        @elseif ($Tournament->status == 'Finalizado')
                                            <span class="badge bg-danger fs-6 shadow-sm opacity-100">Finalizado</span>
                                        @endif
                                    </div>
                                </div> --}}

                                <div class="card-body d-flex flex-column bg-light rounded-bottom">
                                        <div class="card-title fw-bold text mb-4">
                                            <h5 class="text-uppercase">{{ $Tournament->name }}</h5>
                                            <span class="d-block text-muted fw-bold">ID {{ $Tournament->id}}</span>
                                        </div>
                                        
                                    <div class="row text-center align-items-center mt-auto">
                                        <div class="col border-end">
                                            <span class="d-block text-muted fw-bold">DATA</span>
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <span class="fw-bold">{{ $Tournament->start_date }}</span>
                                                <span class="fw-bold"> - </span>
                                                <span class="fw-bold"> {{ $Tournament->end_date }}</span>
                                            </div>
                                        </div>
                                        <div class="col border-end">
                                            <span class="d-block text-muted fw-bold">VAGAS</span>
                                            <span class="fw-bold">{{ $Tournament->current_participants }} / {{ $Tournament->max_participants }}</span>
                                        </div>
                                        <div class="col">
                                            <span class="d-block text-muted fw-bold">PREMIAÇÃO</span>
                                            <span class="fw-bold text-success">R$ {{ $Tournament->awards }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- FIM DO CARD -->
                    @endforeach
                </div>
            </div>
        </div>
        
    </section>

@endsection