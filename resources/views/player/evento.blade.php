@extends('layouts.app_main')

@section('title', 'Pixelplay - ' . $event->name)

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .hover-scale { transition: transform 0.2s ease-in-out; }
        .hover-scale:hover { transform: scale(1.02); }
        .scroll-frame::-webkit-scrollbar { width: 8px; }
        .scroll-frame::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 8px; }
        .scroll-frame::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 8px; }
        .scroll-frame::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
@endpush

@section('content')
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    <div class="position-relative">
                        <img src="{{ asset($event->img ?? 'assets/events/default.png') }}" class="card-img-top"
                            alt="{{ $event->name }}" style="height: 350px; object-fit: cover;">
                    </div>

                    <div class="card-body bg-white p-4 p-md-5">

                        <div class="mb-4 pb-3 border-bottom">
                            <h2 class="fw-bolder mb-0 text-dark">{{ $event->name }}</h2>
                        </div>

                        <div class="p-4 bg-light rounded-4 mb-5 border d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div>
                                <h4 class="fw-bold mb-1">Participe do Evento</h4>
                                <span class="text-muted">Taxa de entrada:
                                    <strong class="text-success fs-5">
                                        {{ $event->entrance_fee > 0 ? 'R$ ' . number_format($event->entrance_fee, 2, ',', '.') : 'Gratuito' }}
                                    </strong>
                                </span>
                            </div>

                            <div class="w-100 d-flex flex-column flex-md-row gap-2" style="max-width: 400px;">
                                @if($event->streaming_url)
                                    <a href="{{ $event->streaming_url }}" target="_blank" class="btn btn-outline-danger w-100 py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                                        <i class="bi bi-broadcast"></i> Assistir
                                    </a>
                                @endif
                                
                                @if($event->users->contains(auth()->id()))
                                    <button class="btn btn-secondary w-100 py-3 fw-bold shadow-sm" disabled>
                                        <i class="bi bi-check-circle"></i> Já Inscrito
                                    </button>
                                @else
                                    <a href="{{ route('payment.checkout', [$event->id, 'event']) }}" class="btn btn-primary w-100 py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                                        <i class="bi bi-ticket-perforated"></i> Inscrever-se
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="row text-center justify-content-center align-items-center mb-5 g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border">
                                    <i class="bi bi-calendar-event fs-3 text-primary mb-2"></i>
                                    <span class="d-block text-muted fw-bold small">DATA DE INÍCIO</span>
                                    <span class="fw-bolder text-dark">{{ $event->start_date ?? 'A definir' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border">
                                    <i class="bi bi-geo-alt fs-3 text-info mb-2"></i>
                                    <span class="d-block text-muted fw-bold small">LOCAL</span>
                                    <span class="fw-bolder text-dark">{{ $event->location ?? 'Online' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border">
                                    <i class="bi bi-people-fill fs-3 text-danger mb-2"></i>
                                    <span class="d-block text-muted fw-bold small">CAPACIDADE</span>
                                    <span class="fw-bolder text-dark"> {{$event->current_participants ?? '0' }} / {{$event->max_participants ?? 'Ilimitado' }} vagas</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-light rounded-4 mb-5 border">
                            <h5 class="fw-bold d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-card-text"></i> Sobre o Evento
                            </h5>
                            <div class="bg-light p-4 rounded-3 text-secondary lh-lg">{{ $event->description }}
                            </div>
                        </div>

                        <div class="p-4 g-5 bg-light rounded-4 mb-5 border">
                            <div class="row mb-4">
                                <h5 class="fw-bold d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-joystick"></i> Torneios do Evento
                                </h5>
                            </div>
                            
                            <div class="row p-4 p-md-5 g-4 overflow-y-auto scroll-frame" style="max-height: 400px;">
                                @forelse ($event->tournaments as $tournament)
                                    <div class="col-12 col-md-6" onclick="window.location.href='/torneio/{{ $tournament->id }}'" style="cursor: pointer">
                                        <div class="card tournament-card h-100 rounded-4 overflow-hidden border shadow-sm hover-scale">
                                            <div class="position-relative">
                                                <img src="{{ asset($tournament->img ?? 'assets/tournaments/default.png') }}" class="card-img-top" alt="{{ $tournament->name }}" style="height: 150px; object-fit: cover;">
                                                {{-- <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between align-items-start">
                                                    @if ($tournament->live)
                                                        <span class="badge bg-danger shadow d-flex align-items-center gap-1 px-2 py-1 rounded-pill">
                                                            <i class="bi bi-broadcast"></i> LIVE
                                                        </span>
                                                    @else
                                                        <div></div>
                                                    @endif
                                                    <span class="badge bg-dark shadow px-2 py-1 rounded-pill">{{ $tournament->status }}</span>
                                                </div> --}}
                                            </div>
                                            <div class="card-body bg-white p-3">
                                                <h6 class="fw-bold text-uppercase mb-3 text-dark">{{ $tournament->name }}</h6>
                                                <div class="d-flex justify-content-between text-center pt-2 border-top">
                                                    <div>
                                                        <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem">Vagas</small>
                                                        <span class="fw-bold small text-dark">{{ $tournament->current_participants }}/{{ $tournament->max_participants }}</span>
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
                                    <div class="col-12 text-center py-5 bg-white rounded-4 border">
                                        <div class="mb-3">
                                            <i class="bi bi-controller text-muted display-4"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2">Nenhum torneio cadastrado</h5>
                                        <p class="text-muted mb-0">No momento, não há torneios associados a este evento.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection