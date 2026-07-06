@extends('layouts.app_main') 
@section('title', 'Pixelplay - Painel do Organizador')

@push('styles')
    <link rel="stylesheet" href="/css/player/torneio.css">
    <style>
        .stat-card-org {
            background-color: #ffffff;
            border-radius: 15px;
            transition: 0.2s ease;
        }

        .stat-card-org:hover {
            transform: translateY(-3px);
        }

        .card-custom {
            border-radius: 15px;
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@section('content')

    <section class="container py-5">

        {{-- Topo do Painel --}}
        <div class="row align-items-center mb-5 g-3">
            <div class="col-md-8 text-center text-md-start">
                <h2 class="fw-bold text-white text-uppercase mb-1"> 
                    <i class="bi bi-speedometer2"></i> Painel do Organizador
                </h2>
                <p class="text-white small mb-0">
                    Gerencie seus campeonatos, valide partidas e controle as inscrições da Pixelplay
                </p>
            </div>
            
            {{-- Ações Rápidas (Botões) --}}
            <div class="col-md-12 gap-3 d-flex flex-wrap align-items-center justify-content-start mt-3">
                <a href="{{ route('org.evento.criar') }}"
                    class="btn btn-outline-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                    <i class="bi bi-plus-lg me-1"></i> Criar Evento
                </a>
                
                <a href="{{ route('org.torneio.criar') }}"
                    class="btn btn-outline-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                    <i class="bi bi-plus-lg me-1"></i> Criar Torneio
                </a>
                
                <a href="{{ route('org.notificacao.criar') }}"
                    class="btn btn-outline-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                    <i class="bi bi-bell-fill me-1"></i> Criar Notificação
                </a>
            </div>
        </div>

        {{-- Cards de Estatísticas --}}
        <div class="row g-4 mb-5">
            {{-- Seção Eventos --}}
            <div class="col-12 mt-2 text-white">
                <h3><i class="bi bi-calendar-event me-2"></i>Eventos</h3>
            </div>
            
            <div class="col-md-6">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Seus Eventos</span>
                        <span class="fw-bold fs-2 text-dark">{{ count($events) }}</span>
                    </div>
                    <div class="action-icon bg-primary-subtle text-primary fs-4">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Inscrições Ativas (Eventos)</span>
                        <span class="fw-bold fs-2 text-dark">{{ $events->sum('current_participants') ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-danger-subtle text-danger fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            {{-- Seção Torneios --}}
            <div class="col-12 mt-4 text-white">
                <h3><i class="bi bi-trophy-fill me-2"></i>Torneios</h3>
            </div>
            
            <div class="col-md-6">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Seus Torneios</span>
                        <span class="fw-bold fs-2 text-dark">{{ count($tournaments) ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-primary-subtle text-primary fs-4">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Inscrições Ativas (Torneios)</span>
                        <span class="fw-bold fs-2 text-dark">{{ $tournaments->sum('current_participants') ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-danger-subtle text-danger fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela: Eventos Recentes --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="card card-custom border-0 shadow-sm bg-light p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-uppercase text-secondary mb-1">Seus Eventos Recentes</h5>
                        <p class="text-muted small mb-0">Acompanhe o andamento dos eventos criados por você</p>
                    </div>

                    @if(isset($events) && count($events) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle bg-white rounded shadow-sm border overflow-hidden mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="ps-4">Evento</th>
                                        <th scope="col" class="text-start">Data de Início</th>
                                        <th scope="col" class="text-start">Inscritos</th>
                                        <th scope="col" class="text-start">Status</th>
                                        <th scope="col" class="text-end pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($event->img ?? 'assets/events/default.png') }}"
                                                        class="me-2 rounded-circle" style="width: 32px; height: 32px;"
                                                        alt="{{ $event->name }}">
                                                    <div>
                                                        <span class="fw-bold d-block text-dark text-uppercase">{{ $event->name }}</span>
                                                        <span class="text-muted small">ID: #{{ $event->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-secondary small fw-medium">
                                                    <i class="bi bi-calendar-event me-1"></i> {{ $event->start_date?->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-dark small fw-bold">
                                                    {{ $event->current_participants ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($event->end_date > now())
                                                    <span class="badge bg-success text-uppercase py-1 px-2" style="font-size: 0.65rem;">Ativo</span>
                                                @else
                                                    <span class="badge bg-danger text-uppercase py-1 px-2" style="font-size: 0.65rem;">Finalizado</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('org.evento.edit', $event->id) }}"
                                                        class="btn btn-sm btn-primary fw-bold text-uppercase px-3">Editar</a>
                                                    <form action="{{ route('org.evento.destroy', $event->id) }}" method="POST"
                                                        onsubmit="return confirm('Tem certeza absoluta que deseja deletar este evento?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold text-uppercase">Deletar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 border bg-white rounded shadow-sm">
                            <i class="bi bi-calendar-x text-muted display-4 d-block mb-3"></i>
                            <h5 class="fw-bold text-secondary mb-2">Nenhum evento criado por você</h5>
                            <p class="text-muted small mb-0 fw-medium">Clique no botão "Criar Evento" acima para lançar sua primeira atividade.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabela: Torneios Recentes --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-custom border-0 shadow-sm bg-light p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-uppercase text-secondary mb-1">Seus Campeonatos Recentes</h5>
                        <p class="text-muted small mb-0">Acompanhe o andamento dos torneios criados por você</p>
                    </div>

                    @if(isset($tournaments) && count($tournaments) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle bg-white rounded shadow-sm border overflow-hidden mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="ps-4">Torneio</th>
                                        <th scope="col" class="text-start">Data de Início</th>
                                        <th scope="col" class="text-start">Times</th>
                                        <th scope="col" class="text-start">Status</th>
                                        <th scope="col" class="text-end pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tournaments as $tournament)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($tournament->img ?? 'assets/tournaments/default.png') }}"
                                                        class="me-2 rounded-circle" style="width: 32px; height: 32px;"
                                                        alt="{{ $tournament->name }}">
                                                    <div>
                                                        <span class="fw-bold d-block text-dark text-uppercase">{{ $tournament->name }}</span>
                                                        <span class="text-muted small">ID: #{{ $tournament->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-secondary small fw-medium">
                                                    <i class="bi bi-calendar-event me-1"></i>{{ $tournament->start_date?->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-dark small fw-bold">
                                                    {{ $tournament->current_participants }} / {{ $tournament->max_participants }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($tournament->end_date > now())
                                                    <span class="badge bg-success text-uppercase py-1 px-2" style="font-size: 0.65rem;">Ativo</span>
                                                @else
                                                    <span class="badge bg-danger text-uppercase py-1 px-2" style="font-size: 0.65rem;">Finalizado</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('org.torneio.bracket', $tournament->id) }}"
                                                        class="btn btn-sm btn-outline-dark fw-bold text-uppercase px-2">Partidas</a>
                                                    <a href="{{ route('org.torneio.edit', $tournament->id) }}"
                                                        class="btn btn-sm btn-primary fw-bold text-uppercase px-3">Editar</a>
                                                    <form action="{{ route('org.torneio.destroy', $tournament->id) }}" method="POST"
                                                        onsubmit="return confirm('Tem certeza absoluta que deseja deletar o torneio?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold text-uppercase">Deletar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 border bg-white rounded shadow-sm">
                            <i class="bi bi-trophy text-muted display-4 d-block mb-3"></i>
                            <h5 class="fw-bold text-secondary mb-2">Nenhum torneio criado por você</h5>
                            <p class="text-muted small mb-0 fw-medium">Clique no botão "Criar Torneio" acima para lançar sua primeira competição.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </section>

@endsection