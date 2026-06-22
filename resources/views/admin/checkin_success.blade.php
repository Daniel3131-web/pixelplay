@extends('layouts.app_main')

@section('content')
<section class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="card bg-success text-white shadow-lg p-5 text-center" style="width: 100%; max-width: 500px;">
        <i class="bi bi-check-circle" style="font-size: 5rem;"></i>
        <h1 class="mt-3">Acesso Permitido!</h1>
        <hr class="border-white">
        <p class="h4">{{ $user->name }}</p>
        <p class="text-white-50">Check-in realizado com sucesso.</p>
        
        <div class="mt-4">
            <a href="{{ route('admin.checkin.scan') }}" class="btn btn-outline-light btn-lg">Validar Próximo</a>
        </div>
    </div>
</section>
@endsection