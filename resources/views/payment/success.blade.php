@extends('layouts.app_main')

@section('title', 'Pixelplay - Comprovante')

@section('content')

    @php
        $isTournament = !empty($order->tournament_id);
        $typeLabel = $isTournament ? 'Torneio' : 'Evento';
        $itemName = $isTournament ? ($order->tournament->name ?? 'Campeonato') : ($order->event->name ?? 'Evento');
    @endphp

    <section class="container-fluid py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-6 col-lg-5">

                <div id="comprovante" class="card bg-dark border-secondary p-4 rounded-4 shadow-lg">
                    <div class="card-body text-center text-white">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h2 class="fw-bold mt-3">Pagamento Confirmado!</h2>
                            @if($order->is_team_payment)
                                <span class="badge bg-primary">Pagamento de Time (5 membros)</span>
                            @endif
                        </div>

                        <div class="border-top border-bottom border-secondary py-3 my-4 text-start">
                            <p class="mb-1"><span class="text-secondary">{{ $typeLabel }}:</span> {{ $itemName }}</p>
                            <p class="mb-1"><span class="text-secondary">Data:</span> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><span class="text-secondary">ID Pedido:</span> #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        {{-- QR Code dinâmico --}}
                        <div class="my-4">
                            <div class="bg-white p-2 d-inline-block rounded">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('admin.validate.order', $order->id)) }}" 
                                     alt="QR Code de Validação" class="img-fluid">
                            </div>
                            <p class="text-white mt-3 small fw-bold">Apresente este código na entrada.</p>
                        </div>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="text-center mt-4 d-print-none">
                    <button onclick="window.print()" class="btn btn-outline-light btn-lg me-2">
                        <i class="bi bi-printer"></i> Salvar/Imprimir
                    </button>
                    
                    <a class="btn btn-primary btn-lg" href="{{ route('player.meuseventos') }}">
                        <i class="bi bi-arrow-left"></i> Voltar ao Painel
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media print {
            body * { visibility: hidden; }
            #comprovante, #comprovante * { visibility: visible; }
            #comprovante { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
@endsection