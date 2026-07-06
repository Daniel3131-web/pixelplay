@extends('layouts.app_main')

@section('title', 'Pixelplay - Torneio')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/player/torneio.css">
    <link rel="stylesheet" href="/css/chaveamento.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11">

                <div class="mb-3 d-flex justify-content-between">
                    <a href="/evento/{{ $Tournament->event_id }}" class="btn btn-sm btn-outline-light fw-bold text-uppercase px-2">
                        <i class="bi bi-arrow-left"></i> Ir para o Evento
                    </a>
                    @if (Auth::user()->role == "organizador")
                        <div>
                            <a href="{{ route('org.torneio.bracket', $Tournament->id) }}"" class="btn btn-sm btn-outline-light fw-bold text-uppercase px-2">Editar Partidas</a>
                            <a href="{{ route('org.torneio.edit', $Tournament->id) }}"" class="btn btn-sm btn-outline-light fw-bold text-uppercase px-2">Editar Torneio</a>
                        </div>
                    @endif
                </div>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="position-relative">
                        <img src="{{ asset($Tournament->img ?? 'assets/tournaments/default.png') }}" class="card-img-top"
                            alt="{{ $Tournament->name }}" style="height: 350px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between">
                            @if($Tournament->end_date > now())
                                <span class="badge bg-success text-uppercase p-2" style="font-size: 1rem;">Ativo</span>
                            @else
                                <span class="badge bg-danger text-uppercase p-2" style="font-size: 1rem;">Finalizado</span>
                            @endif
                        </div>
                    </div>

                    

                    <div class="card-body bg-white p-4 p-md-5">

                        <div class="mb-4 pb-3 border-bottom">
                            <h2 class="fw-bolder mb-0 text-dark">{{ $Tournament->name }}</h2>
                            <span class="text-muted small fw-bold">ID #{{ $Tournament->id }}</span>
                        </div>

                        <div class="p-4 bg-light rounded-4 mb-5 border">
                            <h5 class="fw-bold d-flex align-items-center gap-2 mb-3"><i class="bi bi-card-text"></i> Sobre o
                                Torneio</h5>
                            <div class="bg-light p-4 rounded-3 text-secondary lh-lg">{{ $Tournament->description }}
                            </div>
                        </div>

                        <div class="p-4 bg-light rounded-4 mb-5 border d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div>
                                <h4 class="fw-bold mb-1">Garanta sua vaga</h4>
                                <span class="text-muted">Valor da inscrição:
                                    <strong class="text-success fs-5">R$
                                        {{ number_format($Tournament->entrance_fee, 2, ',', '.') }} (Por pessoa)</strong>
                                </span>
                            </div>
                            

                            @php
                                $Team = Auth()->user()->User_Team;
                            @endphp
                            
                            <div class="w-100" style="max-width: 250px;">
                                @if($Tournament->current_participants >= $Tournament->max_participants || $Tournament->status == 'Finalizado')
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> Vagas Esgotadas!
                                    </button>
                                @elseif($Tournament->entry_date < now() or $Tournament->end_date < now())
                                    <button class="btn btn-secondary w-100 py-3 fw-bold shadow-sm" disabled>
                                        <i class="bi bi-check-circle"></i> Inscrições encerradas!
                                    </button>
                                @elseif (!$Team)
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> Você precisa de um time!
                                    </button>
                                @elseif ($Team->leader_id != Auth()->user()->id)
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-slash-circle"></i> Apenas o líder pode registrar!
                                    </button>
                                @elseif (count($Team->users) < 5)
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-check-circle"></i> Seu time precisa de 5 players!
                                    </button>
                                @elseif ($Tournament->teams->contains('id', $Team->id))
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-check-circle"></i> Time já registrado!
                                    </button>
                                @elseif (!$Team->allMembersHaveTickets($Tournament->event_id))
                                    <button class="btn btn-secondary w-100 py-3 fw-bold disabled" disabled>
                                        <i class="bi bi-exclamation-triangle"></i> Membros sem ingresso do evento!
                                    </button>
                                @else
                                    <a href="{{ Route('payment.checkout', [$Tournament->id, 'tournament']) }}"
                                        class="btn btn-primary w-100 py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2">
                                        <i class="bi bi-ticket-perforated"></i> Comprar Ingresso!
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-light rounded-4 mb-5 border">
                            <h2 class="mb-3">Membros do seu time</h2>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle bg-white rounded shadow-sm border">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="ps-4">Jogador</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($Team && $Team->users)
                                            @foreach($Team->users as $member)
                                                <tr style="cursor: pointer"
                                                    onclick="window.location.href='/profile/{{ $member->id }}'">
                                                    <td class="ps-4 py-3">
                                                        <div class="d-flex align-items-center">
                                                                <img src="{{ asset($member->img ?? '/assets/profiles/avatar/default.png') }}" class="me-3 rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary" style="width: 40px; height: 40px;" alt="Foto de Perfil">
                                                            <div>
                                                                <span class="fw-bold d-block text-dark">
                                                                    {{ $member->name }}
                                                                    {{-- Badges de Identificação --}}
                                                                    @if($member->id === Auth::id())
                                                                        <span class="badge bg-info text-dark ms-1" style="font-size: 0.65rem;">Você</span>
                                                                    @endif
                                                                    @if($member->id == $Team->leader_id)
                                                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Líder</span>
                                                                    @endif
                                                                </span>

                                                                {{-- Feedback Visual do Ingresso --}}
                                                                @if($member->hasTicketForEvent($Tournament->event_id))
                                                                    <span class="text-success small fw-bold">
                                                                        <i class="bi bi-check-circle-fill"></i> Com ingresso
                                                                    </span>
                                                                @else
                                                                    <span class="text-danger small fw-bold">
                                                                        <i class="bi bi-x-circle-fill"></i> Sem ingresso
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="ps-4 py-3 text-muted">Você não possui um time vinculado.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Outras seções como Data, Categoria, Premiação, Sobre e Chaveamento mantidas iguais --}}

                        



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

                                                                    <img src="{{ asset($match->teamA->img) }}" width="32" height="32" class="rounded-circle me-1" alt="Logo">
                                                                    
                                                                    <span class="bracket-slot__name">{{ $match->teamA->acronym }}</span>

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

                                                                    <img src="{{ asset($match->teamB->img) }}" width="32" height="32"
                                                                        class="rounded-circle me-1" alt="Logo">

                                                                    <span class="bracket-slot__name">{{ $match->teamB->acronym }}</span>

                                                                    
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