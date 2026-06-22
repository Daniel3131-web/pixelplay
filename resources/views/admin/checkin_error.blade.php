@extends('layouts.app_main')

@section('content')
<section class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="card bg-danger text-white shadow-lg p-5 text-center" style="width: 100%; max-width: 500px;">
        <i class="bi bi-x-circle" style="font-size: 5rem;"></i>
        <h1 class="mt-3">Acesso Negado</h1>
        <hr class="border-white">
        <p class="h5">{{ $message ?? 'Ocorreu um erro ao validar este ingresso.' }}</p>
        
        <div class="mt-4">
            <a href="{{ route('admin.checkin.scan') }}" class="btn btn-outline-light btn-lg">Tentar Novamente</a>
        </div>
    </div>
</section>
@endsection