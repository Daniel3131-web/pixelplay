@extends('layouts.player')

@section('title', 'Pixelplay - Perfil de ' . $user->name)

@push('styles')
    <link rel="stylesheet" href="/css/player/torneio.css">
    <style>
        .player-badge,
        .stat-card-custom {
            background-color: #ffffff;
            border-radius: 12px;
            transition: 0.2s;
        }

        .player-badge:hover,
        .stat-card-custom:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            object-fit: cover;
        }

        .card-custom {
            border-radius: 15px;
        }
    </style>
@endpush

@section('content')

    <section class="container py-5">
        <div class="row g-4">

            <div class="col-lg-4">
                <div class="card card-custom border-0 shadow-sm bg-light overflow-hidden">
                    <div class="w-100 bg-dark"
                        style="height: 100px; background: linear-gradient(135deg, #1e1e2f 0%, #0d6efd 100%);"></div>

                    <div class="card-body p-4 pt-0">
                        <div class="d-flex justify-content-center mb-3" style="margin-top: -55px;">
                            @if ($user->img)
                                <img src="{{ $user->img }}"
                                    class="me-3 rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary"
                                    style="width: 128px; height: 128px;" alt="Foto de Perfil">
                            @else
                                <div class="me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary"
                                    style="width: 128px; height: 128px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark mb-1 text-uppercase">{{ $user->name }}</h3>
                            <span class="badge bg-secondary text-uppercase py-1 px-2 mb-2"
                                style="font-size: 0.7rem;">{{ $user->role ?? 'Jogador' }}</span>
                            <span class="d-block text-muted small fw-bold">ID do Usuário: #{{ $user->id }}</span>
                        </div>

                        <div class="mb-4">
                            <span class="d-block text-muted small fw-bold text-uppercase mb-1">Biografia</span>
                            <p class="text-secondary small mb-0">
                                "{{ $user->bio ?? 'Este jogador prefere manter o mistério e ainda não escreveu uma biografia.' }}"
                            </p>
                        </div>

                        <div class="mb-4">
                            <span class="d-block text-muted small fw-bold text-uppercase mb-1">Cadastro</span>
                            <p class="text-secondary small mb-0">
                                <i class="bi bi-calendar3 me-1"></i> Integrante desde
                                {{ $user->created_at->format('d/m/Y') }}
                            </p>
                        </div>

                        @if($user->id === Auth::id())
                            <div class="pt-2 border-top">
                                <a href="{{ route('profile.edit') }}"
                                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold">
                                    <i class="bi bi-gear-fill"></i> Editar Perfil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">

                <div class="card card-custom border-0 shadow-sm bg-light p-4 mb-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-uppercase text-secondary mb-1">Histórico na Plataforma</h5>
                        <p class="text-muted small mb-0">Desempenho geral e engajamento em competições</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="stat-card-custom p-3 text-center border bg-white shadow-sm">
                                <i class="bi bi-trophy text-warning fs-3 d-block mb-1"></i>
                                <span class="d-block text-muted small fw-bold" style="font-size: 0.75rem;">TORNEIOS</span>
                                <span class="fw-bold fs-4 text-dark">{{ $user->tournaments_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card-custom p-3 text-center border bg-white shadow-sm">
                                <i class="bi bi-controller text-info fs-3 d-block mb-1"></i>
                                <span class="d-block text-muted small fw-bold" style="font-size: 0.75rem;">PARTIDAS</span>
                                <span class="fw-bold fs-4 text-dark">{{ $user->matches_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card-custom p-3 text-center border bg-white shadow-sm">
                                <i class="bi bi-award text-success fs-3 d-block mb-1"></i>
                                <span class="d-block text-muted small fw-bold" style="font-size: 0.75rem;">VITÓRIAS</span>
                                <span class="fw-bold fs-4 text-dark">{{ $user->wins_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-card-custom p-3 text-center border bg-white shadow-sm">
                                <i class="bi bi-fire text-danger fs-3 d-block mb-1"></i>
                                <span class="d-block text-muted small fw-bold" style="font-size: 0.75rem;">EVENTOS</span>
                                <span class="fw-bold fs-4 text-dark">{{ $user->events_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom border-0 shadow-sm bg-light p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-uppercase text-secondary mb-1">Equipe Atual</h5>
                        <p class="text-muted small mb-0">Alineação ativa do jogador na Pixelplay</p>
                    </div>

                    @if($team)
                        <div class="table-responsive">
                            <table
                                class="table table-hover align-middle bg-white rounded shadow-sm border overflow-hidden mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="ps-4">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4 py-3" style="cursor: pointer"
                                            onclick="window.location.href='/time/{{ $team->id }}'">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center fw-bold me-3"
                                                    style="width: 45px; height: 45px; font-size: 1rem;">
                                                    <img src="{{ $team->img ?? '/assets/teams/default-banner.jpg' }}"
                                                        class="w-100" style="object-fit: cover;" alt="Foto do time">
                                                </div>
                                                <div>
                                                    <span
                                                        class="fw-bold d-block text-dark text-uppercase">{{ $team->name }}</span>
                                                    <span class="text-muted small">ID: #{{ $team->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 border bg-white rounded shadow-sm">
                            <i class="bi bi-people text-muted fs-2 d-block mb-2"></i>
                            <p class="text-muted small mb-0 fw-medium">Atualmente este jogador não faz parte de nenhuma equipe.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

@endsection