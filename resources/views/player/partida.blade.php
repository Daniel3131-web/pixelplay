@extends('layouts.app_main')

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
                    <a href="/torneio/{{ $Match->tournament_id }}" class="btn btn-sm btn-outline-light fw-bold text-uppercase px-2">
                        <i class="bi bi-arrow-left"></i> Ir para o Torneio
                    </a>
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="position-relative">
                        <img src="{{ asset($Match->tournament->img ?? 'assets/tournaments/default.png') }}" class="w-100"
                            alt="{{ $Match->name ?? 'Partida' }}" style="height: 250px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between">
                            <span class="badge bg-white text-dark fs-6 shadow px-3 py-2 rounded-pill">
                                Round {{ $Match->round }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body bg-white p-4 p-md-5">

                        @if (auth()->check() && auth()->user()->role == 'organizador')
                            <div class="row align-items-center justify-content-center mb-5 pb-5 border-bottom text-center">
                                <div class="col-md-6">
                                    @if ($Match->team_b_id && $Match->team_a_id && !$Match->winner_id && $Match->match_status !== 'Finalizada' && $Match->match_status !== 'W.O.')                                        
                                        <a href="{{ Route('org.partida.edit', $Match->id) }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold text-uppercase" style="font-size: 0.85rem;"> <i class="bi bi-pencil-square"></i> Editar a Partida</a>
                                    @else
                                        <p class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold text-uppercase">Impossivel editar essa partida</p>
                                    @endif
                                </div>
                                {{-- <div class="col">
                                    <form action="{{ route('org.partida.destroy', $Match->id) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza absoluta que deseja deletar a partida?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold text-uppercase"
                                            style="font-size: 0.85rem;">
                                            <i class="bi bi-trash-fill"></i> Deletar a Partida
                                        </button>
                                    </form>
                                </div> --}}
                            </div>
                        @endif

                        {{-- Placar e Times --}}
                        <div class="row align-items-center mb-5 pb-5 border-bottom text-center">

                            {{-- Time A --}}
                            <div class="col-4">
                                <img src="{{ asset($Match->teamA->img ?? 'assets/teams/default.png') }}"
                                    alt="Logo {{ $Match->teamA->name ?? 'A definir' }}"
                                    class="rounded-circle mb-3 shadow-sm"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <h3 class="fw-bolder text-dark text-truncate">{{ $Match->teamA->name ?? 'A definir' }}</h3>
                                @if($Match->winner_id && $Match->winner_id == $Match->team_a_id)
                                    <span class="badge bg-success mt-2 px-3 py-1 fs-6">
                                        VENCEDOR {{ $Match->match_status === 'W.O.' || $Match->is_wo ? '(W.O.)' : '' }}
                                    </span>
                                @endif
                            </div>

                            {{-- Pontuação --}}
                            <div class="col-4">
                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <h1 class="display-3 fw-bold mb-0 text-dark">{{ $Match->score_a ?? 0 }}</h1>
                                    <h2 class="text-muted mb-0">-</h2>
                                    <h1 class="display-3 fw-bold mb-0 text-dark">{{ $Match->score_b ?? 0 }}</h1>
                                </div>
                            </div>

                            {{-- Time B --}}
                            <div class="col-4">
                                <img src="{{ asset($Match->teamB->img ?? 'assets/teams/default.png') }}"
                                    alt="Logo {{ $Match->teamB->name ?? 'A definir' }}"
                                    class="rounded-circle mb-3 shadow-sm"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <h3 class="fw-bolder text-dark text-truncate">{{ $Match->teamB->name ?? 'A definir' }}</h3>
                                @if($Match->winner_id && $Match->winner_id == $Match->team_b_id)
                                    <span class="badge bg-success mt-2 px-3 py-1 fs-6">
                                        VENCEDOR {{ $Match->match_status === 'W.O.' || $Match->is_wo ? '(W.O.)' : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Seção de Estatísticas --}}
                        <div>
                            <h4 class="fw-bold mb-3"><i class="bi bi-map-fill"></i> Mapa da partida:
                                {{ $Match->map->name ?? 'Desconhecido' }}</h4>
                            <img src="{{ asset($Match->map->img ?? '') }}"
                                class="img-fluid mb-5" style="height: 320px; width: 640px;"
                                alt="{{ $Match->map->name ?? 'Desconhecido' }}">

                            <h4 class="fw-bold mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-bar-chart-fill"></i> Estatísticas da Partida
                            </h4>

                            {{-- Tabela Time A --}}
                            <div class="mb-5">
                                <h5 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                    <img src="{{ asset($Match->teamA->img ?? 'assets/teams/default.png') }}" width="24"
                                        height="24" class="rounded-circle">
                                    {{ $Match->teamA->name ?? 'A definir' }}
                                </h5>

                                <div class="bg-light p-3 rounded-4 border">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover align-middle mb-0">
                                            <thead class="border-bottom">
                                                <tr class="text-muted small">
                                                    <th scope="col" class="ps-3">JOGADOR</th>
                                                    <th scope="col" class="text-center">PERSONAGEM</th>
                                                    <th scope="col" class="text-center">KILLS (K)</th>
                                                    <th scope="col" class="text-center">DEATHS (D)</th>
                                                    <th scope="col" class="text-center">ASSISTS (A)</th>
                                                    <th scope="col" class="text-center pe-3">PONTUAÇÃO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($Match->player_Infos->where('team_id', $Match->team_a_id) as $stats)
                                                    <tr onclick="window.location.href='{{ route('profile.show', $stats->player->id) }}'"
                                                        style="cursor: pointer">
                                                        <td class="ps-3 fw-bold text-dark">
                                                            <img src="{{ asset($stats->player->img ?? '/assets/profiles/avatar/default.png') }}"
                                                                class="rounded-circle me-2" style="height: 32px; width: 32px;"
                                                                alt="{{ $stats->player->name }}">
                                                            {{ $stats->player->name ?? 'Desconhecido' }}
                                                        </td>
                                                        <td class="text-start fw-bold">
                                                            <img src="{{ asset($stats->character->img ?? "Desconhecido") }}" alt="{{ $stats->character->name ?? "Desconhecido" }}">
                                                            {{ $stats->character->name ?? 'Desconhecido'}}
                                                        </td>
                                                        <td class="text-center fw-bold">{{ $stats->kill }}</td>
                                                        <td class="text-center fw-bold text-danger">{{ $stats->death }}</td>
                                                        <td class="text-center fw-bold text-info">{{ $stats->assistance }}</td>
                                                        <td class="text-center pe-3 fw-bold text-success">{{ $stats->score }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-3 fst-italic">Nenhuma
                                                            estatística registrada para este time.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabela Time B --}}
                            <div class="mb-2">
                                <h5 class="fw-bold text-danger mb-3 d-flex align-items-center gap-2">
                                    <img src="{{ asset($Match->teamB->img ?? 'assets/teams/default.png') }}" width="24"
                                        height="24" class="rounded-circle">
                                    {{ $Match->teamB->name ?? 'A definir' }}
                                </h5>

                                <div class="bg-light p-3 rounded-4 border">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover align-middle mb-0">
                                            <thead class="border-bottom">
                                                <tr class="text-muted small">
                                                    <th scope="col" class="ps-3">JOGADOR</th>
                                                    <th scope="col" class="text-center">PERSONAGEM</th>
                                                    <th scope="col" class="text-center">KILLS (K)</th>
                                                    <th scope="col" class="text-center">DEATHS (D)</th>
                                                    <th scope="col" class="text-center">ASSISTS (A)</th>
                                                    <th scope="col" class="text-center pe-3">PONTUAÇÃO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($Match->player_Infos->where('team_id', $Match->team_b_id) as $stats)
                                                    <tr onclick="window.location.href='{{ route('profile.show', $stats->player->id) }}'"
                                                        style="cursor: pointer">
                                                        <td class="ps-3 fw-bold text-dark">
                                                            <img src="{{ asset($stats->player->img ?? '/assets/profiles/avatar/default.png') }}"
                                                                class="rounded-circle me-2" style="height: 32px; width: 32px;"
                                                                alt="{{ $stats->player->name }}">
                                                            {{ $stats->player->name ?? 'Desconhecido' }}
                                                        </td>
                                                        <td class="text-start fw-bold">
                                                            <img src="{{ asset($stats->character->img ?? "Desconhecido") }}" alt="{{ $stats->character->name ?? "Desconhecido" }}">
                                                            {{ $stats->character->name ?? 'Desconhecido'}}
                                                        </td>
                                                        <td class="text-center fw-bold">{{ $stats->kill }}</td>
                                                        <td class="text-center fw-bold text-danger">{{ $stats->death }}</td>
                                                        <td class="text-center fw-bold text-info">{{ $stats->assistance }}</td>
                                                        <td class="text-center pe-3 fw-bold text-success">{{ $stats->score }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted py-3 fst-italic">Nenhuma
                                                            estatística registrada para este time.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection