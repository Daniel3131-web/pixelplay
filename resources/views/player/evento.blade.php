@extends('layouts.app_main')

@section('title', 'Pixelplay - ' . $event->name)

@section('content')
<section class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('player.eventos') }}" class="text-decoration-none text-secondary">Eventos</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">{{ $event->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="position-relative mb-4">
                <img src="{{ asset($event->img ?? 'assets/events/default.png') }}" class="img-fluid rounded-4 shadow-lg w-100" style="max-height: 400px; object-fit: cover;">
                <div class="position-absolute bottom-0 start-0 p-4">
                    <h1 class="text-white fw-bold display-4">{{ $event->name }}</h1>
                </div>
            </div>

            <div class="card bg-dark border-0 p-4 rounded-4 shadow-sm text-white mb-5">
                <h4 class="fw-bold mb-3">Sobre o Evento</h4>
                <p class="text-secondary lh-lg">{{ $event->description ?? 'Nenhuma descrição detalhada disponível.' }}</p>
            </div>

            <h4 class="text-white fw-bold mb-4">Torneios do Evento</h4>
            <div class="scroll-frame z-2 row p-2 g-4 overflow-y-auto" style="max-height: 600px;">
                @forelse ($event->tournaments as $tournament)
                    <div class="col-12 col-md-6" onclick="window.location.href='/torneio/{{ $tournament->id }}'" style="cursor: pointer">
                        <div class="card tournament-card h-100 border-0 rounded-4 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ asset($tournament->img ?? 'assets/tournaments/default.png') }}" class="card-img-top" alt="{{ $tournament->name }}" style="height: 150px; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 p-3">
                                    @if ($tournament->live)
                                        <span class="badge bg-danger mb-2 d-block"><i class="bi bi-broadcast"></i> LIVE</span>
                                    @endif
                                    <span class="badge bg-dark">{{ $tournament->status }}</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-uppercase mb-2">{{ $tournament->name }}</h6>
                                <div class="d-flex justify-content-between text-center pt-2 border-top">
                                    <div>
                                        <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem">Vagas</small>
                                        <span class="fw-bold small">{{ $tournament->current_participants }}/{{ $tournament->max_participants }}</span>
                                    </div>
                                    <div>
                                        <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem">Premiação</small>
                                        <span class="fw-bold text-success small">R$ {{ number_format($tournament->awards, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-secondary">Nenhum torneio cadastrado neste evento.</div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-dark border-0 p-4 rounded-4 shadow-lg sticky-top" style="top: 20px;">
                <h5 class="text-white mb-4">Informações</h5>
                <ul class="list-unstyled text-secondary">
                    <li class="mb-3"><i class="bi bi-calendar-event me-2 text-primary"></i> Início: {{ $event->start_date?->format('d/m/Y') }}</li>
                    <li class="mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i> Local: {{ $event->location ?? 'Online' }}</li>
                    <li class="mb-3"><i class="bi bi-people me-2 text-primary"></i> Capacidade: {{ $event->max_capacity ?? 'Ilimitado' }} vagas</li>
                    <li class="mb-3"><i class="bi bi-cash-stack me-2 text-primary"></i> Taxa: {{ $event->entrance_fee > 0 ? 'R$ ' . number_format($event->entrance_fee, 2, ',', '.') : 'Gratuito' }}</li>
                </ul>

                @if($event->streaming_url)
                    <a href="{{ $event->streaming_url }}" target="_blank" class="btn btn-outline-danger w-100 mb-3">
                        <i class="bi bi-broadcast"></i> Assistir Transmissão
                    </a>
                @endif

                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Inscrever-se Agora</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection