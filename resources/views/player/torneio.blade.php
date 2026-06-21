@extends('layouts.app_main')

@section('title', 'Pixelplay - Torneio')

@push('styles')
    <!-- Ícones do Bootstrap (certifique-se de que já não esteja carregado no layout principal) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                        <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between">
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
                        </div>
                    </div>

                    <div class="card-body bg-white p-4 p-md-5">

                        <div class="mb-4 pb-3 border-bottom">
                            <h2 class="fw-bolder mb-0 text-dark">{{ $Tournament->name }}</h2>
                            <span class="text-muted small fw-bold">ID #{{ $Tournament->id }}</span>
                        </div>

                        <div
                            class="p-4 bg-light rounded-4 mb-5 border d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div>
                                <h4 class="fw-bold mb-1">Garanta sua vaga</h4>
                                <span class="text-muted">Valor da inscrição:
                                    <strong class="text-success fs-5">R$
                                        {{ number_format($Tournament->entrance_fee, 2, ',', '.') }}</strong>
                                </span>
                            </div>

                            <div class="w-100" style="max-width: 250px;">
                                {{-- 1. Verifica se o torneio já acabou ou está lotado --}}
                                @if($Tournament->current_participants >= $Tournament->max_participants || $Tournament->status == 'Finalizado')
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> Vagas Esgotadas.
                                    </button>

                                    {{-- 2. Verifica se o usuário NÃO tem um time --}}
                                @elseif (!Auth()->user()->User_Team)
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> Você tem que possuir um time.
                                    </button>

                                    {{-- 3. Verifica se o usuário NÃO é o líder do time --}}
                                @elseif (Auth()->user()->User_Team->leader_id != Auth()->user()->id)
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> Você tem que ser o líder do time.
                                    </button>

                                    {{-- 4. Verifica se o time NÃO tem 5 membros --}}
                                @elseif (Auth()->user()->User_Team->users->count() != 5)
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> O time precisa de exatamente 5 membros.
                                    </button>

                                    {{-- 5. Verifica se o time JÁ ESTÁ registrado neste torneio --}}
                                @elseif ($Tournament->teams->contains('id', Auth()->user()->User_Team->id))
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-check-circle"></i> Seu time já está no torneio.
                                    </button>

                                    {{-- 6. Se passou por tudo acima, libera o botão de compra --}}
                                @else
                                    <a href="{{ Route('payment.checkout', $Tournament->id) }}"
                                        class="btn btn-primary w-100 py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                                        <i class="bi bi-ticket-perforated"></i> Comprar Ingresso.
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="row text-center justify-content-center align-items-center mb-5 g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border">
                                    <i class="bi bi-calendar-event fs-3 text-primary mb-2"></i>
                                    <span class="d-block text-muted fw-bold small">DATA DO EVENTO</span>
                                    <span class="fw-bolder text-dark">{{ $Tournament->start_date }} a
                                        {{ $Tournament->end_date }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border">
                                    <i class="bi bi-people-fill fs-3 text-info mb-2"></i>
                                    <span class="d-block text-muted fw-bold small">VAGAS PREENCHIDAS</span>
                                    <span
                                        class="fw-bolder text-dark {{ $Tournament->current_participants >= $Tournament->max_participants ? 'text-danger' : '' }}">
                                        {{ $Tournament->current_participants }} / {{ $Tournament->max_participants }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border border-opacity-50">
                                    <i class="bi bi-controller fs-3 text-danger mb-2"></i>
                                    <span class="d-block text-muted fw-bold small">CATEGORIA</span>
                                    <span class="fw-bolder text-dark text-uppercase fs-5">{{ $Tournament->category }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded-3 h-100 border border-success border-opacity-50">
                                    <i class="bi bi-trophy-fill fs-3 text-success mb-2"></i>
                                    <span class="d-block text-success fw-bold small">PREMIAÇÃO TOTAL</span>
                                    <span class="fw-bolder text-success fs-5">R$
                                        {{ number_format($Tournament->awards, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h5 class="fw-bold d-flex align-items-center gap-2 mb-3"><i class="bi bi-card-text"></i> Sobre o
                                Torneio</h5>
                            <div class="bg-light p-4 rounded-3 text-secondary lh-lg">
                                {{ $Tournament->description }}
                            </div>
                        </div>

                        <!-- INÍCIO DO BRACKET (CHAVEAMENTO) -->
                        <div>
                            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-diagram-3-fill"></i> Chaveamento
                            </h5>

                            @if($Tournament->matches->isEmpty())
                                <div class="text-center py-5 px-4 bg-light rounded-4 border">
                                    <div class="mb-3">
                                        <i class="bi bi-diagram-3 text-muted display-4"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Aguardando definição dos confrontos</h5>
                                    <p class="text-muted mb-0 mx-auto" style="max-width: 500px;">
                                        O chaveamento oficial e os confrontos deste torneio ainda serão gerados.
                                    </p>
                                </div>
                            @else
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

                                                        <div class="bracket-match"
                                                            onclick="window.location.href=('{{ Route('player.match.show', $match->id) }}')">

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
                            </div>@endif

                        </div><!-- fim row bracket -->

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection