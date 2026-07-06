@extends('layouts.app_main')

@section('title', 'Pixelplay - Torneio')

@push('styles')
    <link rel="stylesheet" href="/css/player/torneio.css">
    <link rel="stylesheet" href="/css/chaveamento.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="position-relative">
                        <img src="{{ asset($Tournament->img ?? 'assets/tournaments/default.png') }}" class="card-img-top"
                            alt="{{ $Tournament->name }}" style="height: 350px; object-fit: cover;">
                        {{-- <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between">
                            @if ($Tournament->live)
                                <span
                                    class="badge bg-danger fs-6 shadow d-flex align-items-center gap-2 px-3 py-2 rounded-pill">
                                    <i class="bi bi-broadcast"></i> AO VIVO
                                </span>
                            @else
                                <div></div>
                            @endif

                            @php
                                $statusColors = [
                                    'Aberto' => 'bg-success',
                                    'Agendado' => 'bg-info text-dark',
                                    'Em andamento' => 'bg-warning text-dark',
                                    'Finalizado' => 'bg-secondary'
                                ];
                                $colorClass = $statusColors[$Tournament->status] ?? 'bg-primary';
                            @endphp
                            <span class="badge {{ $colorClass }} fs-6 shadow px-3 py-2 rounded-pill">
                                {{ $Tournament->status }}
                            </span>
                        </div> --}}
                    </div>

                    <div class="card-body bg-white p-4 p-md-5">
                        <div>
                            <div class="col-md-12 text-center text-md-end">
                                <a href="{{ Route('org.partida.criar', [$Tournament->id]) }}"
                                    class="btn btn-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                                    <i class="bi bi-plus-lg me-1"></i> Criar nova partida
                                </a>
                            </div>
                        </div>
                        <!-- INÍCIO DO BRACKET (CHAVEAMENTO) -->
                        <div>
                            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-diagram-3-fill"></i> Chaveamento
                            </h5>

                            <div class="bracket-wrapper">
                                <div class="bracket-container">

                                    @php
                                        if ($Tournament->max_participants == '4') {
                                            $fases = ['Semi Final', 'Final'];
                                        } elseif ($Tournament->max_participants == '8') {
                                            $fases = ['Quartas de Final', 'Semi Final', 'Final'];
                                        } else {
                                            $fases = ['Oitavas de Final', 'Quartas de Final', 'Semi Final', 'Final'];
                                        }
                                    @endphp

                                    @foreach($fases as $fase)
                                        <div class="bracket-round">
                                            <div class="bracket-round__title">{{ $fase }}</div>

                                            <div class="bracket-round__matches">
                                                @foreach($Tournament->matches->where('stage', $fase)->sortBy('order_of_keys') as $match)

                                                    <div class="bracket-match" onclick="window.location.href=('{{ Route('player.match.show', $match->id) }}')">
                                                        @php
                                                            $isWinnerA = $match->winner_id && $match->winner_id === $match->team_a_id;
                                                            $isLoserA = $match->winner_id && $match->winner_id !== $match->team_a_id;
                                                            $statusClassA = $isWinnerA ? 'bracket-slot--winner' : ($isLoserA ? 'bracket-slot--loser' : '');
                                                        @endphp
                                                        <div class="bracket-slot {{ $statusClassA }}">
                                                            @if($match->teamA)
                                                                <img src="{{ asset($match->teamA->img) }}" width="20" height="20"
                                                                    class="rounded-circle me-1" alt="Logo">
                                                                <span class="bracket-slot__name">{{ $match->teamA->name }}</span>
                                                            @else
                                                                <span class="bracket-slot__name text-muted fst-italic">A definir</span>
                                                            @endif

                                                            @if($isWinnerA)
                                                                <span class="bracket-slot__score text-success fw-bold">W</span>
                                                            @endif
                                                        </div>

                                                        @php
                                                            $isWinnerB = $match->winner_id && $match->winner_id === $match->team_b_id;
                                                            $isLoserB = $match->winner_id && $match->winner_id !== $match->team_b_id;
                                                            $statusClassB = $isWinnerB ? 'bracket-slot--winner' : ($isLoserB ? 'bracket-slot--loser' : '');
                                                        @endphp
                                                        <div class="bracket-slot {{ $statusClassB }}">
                                                            @if($match->teamB)
                                                                <img src="{{ asset($match->teamB->img) }}" width="20" height="20"
                                                                    class="rounded-circle me-1" alt="Logo">
                                                                <span class="bracket-slot__name">{{ $match->teamB->name }}</span>
                                                            @else
                                                                <span class="bracket-slot__name text-muted fst-italic">A definir</span>
                                                            @endif

                                                            @if($isWinnerB)
                                                                <span class="bracket-slot__score text-success fw-bold">W</span>
                                                            @endif
                                                        </div>

                                                    </div>

                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                        </div><!-- fim row bracket -->

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection