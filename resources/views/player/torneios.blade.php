@extends('layouts.player')

@section('title', 'Pixelplay - Torneios')

@push('styles')
    <style>
        .tournament-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: #ffffff;
        }

        .tournament-card:hover {
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
                <h2 class="text-white fw-bold mb-1">{{ $search ? "Resultados para: $search" : "Torneios Disponíveis" }}</h2>
                <p class="text-secondary">Encontre sua próxima competição</p>
            </div>
            <form action="{{ route('player.torneios') }}" method="GET" class="w-100">
                <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" name="search" class="form-control border-start-0 py-2.5" placeholder="Buscar por nome do torneio ou categoria..." aria-label="Search" value="{{ $search }}">
                </div>
            </form>
        </div>

        <div class="scroll-frame overflow-y-auto p-5" style="max-height: 800px;">
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                @forelse ($tournaments as $tournament)
                    <div class="col" onclick="window.location.href='/torneio/{{ $tournament->id }}'">
                        <div class="card tournament-card h-100 border-0 rounded-4 overflow-hidden">
                            <div class="position-relative">
                                @if ($tournament->img)
                                    <img src="{{ $tournament->img }}" class="card-img-top" alt="{{ $tournament->name }}"
                                        style="height: 180px; object-fit: cover;">
                                @else
                                    <img src="/assets/tournaments/banner/default.jpg" class="card-img-top"
                                        alt="{{ $tournament->name }}" style="height: 180px; object-fit: cover;">
                                @endif

                                <div class="position-absolute top-0 start-0 p-3">
                                    @if ($tournament->live)
                                        <span class="badge bg-danger mb-2 d-block"><i class="bi bi-broadcast"></i> LIVE</span>
                                    @endif
                                    <span class="badge bg-dark">{{ $tournament->status }}</span>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <h5 class="fw-bold text-uppercase mb-3">{{ $tournament->name }}</h5>

                                <div class="d-flex justify-content-between text-center pt-3 border-top">
                                    <div>
                                        <small class="d-block text-muted text-uppercase">Vagas</small>
                                        <span
                                            class="fw-bold">{{ $tournament->current_participants }}/{{ $tournament->max_participants }}</span>
                                    </div>
                                    <div>
                                        <small class="d-block text-muted text-uppercase">Premiação</small>
                                        <span class="fw-bold text-success">R$
                                            {{ number_format($tournament->awards, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-white">Nenhum torneio encontrado.</h4>
                        <a href="/torneios" class="btn btn-outline-light">Ver todos</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection