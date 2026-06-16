@extends('layouts.player')

@section('title', 'Pixelplay - Time')

@push('styles')
    <link rel="stylesheet" href="/css/player/torneio.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- CARD -->
                    <div class="card border-0">
                        <img src="{{ $Team->img }}" class="card-img-top" alt="banner do Time" style="height: 400px; object-fit: cover;">

                        <div class="card-body d-flex flex-column bg-light rounded-bottom">
                                <div class="card-title fw-bold text mb-4">
                                    <h5 class="text-uppercase">{{ $Team->name }}</h5>
                                    <span class="d-block text-muted fw-bold">ID {{ $Team->id}}</span>
                                </div>
                                
                            <div class="row text-center align-items-center mt-auto">
                                <div class="col border-end">
                                    <span class="d-block text-muted fw-bold">VAGAS</span>
                                    @if ($Team->current_participants == $Team->max_participants)
                                        <span class="fw-bold text-danger">{{ $Team->current_participants }} / {{ $Team->max_participants }}</span>
                                    @else
                                        <span class="fw-bold">{{ $Team->current_participants }} / {{ $Team->max_participants }}</span>
                                    @endif
                                </div>
                                <div class="col">
                                    <span class="d-block text-muted fw-bold">PRIVACIDADE</span>
                                    @if ($Team->privacy == 'public')
                                    <span class="fw-bold text-success text-uppercase">{{ $Team->privacy }}</span>
                                    @else
                                    <span class="fw-bold text-danger text-uppercase">{{ $Team->privacy }}</span>
                                    @endif
                                </div>
                                <div class="col">
                                    @if ($Team->privacy == 'public')
                                        <form action="{{ route('player.time.join', $Team) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-lg my-1">Entrar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('player.time.join', $Team) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Digite a Senha">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg my-1">Entrar</button>
                                        </form>
                                    @endif
                                </div>
                        </div>
                    </div>
                <!-- FIM DO CARD -->
            </div>
        </div>
        
    </section>

@endsection