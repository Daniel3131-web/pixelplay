@extends('layouts.app_main')

@section('title', 'Pixelplay - Eventos')

@push('styles')
    <style>
        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .scroll-frame {
            scrollbar-width: thin;
        }
    </style>
@endpush

@section('content')
    <section class="container py-5">
        <div class="d-flex flex-column justify-content-between align-items-start mb-4">
            <div>
                <h2 class="text-white fw-bold mb-1">{{ $search ? "Resultados para: $search" : "Eventos Disponíveis" }}</h2>
                <p class="text-secondary">Encontre seu próximo grande evento</p>
            </div>
            <form action="{{ route('player.eventos') }}" method="GET" class="w-100">
                <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" name="search" class="form-control border-start-0 py-2.5"
                        placeholder="Buscar por nome do evento..." aria-label="Search" value="{{ $search }}">
                </div>
            </form>
        </div>

        <section class="container py-5">
            <div class="row">
                <div class="col-12">
                    <div class="scroll-frame z-2 row p-4 g-5 justify-content-center overflow-y-auto" style="max-height: 500px; padding-bottom: 1rem;">
                        @forelse ($events as $event)
                            <div class="col-12 col-md-6"
                                onclick="window.location.href='{{ route('player.evento.show', $event->id) }}'">
                                <div class="card event-card h-100 rounded-4 overflow-hidden">
                                    <div class="position-relative">
                                        <img src="{{ asset($event->img ?? 'assets/events/default.png') }}" class="card-img-top"
                                            alt="{{ $event->name }}" style="height: 180px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 p-3">
                                            @if (isset($event->live) && $event->live)
                                                <span class="badge bg-danger mb-2 d-block"><i class="bi bi-broadcast"></i>
                                                    LIVE</span>
                                            @endif
                                            @if (isset($event->status))
                                                <span class="badge bg-dark">{{ $event->status }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body bg-dark text-white p-4">
                                        <h5 class="fw-bold text-uppercase mb-3">{{ $event->name }}</h5>

                                        <div class="d-flex justify-content-between text-center pt-3 border-top">
                                            <div>
                                                <small class="d-block text-white text-uppercase">Data de Início</small>
                                                <span class="fw-bold text-secondary">{{ $event->start_date?->format('d/m/Y') }}</span>
                                            </div>
                                            <div>
                                                <span class="btn btn-sm btn-primary fw-bold px-3">Ver Detalhes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <h4 class="text-white">Nenhum evento encontrado.</h4>
                                <a href="{{ route('player.eventos') }}" class="btn btn-outline-light">Ver todos</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection