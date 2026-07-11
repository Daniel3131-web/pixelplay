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

        .scroll-frame::-webkit-scrollbar {
            width: var(--sb-size);
        }

        .scroll-frame::-webkit-scrollbar-track {
            background: var(--sb-track-color);
            border-radius: 3px;
        }

        .scroll-frame::-webkit-scrollbar-thumb {
            background: var(--sb-thumb-color);
            border-radius: 3px;
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
                    <div class="scroll-frame z-2 row p-4 g-5 justify-content-center overflow-y-auto"
                        style="max-height: 500px; padding-bottom: 1rem;">
                        @forelse ($events as $event)
                            <div class="col-lg-6 col-md-12" onclick="window.location.href='{{ route('player.evento.show', $event->id) }}'">
                                <div class="card event-card h-100 rounded-4 overflow-hidden">
                                    <div class="position-relative">
                                        <img src="{{ asset($event->img ?? 'assets/events/default.png') }}" class="card-img-top"
                                            alt="{{ $event->name }}" style="height: 180px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between">
                                            @if($event->end_date > now())
                                                <span class="badge bg-success text-uppercase p-2"
                                                    style="font-size: 0.65rem;">Ativo</span>
                                            @else
                                                <span class="badge bg-danger text-uppercase p-2"
                                                    style="font-size: 0.65rem;">Finalizado</span>
                                            @endif
                                        </div>
                                    </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold text-uppercase mb-3">{{ $event->name }}</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="p-3 bg-white rounded-3 h-100 border">
                                                        <i class="bi bi-calendar-event fs-3 text-primary mb-2"></i>
                                                        <span class="d-block text-muted fw-bold small">DATA DE INÍCIO</span>
                                                        <span
                                                            class="fw-bolder text-dark small">{{ $event->start_date?->format('d/m/Y') ?? 'A definir' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 bg-white rounded-3 h-100 border">
                                                        <i class="bi bi-geo-alt fs-3 text-info mb-2"></i>
                                                        <span class="d-block text-muted fw-bold small">LOCAL</span>
                                                        <span
                                                            class="fw-bolder text-dark small">{{ $event->location ?? 'Online' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="p-3 bg-white rounded-3 h-100 border">
                                                        <i class="bi bi-people-fill fs-3 text-danger mb-2"></i>
                                                        <span class="d-block text-muted fw-bold small">CAPACIDADE</span>
                                                        <span class="fw-bolder text-dark small">
                                                            {{$event->current_participants ?? '0' }}
                                                            / {{$event->max_participants ?? 'Ilimitado' }} vagas</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <span class="btn btn-sm btn-primary fw-bold px-3">Ver Detalhes</span>
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