@extends('layouts.player')

@section('title', 'Pixelplay - Detalhes da Partida')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/player/partida.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                
                <div class="mb-3">
                    <a href="/torneio/{{ $Match->tournament_id }}" class="text-decoration-none text-white fw-bold">
                        <i class="bi bi-arrow-left"></i> Voltar para o Torneio
                    </a>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="position-relative">
                        <img src="{{ asset($Match->tournament->img ?? 'assets/tournaments/default.png') }}" class="w-100" alt="{{ $Match->name }}" style="height: 250px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between">
                            @if ($Match->live)
                                <span class="badge bg-danger fs-6 shadow d-flex align-items-center gap-2 px-3 py-2 rounded-pill">
                                    <i class="bi bi-broadcast"></i> AO VIVO
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6 shadow px-3 py-2 rounded-pill">
                                    FINALIZADA
                                </span>
                            @endif
                            
                            <span class="badge bg-info text-dark fs-6 shadow px-3 py-2 rounded-pill">
                                {{ $Match->stage }} </span>
                        </div>
                    </div>

                    <div class="card-body bg-white p-4 p-md-5">

                        <div class="row align-items-center mb-5 pb-5 border-bottom text-center">
                            
                            <div class="col-4">
                                <img src="{{ asset( $Match->teamA->img) }}" alt="Logo {{ $Match->teamA->name }}" class="rounded-circle mb-3 shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <h3 class="fw-bolder text-dark text-truncate">{{ $Match->teamA->name }}</h3>
                                @if($Match->winner_id == $Match->team_a_id)
                                    <span class="badge bg-success mt-2 px-3 py-1">VENCEDOR</span>
                                @endif
                            </div>

                            <div class="col-4">
                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <h1 class="display-3 fw-bold mb-0 text-dark">{{ $Match->score_a ?? 0 }}</h1>
                                    <h2 class="text-muted mb-0">-</h2>
                                    <h1 class="display-3 fw-bold mb-0 text-dark">{{ $Match->score_b ?? 0 }}</h1>
                                </div>
                            </div>

                            <div class="col-4">
                                <img src="{{ asset($Match->teamB->img) }}" alt="Logo {{ $Match->teamB->name }}" class="rounded-circle mb-3 shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <h3 class="fw-bolder text-dark text-truncate">{{ $Match->teamB->name }}</h3>
                                @if($Match->winner_id == $Match->team_b_id)
                                    <span class="badge bg-success mt-2 px-3 py-1">VENCEDOR</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="fw-bold mb-4 d-flex align-items-center gap-2"><i class="bi bi-bar-chart-fill"></i> Estatísticas da Partida</h4>

                            <div class="mb-5">
                                <h5 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                    <img src="{{asset($Match->teamA->img) }}" width="24" height="24" class="rounded-circle"> 
                                    {{ $Match->teamA->name }}
                                </h5>
                                
                                <div class="bg-light p-3 rounded-4 border">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover align-middle mb-0">
                                            <thead class="border-bottom">
                                                <tr class="text-muted small">
                                                    <th scope="col" class="ps-3">JOGADOR</th>
                                                    <th scope="col" class="text-center">KILLS (K)</th>
                                                    <th scope="col" class="text-center">DEATHS (D)</th>
                                                    <th scope="col" class="text-center">ASSISTS (A)</th>
                                                    <th scope="col" class="text-end pe-3">PONTUAÇÃO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($Match->player_Infos->where('team_id', $Match->team_a_id) as $stats)
                                                <tr onclick="window.location.href=('{{ Route('profile.show', $stats->player->id) }}')" style="cursor: pointer">
                                                    <td class="ps-3 fw-bold text-dark">
                                                        <img src="{{ $stats->player->img }}" class="rounded-circle" style="height: 32px; width: 32px;" alt="{{ $stats->player->name }}">
                                                        {{ $stats->player->name ?? 'Desconhecido' }}
                                                    </td>
                                                    <td class="text-center fw-bold">{{ $stats->kill }}</td>
                                                    <td class="text-center fw-bold text-danger">{{ $stats->death }}</td>
                                                    <td class="text-center fw-bold text-info">{{ $stats->assistance }}</td>
                                                    <td class="text-end pe-3 fw-bold text-success">{{ $stats->score }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <h5 class="fw-bold text-danger mb-3 d-flex align-items-center gap-2">
                                    <img src="{{ asset($Match->teamB->img) }}" width="24" height="24" class="rounded-circle"> 
                                    {{ $Match->teamB->name }}
                                </h5>
                                
                                <div class="bg-light p-3 rounded-4 border">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover align-middle mb-0">
                                            <thead class="border-bottom">
                                                <tr class="text-muted small">
                                                    <th scope="col" class="ps-3">JOGADOR</th>
                                                    <th scope="col" class="text-center">KILLS (K)</th>
                                                    <th scope="col" class="text-center">DEATHS (D)</th>
                                                    <th scope="col" class="text-center">ASSISTS (A)</th>
                                                    <th scope="col" class="text-end pe-3">PONTUAÇÃO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($Match->player_Infos->where('team_id', $Match->team_b_id) as $stats)
                                                <tr onclick="window.location.href=('{{ Route('profile.show', $stats->player->id) }}')" style="cursor: pointer">
                                                    <td class="ps-3 fw-bold text-dark">
                                                        <img src="{{ $stats->player->img }}" class="rounded-circle" style="height: 32px; width: 32px;" alt="{{ $stats->player->name }}">
                                                        {{ $stats->player->name ?? 'Desconhecido' }}
                                                    </td>
                                                    <td class="text-center fw-bold">{{ $stats->kill }}</td>
                                                    <td class="text-center fw-bold text-danger">{{ $stats->death }}</td>
                                                    <td class="text-center fw-bold text-info">{{ $stats->assistance }}</td>
                                                    <td class="text-end pe-3 fw-bold text-success">{{ $stats->score }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div></div>
                </div>
            </div>
        </div>
    </section>

@endsection