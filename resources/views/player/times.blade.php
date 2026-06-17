@extends('layouts.player')

@section('title', 'Pixelplay - Times')

@push('styles')
    <link rel="stylesheet" href="/css/player/home.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-between align-items-center g-3 mb-4">
            <div class="col-md-6 text-center text-md-start">
                @if ($search)
                    <h2 class="text-white mb-0 fw-bold">Buscando por: <span class="text-primary">{{ $search }}</span></h2>
                @else
                    <h2 class="text-white mb-0 fw-bold">Lista de Times</h2>
                @endif
            </div>

            <div class="col-md-6 text-center text-md-end d-flex justify-content-md-end gap-2">
                @if ($hasTeam)
                    <a href="{{ route('player.time.show', Auth::user()->team_id) }}"
                        class="btn btn-outline-light btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                        <i class="bi bi-columns-gap me-1"></i> Meu time
                    </a>
                @else
                    <a href="{{ route('player.time.create') }}"
                        class="btn btn-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                        <i class="bi bi-plus-lg me-1"></i> Criar time
                    </a>
                @endif
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('player.times') }}" method="GET">
                    <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" name="search" class="form-control border-start-0 py-2.5"
                            placeholder="Buscar por nome do time ou tag..." aria-label="Search" value="{{ $search }}">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="scroll-frame z-2 row p-4 g-5 justify-content-center overflow-y-auto"
                    style="max-height: 500px; padding-bottom: 1rem;">
                    @foreach ($Teams as $Team)
                        <!-- CARD -->
                        <div class="col-12 col-md-6" onclick="window.location.href='/time/{{ $Team->id }}'">
                            <div class="card border-2">
                                <img src="{{ $Team->img }}" class="card-img-top" alt="banner do Time"
                                    style="height: 150px; object-fit: cover;">
                                <div class="card-img-overlay d-flex flex-column justify-content-between p-3"></div>

                                <div class="card-body d-flex flex-column bg-light rounded-bottom">
                                    <div class="card-title fw-bold text mb-4">
                                        <h5 class="text-uppercase">{{ $Team->name }}</h5>
                                        <span class="d-block text-muted fw-bold">ID #{{ $Team->id}}</span>
                                    </div>

                                    <div class="row text-center align-items-center mt-auto">
                                        <div class="col border-end">
                                            <span class="d-block text-muted fw-bold">VAGAS</span>
                                            @if ($Team->current_participants == $Team->max_participants)
                                                <span class="fw-bold text-danger">{{ $Team->current_participants }} /
                                                    {{ $Team->max_participants }}</span>
                                            @else
                                                <span class="fw-bold">{{ $Team->current_participants }} /
                                                    {{ $Team->max_participants }}</span>
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
                    @if(count($Teams) == 0 && $search)
                        <p class="text-white">Não foi possível encontrar nenhum time com {{ $search }}! <a href="/times">Ver
                                todos</a></p>
                    @elseif(count($Teams) == 0)
                        <p class="text-white">Não há times disponíveis</p>
                    @endif
                </div>
            </div>
        </div>

    </section>

@endsection