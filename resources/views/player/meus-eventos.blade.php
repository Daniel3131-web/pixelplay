@extends('layouts.app_main')

@section('title', 'Minhas Inscrições - Pixelplay')

@section('content')
<section class="container py-5">
    <h2 class="text-white fw-bold mb-4">Minhas Inscrições</h2>

    {{-- Seção de Eventos --}}
    <h4 class="text-white mb-3">
        <i class="bi bi-calendar-event me-2"></i>Eventos Principais
    </h4>
    <div class="d-flex overflow-x-auto pb-4 gap-4 flex-nowrap" style="scrollbar-width: thin;">
        @forelse($eventOrders as $order)
            <div class="flex-shrink-0" style="width: 300px;">
                <div class="card rounded-4 h-100 overflow-hidden shadow border-0">
                    <img src="{{ asset($order->event->img ?? 'assets/events/default.png') }}" 
                         class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">{{ $order->event->name }}</h5>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-calendar-check me-1 text-primary"></i> 
                            {{ $order->event->start_date?->format('d/m/Y') }}
                        </p>
                        <a href="{{ route('payment.success', $order->id) }}" class="btn btn-sm btn-outline-primary w-100 mb-3">Ver Comprovante</a>
                        <a href="{{ route('player.evento.show', $order->event->id) }}" class="btn btn-sm btn-outline-secondary w-100 mb-3">Ver Evento</a>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-white p-4 border rounded-4 bg-dark w-100 text-center">
                Nenhuma inscrição em eventos encontrada.
            </div>
        @endforelse
    </div>

    {{-- Seção de Torneios --}}
    <h4 class="text-white mt-5 mb-3">
        <i class="bi bi-controller me-2"></i>Torneios
    </h4>
    <div class="d-flex overflow-x-auto pb-4 gap-4 flex-nowrap" style="scrollbar-width: thin;">
        @forelse($tournamentOrders as $order)
            <div class="flex-shrink-0" style="width: 300px;">
                <div class="card rounded-4 h-100 overflow-hidden shadow border-0">
                    <img src="{{ asset($order->tournament->img ?? 'assets/events/default.png') }}" 
                         class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">{{ $order->tournament->name }}</h5>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-calendar-check me-1 text-primary"></i> 
                        </p>
                        <a href="{{ route('payment.success', $order->id) }}" class="btn btn-sm btn-outline-primary w-100 mb-3">Ver Comprovante</a>
                        <a href="{{ route('player.torneio.show', $order->tournament->id) }}" class="btn btn-sm btn-outline-secondary w-100 mb-3">Ver Torneio</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-white p-4 border rounded-4 bg-dark w-100 text-center">
                Nenhuma inscrição em torneios encontrada.
            </div>
        @endforelse
    </div>
</section>

<style>
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
</style>
@endsection