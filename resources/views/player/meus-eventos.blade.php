@extends('layouts.app_main')

@section('title', 'Meus Eventos - Pixelplay')

@section('content')
<section class="container py-5">
    <h2 class="text-white fw-bold mb-4">Minhas Inscrições</h2>
    
    <div class="row g-4">
        @forelse($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card bg-dark border-0 rounded-4 h-100 overflow-hidden shadow">
                <img src="{{ asset($event->img ?? 'assets/events/default.png') }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                
                <div class="card-body p-4">
                    <h5 class="text-white fw-bold mb-2">{{ $event->name }}</h5>
                    <p class="text-secondary small mb-3">
                        <i class="bi bi-calendar-check me-1 text-primary"></i> 
                        Início: {{ $event->start_date?->format('d/m/Y') }}
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                        <span class="badge bg-success bg-opacity-20 text-success px-3 py-2 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i> Confirmado
                        </span>
                        <a href="{{ route('player.evento.show', $event->id) }}" class="btn btn-sm btn-outline-light">Ver Detalhes</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="text-secondary mb-3"><i class="bi bi-calendar-x display-1"></i></div>
            <h4 class="text-white">Nenhuma inscrição ativa.</h4>
            <p class="text-secondary">Explore nossos eventos e encontre sua próxima competição!</p>
            <a href="{{ route('player.eventos') }}" class="btn btn-primary mt-3">Ver Eventos</a>
        </div>
        @endforelse
    </div>
</section>
@endsection