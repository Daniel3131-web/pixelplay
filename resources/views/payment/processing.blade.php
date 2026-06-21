@extends('layouts.app_main')

@section('content')
<section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4 text-center">
            <h2 class="text-white fw-bold mb-4">{{ $order->method == 'pix' ? 'QR CODE PIX' : 'PROCESSANDO CARTÃO' }}</h2>
            
            <div class="card bg-dark border-secondary p-4 rounded text-center">
                @if($order->method == 'pix')
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PIX_ID_{{ $order->id }}" class="mx-auto mb-3">
                @else
                    <div class="spinner-border text-primary my-4" style="width: 3rem; height: 3rem;"></div>
                    <p class="text-white">Validando dados junto ao gateway...</p>
                @endif
                
                <form action="{{ route('payment.confirm', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">Simular Aprovação</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection