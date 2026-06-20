@extends('layouts.player')

@section('title', 'Pixelplay - Pagamento Confirmado')

@section('content')

<section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100 m-0">
        <div class="col-md-6 col-lg-5">

            <div id="comprovante" class="card bg-dark border-secondary p-4 rounded shadow-lg">
                <div class="card-body text-center text-white">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        <h2 class="text-uppercase fw-bold mt-3">Pagamento Aprovado!</h2>
                    </div>

                    <div class="border-top border-bottom border-secondary py-3 my-4 text-start">
                        <p class="mb-1"><span class="text-secondary">Torneio:</span> Campeonato Pixelplay</p>
                        <p class="mb-1"><span class="text-secondary">Data:</span> {{ date('d/m/Y H:i') }}</p>
                        <p class="mb-1"><span class="text-secondary">Status:</span> Confirmado</p>
                        <p class="mb-1"><span class="text-secondary">ID Transação:</span> #{{ rand(10000, 99999) }}</p>
                    </div>

                    <p class="small text-white">Sua inscrição foi registrada com sucesso. Apresente este comprovante no dia do evento.</p>
                </div>
            </div>

            <div class="text-center mt-4 d-print-none">
                <button onclick="window.print()" class="btn btn-outline-light btn-lg me-2">
                    <i class="bi bi-printer"></i> Exportar Comprovante (PDF)
                </button>
            </div>

            <div class="text-center mt-4 d-print-none">
                <a class="btn btn-outline-primary btn-lg" href="{{ Route('player.torneio.show', $order->tournament_id) }}">Ir para o torneio</a>
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