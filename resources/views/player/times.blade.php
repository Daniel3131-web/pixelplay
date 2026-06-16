@extends('layouts.player')

@section('title', 'Pixelplay - Times')

@push('styles')
    <link rel="stylesheet" href="/css/player/home.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-between align-items-center">
            <div class="col-8">
                <h2 class="text-white mb-4">LISTA DE TIMES</h2>
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
                    @foreach ($Teams as $Team)
                        <!-- CARD -->
                        <div class="col-12 col-md-6" onclick="window.location.href='/time/{{ $Team->id }}'">
                            <div class="card border-2">
                                <img src="{{ $Team->img }}" class="card-img-top" alt="banner do Time" style="height: 150px; object-fit: cover;">
                                <div class="card-img-overlay d-flex flex-column justify-content-between p-3"></div>

                                <div class="card-body d-flex flex-column bg-light rounded-bottom">
                                        <div class="card-title fw-bold text mb-4">
                                            <h5 class="text-uppercase">{{ $Team->name }}</h5>
                                            <span class="d-block text-muted fw-bold">ID {{ $Team->id}}</span>
                                        </div>
                                        
                                    <div class="row text-center align-items-center mt-auto">
                                        <div class="col border-end">
                                            <span class="d-block text-muted fw-bold">VAGAS</span>
                                            @if ($Team->current_participants == $Team->max_participants)
                                                <span class="fw-bold text-danger">{{ $Team->current_participants }} / {{ $Team->max_participants }}</span>
                                            @else
                                                <span class="fw-bold">{{ $Team->current_participants }} / {{ $Team->max_participants }}</span>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <span class="d-block text-muted fw-bold">PRIVACIDADE</span>
                                            @if ($Team->privacy == 'public')
                                            <span class="fw-bold text-success text-uppercase">{{ $Team->privacy }}</span>
                                            @else
                                            <span class="fw-bold text-danger text-uppercase">{{ $Team->privacy }}</span>
                                            @endif
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