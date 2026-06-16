@extends('layouts.player')

@section('title', 'Pixelplay - Torneio')

@push('styles')
    <link rel="stylesheet" href="/css/player/torneio.css">
    <link rel="stylesheet" href="/css/chaveamento.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0">

                    <img src="{{ $Tournament->img }}" class="card-img-top" alt="banner do Torneio ou Evento"
                        style="height: 400px; object-fit: cover;">

                    {{-- <div class="card-img-overlay d-flex flex-column justify-content-between p-3" style="max-height: 400px;">
                        <div class="d-flex justify-content-between align-items-start">

                            @if ($Tournament->live == true)
                                <span
                                    class="badge bg-danger fs-6 shadow-sm opacity-100 d-flex align-items-center justify-content-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                        class="bi bi-circle-fill" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="8" />
                                    </svg>
                                    LIVE
                                </span>
                            @else
                                <div></div>
                            @endif

                            @if ($Tournament->status == 'Aberto')
                                <span class="badge bg-success fs-6 shadow-sm opacity-100">Aberto</span>
                            @elseif ($Tournament->status == 'Agendado')
                                <span class="badge bg-info fs-6 shadow-sm opacity-100">Agendado</span>
                            @elseif ($Tournament->status == 'Em andamento')
                                <span class="badge bg-warning text-dark fs-6 shadow-sm opacity-100">Em andamento</span>
                            @elseif ($Tournament->status == 'Finalizado')
                                <span class="badge bg-danger fs-6 shadow-sm opacity-100">Finalizado</span>
                            @endif

                        </div>

                        <div class="d-flex justify-content-center align-items-center w-100 h-100">
                            <video controls width="90%" height="90%" src="" class="object-fit-contain rounded"></video>
                        </div>
                    </div> --}}

                    <div class="card-body d-flex flex-column bg-light rounded-bottom">

                        <div class="card-title fw-bold text mb-4">
                            <h5>{{ $Tournament->name }}</h5>
                            <span class="d-block text-muted fw-bold">ID {{ $Tournament->id }}</span>
                        </div>

                        <div class="row text-center align-items-center mt-auto">
                            <div class="col border-end">
                                <span class="d-block text-muted fw-bold">DATA</span>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <span class="fw-bold">{{ $Tournament->start_date }}</span>
                                    <span class="fw-bold"> - </span>
                                    <span class="fw-bold">{{ $Tournament->end_date }}</span>
                                </div>
                            </div>
                            <div class="col border-end">
                                <span class="d-block text-muted fw-bold">VAGAS</span>
                                <span class="fw-bold">{{ $Tournament->current_participants }} /
                                    {{ $Tournament->max_participants }}</span>
                            </div>
                            <div class="col">
                                <span class="d-block text-muted fw-bold">PREMIAÇÃO</span>
                                <span class="fw-bold text-success">R$ {{ $Tournament->awards }}</span>
                            </div>
                        </div>

                        <div class="row py-5">
                            <div class="col">

                                <div class="row mb-4">
                                    <span class="d-block text-muted fw-bold">DESCRIÇÃO</span>
                                    <p>{{ $Tournament->description }}</p>
                                </div>

                                <!-- INICIO DO BRACKET -->
                                <div class="row mb-4">
                                    <span class="d-block text-muted fw-bold mb-3">CHAVEAMENTO</span>
                                    <div class="bracket-wrapper">
                                        <div class="bracket-container">

                                            <!-- OITAVAS DE FINAL -->
                                            <div class="bracket-round mx-1">
                                                <div class="bracket-round__title">Oitavas de Final</div>
                                                <div class="bracket-round__matches">
                                                    @foreach($Tournament->matches->sortBy('order_of_keys') as $match)
                                                        @if($match->stage === 'Oitavas de Final')
                                                            <div class="bracket-match">

                                                                <!-- TIME A -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_a_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamA)
                                                                        <img src="{{ asset('storage/' . $match->teamA->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamA->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_a_id)
                                                                        <span class="bracket-slot__score badge bg-success">W</span>
                                                                    @endif
                                                                </div>

                                                                <!-- TIME B -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_b_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamB)
                                                                        <img src="{{ asset('storage/' . $match->teamB->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamB->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_b_id)
                                                                        <span class="bracket-slot__score badge bg-success">W</span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- QUARTAS DE FINAL -->
                                            <div class="bracket-round mx-1">
                                                <div class="bracket-round__title">Quartas de Final</div>
                                                <div class="bracket-round__matches">
                                                    @foreach($Tournament->matches->sortBy('order_of_keys') as $match)
                                                        @if($match->stage === 'Quartas de Final')
                                                            <div class="bracket-match">

                                                                <!-- TIME A -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_a_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamA)
                                                                        <img src="{{ asset('storage/' . $match->teamA->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamA->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_a_id)
                                                                        <span class="bracket-slot__score badge bg-success">W</span>
                                                                    @endif
                                                                </div>

                                                                <!-- TIME B -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_b_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamB)
                                                                        <img src="{{ asset('storage/' . $match->teamB->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamB->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_b_id)
                                                                        <span class="bracket-slot__score badge bg-success">W</span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- SEMIFINAIS -->
                                            <div class="bracket-round mx-1">
                                                <div class="bracket-round__title">Semifinais</div>
                                                <div class="bracket-round__matches">
                                                    @foreach($Tournament->matches->sortBy('order_of_keys') as $match)
                                                        @if($match->stage === 'Semi Final')
                                                            <div class="bracket-match">

                                                                <!-- TIME A -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_a_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamA)
                                                                        <img src="{{ asset('storage/' . $match->teamA->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamA->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_a_id)
                                                                        <span class="bracket-slot__score badge bg-success">W</span>
                                                                    @endif
                                                                </div>

                                                                <!-- TIME B -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_b_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamB)
                                                                        <img src="{{ asset('storage/' . $match->teamB->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamB->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_b_id)
                                                                        <span class="bracket-slot__score badge bg-success">W</span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- GRANDE FINAL -->
                                            <div class="bracket-round mx-1">
                                                <div class="bracket-round__title">Grande Final</div>
                                                <div class="bracket-round__matches">
                                                    @foreach($Tournament->matches->sortBy('order_of_keys') as $match)
                                                        @if($match->stage === 'Final')
                                                            <div class="bracket-match">

                                                                <!-- TIME A -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_a_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamA)
                                                                        <img src="{{ asset('storage/' . $match->teamA->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamA->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_a_id)
                                                                        <span class="bracket-slot__score badge bg-warning text-dark">
                                                                            Campeão</span>
                                                                    @endif
                                                                </div>

                                                                <!-- TIME B -->
                                                                <div
                                                                    class="bracket-slot {{ $match->winner_id && $match->winner_id === $match->team_b_id ? 'bracket-slot--winner' : 'bracket-slot--loser' }}">
                                                                    @if($match->teamB)
                                                                        <img src="{{ asset('storage/' . $match->teamB->img) }}"
                                                                            width="20" height="20" class="rounded-circle me-1"
                                                                            alt="Logo">
                                                                        <span
                                                                            class="bracket-slot__name">{{ $match->teamB->name }}</span>
                                                                    @else
                                                                        <span class="bracket-slot__name text-muted">A definir</span>
                                                                    @endif
                                                                    @if($match->winner_id && $match->winner_id === $match->team_b_id)
                                                                        <span class="bracket-slot__score badge bg-warning text-dark">
                                                                            Campeão</span>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div><!-- fim bracket-container -->
                                    </div><!-- fim bracket-wrapper -->
                                </div><!-- fim row bracket -->

                            </div>
                        </div>

                    </div><!-- fim card-body -->
                </div><!-- fim card -->
            </div>
        </div>
    </section>

@endsection