@extends('layouts.app_main')
@section('title', 'Pixelplay - Relatórios e Estatísticas')

@push('styles')
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

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 260px;
        }
    </style>
@endpush

@section('content')

    <section class="container py-5">

        {{-- Topo do Painel --}}
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-8 text-center text-md-start">
                <h2 class="fw-bold text-white text-uppercase mb-1">
                    <i class="bi bi-graph-up-arrow"></i> Relatórios e Estatísticas
                </h2>
                <p class="text-white small mb-0">
                    Indicadores de performance por jogador, equipe e evento, com exportação de dados
                </p>
            </div>

            <div class="col-md-4 d-flex justify-content-center justify-content-md-end">
                <a href="{{ route('org.reports.export', ['type' => 'all']) }}"
                   class="btn btn-primary btn-lg fs-6 fw-bold text-uppercase shadow-sm py-2 px-4 card-custom">
                    <i class="bi bi-download me-1"></i> Exportar Relatório Geral (CSV)
                </a>
            </div>
        </div>

        {{-- Cards de indicadores --}}
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Total de Eventos</span>
                        <span class="fw-bold fs-2 text-dark">{{ $overview['total_events'] }}</span>
                    </div>
                    <div class="action-icon bg-primary-subtle text-primary fs-4">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Total de Equipes</span>
                        <span class="fw-bold fs-2 text-dark">{{ $overview['total_teams'] }}</span>
                    </div>
                    <div class="action-icon bg-warning-subtle text-warning fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card-org border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <span class="d-block text-muted small fw-bold text-uppercase" style="font-size: 0.75rem;">Total de Jogadores</span>
                        <span class="fw-bold fs-2 text-dark">{{ $overview['total_players'] }}</span>
                    </div>
                    <div class="action-icon bg-success-subtle text-success fs-4">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtro por evento --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom border-0 shadow-sm bg-light p-3">
                    <form method="GET" action="{{ route('org.reports.index') }}" class="d-flex align-items-center gap-3 flex-wrap">
                        <label for="event_id" class="fw-bold text-secondary text-uppercase small mb-0">
                            <i class="bi bi-funnel me-1"></i> Evento:
                        </label>
                        <select name="event_id" id="event_id" onchange="this.form.submit()" class="form-select w-auto">
                            <option value="">Todos os eventos</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @selected($eventId == $event->id)>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="row g-4">

            <div class="col-md-6">
                <div class="card card-custom border-0 shadow-sm bg-light p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-uppercase text-secondary mb-0">
                                Taxa de Abandono:
                                <span class="text-danger">{{ $dropout['rate'] }}%</span>
                            </h5>
                            <button onclick="downloadChart('dropoutChart', 'taxa-abandono-pixelplay.png')"
                                    class="btn btn-sm btn-outline-dark" title="Baixar Gráfico">
                                <i class="bi bi-image"></i>
                            </button>
                        </div>
                        <div class="chart-wrapper bg-white rounded p-2">
                            <canvas id="dropoutChart"></canvas>
                        </div>
                    </div>
                    <a href="{{ route('org.reports.export', ['type' => 'abandonos']) }}"
                       class="btn btn-outline-dark fw-bold text-uppercase mt-3">
                        <i class="bi bi-download me-1"></i> Baixar Dados desta Planilha (CSV)
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-custom border-0 shadow-sm bg-light p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-uppercase text-secondary mb-0">
                                Engajamento:
                                <span class="text-primary">{{ $engagement['rate'] }}%</span> recorrentes
                            </h5>
                            <button onclick="downloadChart('engagementChart', 'engajamento-jogadores.png')"
                                    class="btn btn-sm btn-outline-dark" title="Baixar Gráfico">
                                <i class="bi bi-image"></i>
                            </button>
                        </div>
                        <div class="chart-wrapper bg-white rounded p-2">
                            <canvas id="engagementChart"></canvas>
                        </div>
                    </div>
                    <a href="{{ route('org.reports.export', ['type' => 'players']) }}"
                       class="btn btn-outline-dark fw-bold text-uppercase mt-3">
                        <i class="bi bi-download me-1"></i> Baixar Lista de Jogadores (CSV)
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection

{{--
    Atenção: o layout `layouts.app_main` só tem @stack('styles') no <head>,
    não existe @stack('scripts') no final do <body>. Por isso o Chart.js e o
    script inline vão dentro do mesmo push('styles') — como tudo roda dentro
    de um listener de DOMContentLoaded, funciona normalmente mesmo executando
    a partir do <head>.
--}}
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dados vêm direto do backend, nada de valor fixo/mockado
        const dropoutData = {
            active: @json($dropout['active']),
            dropped: @json($dropout['dropped']),
        };
        const engagementData = {
            singleEvent: @json($engagement['single_event']),
            recurrent: @json($engagement['recurrent']),
        };

        function downloadChart(canvasId, filename) {
            const canvas = document.getElementById(canvasId);
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            document.body.appendChild(link);
            link.click();
            link.remove();
        }

        document.addEventListener('DOMContentLoaded', function () {
            new Chart(document.getElementById('dropoutChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Concluíram', 'Abandono'],
                    datasets: [{
                        data: [dropoutData.active, dropoutData.dropped],
                        backgroundColor: ['#198754', '#dc3545'],
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                },
            });

            new Chart(document.getElementById('engagementChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['1 Evento', '2+ Eventos (Fiéis)'],
                    datasets: [{
                        data: [engagementData.singleEvent, engagementData.recurrent],
                        backgroundColor: ['#0d6efd', '#6f42c1'],
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } },
                },
            });
        });
    </script>
@endpush