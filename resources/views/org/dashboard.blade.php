@extends('layouts.org') @section('title', 'Pixelplay - Painel do Organizador')

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
        
        <div class="row align-items-center mb-5 g-3">
            <div class="col-md-8 text-center text-md-start">
                <h2 class="fw-bold text-white text-uppercase mb-1">Painel do Organizador</h2>
                <p class="text-white small mb-0">Gerencie seus campeonatos, valide partidas e controle as inscrições da Pixelplay</p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <a href="{{ Route('org.torneio.criar') }}" class="btn btn-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                    <i class="bi bi-plus-lg me-1"></i> Novo Torneio
                </a>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-6 col-lg-3">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Seus Torneios</span>
                        <span class="fw-bold fs-2 text-dark">{{ $totalTournaments ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-primary-subtle text-primary fs-4">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-6 col-lg-3">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Inscrições Ativas</span>
                        <span class="fw-bold fs-2 text-dark">{{ $totalSubscribers ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-success-subtle text-success fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Partidas Hoje</span>
                        <span class="fw-bold fs-2 text-dark">{{ $todayMatches ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-info-subtle text-info fs-4">
                        <i class="bi bi-controller"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Resultados Pendentes</span>
                        <span class="fw-bold fs-2 text-danger">{{ $pendingResults ?? 0 }}</span>
                    </div>
                    <div class="action-icon bg-danger-subtle text-danger fs-4">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

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
                                        <th scope="col">Data de Início</th>
                                        <th scope="col">Times</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-end pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tournaments as $tournament)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-secondary-subtle text-secondary rounded d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                        {{ strtoupper(substr($tournament->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold d-block text-dark text-uppercase">{{ $tournament->name }}</span>
                                                        <span class="text-muted small">ID: #{{ $tournament->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-secondary small fw-medium">
                                                    <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($tournament->start_date)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-dark small fw-bold">
                                                    {{ $tournament->teams_count }} / {{ $tournament->max_teams }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($tournament->status === 'inscricoes')
                                                    <span class="badge bg-success text-uppercase py-1 px-2" style="font-size: 0.65rem;">Inscrições Abertas</span>
                                                @elseif($tournament->status === 'andamento')
                                                    <span class="badge bg-warning text-dark text-uppercase py-1 px-2" style="font-size: 0.65rem;">Em Andamento</span>
                                                @else
                                                    <span class="badge bg-secondary text-uppercase py-1 px-2" style="font-size: 0.65rem;">Finalizado</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="#" class="btn btn-sm btn-outline-dark fw-bold text-uppercase px-2" title="Gerenciar Chaves/Partidas">
                                                        <i class="bi bi-diagram-3-fill"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-primary fw-bold text-uppercase px-3">
                                                        Editar
                                                    </a>
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
                            <p class="text-muted small mb-0 fw-medium">Clique no botão "Novo Torneio" acima para lançar sua primeira competição na plataforma.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </section>

@endsection