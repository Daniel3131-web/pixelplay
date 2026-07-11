@extends('layouts.app_main')

@section('title', 'Pixelplay - Editar Partida')

@section('content')

    <section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-12 col-lg-12">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Configurar partida
                </h2>

                <form action="{{ route('org.partida.update', $Match->id) }}" method="POST" id="matchForm">
                    @csrf
                    @method('PUT')

                    <!-- Inputs ocultos preenchidos via JavaScript ao selecionar o vencedor -->
                    <input type="hidden" name="winner_id" id="winner_id" value="">
                    <input type="hidden" name="is_wo" id="is_wo" value="0">

                    <div class="card-body bg-white p-4 p-md-5 rounded shadow">

                        {{-- Placar e Times --}}
                        <div class="row align-items-center mb-5 pb-5 border-bottom text-center">

                            {{-- Time A --}}
                            <div class="col-md-4">
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

                            {{-- Centro: Placar e Select de Decisão --}}
                            <div class="col-md-4">
                                <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                                    <input type="number" name="score_a"
                                        class="form-control form-control-lg text-center fs-2 fw-bold w-50"
                                        value="{{ $Match->score_a ?? 0 }}" min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                    <h2 class="text-muted mb-0">-</h2>
                                    <input type="number" name="score_b"
                                        class="form-control form-control-lg text-center fs-2 fw-bold w-50"
                                        value="{{ $Match->score_b ?? 0 }}" min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                </div>

                                @if(!$Match->winner_id)
                                    <div class="form-group text-start p-3 bg-light rounded-3 border shadow-sm">
                                        <label for="winner_select"
                                            class="form-label fw-bold text-muted small text-uppercase mb-2">Declarar
                                            Resultado</label>
                                        <select id="winner_select" class="form-select fw-bold cursor-pointer"
                                            onchange="verificarSelecaoVencedor(this)">
                                            <option value="" selected disabled>Escolha o desfecho...</option>
                                            <option value="normal-{{ $Match->team_a_id }}">{{ $Match->teamA->name }} Venceu
                                            </option>
                                            <option value="normal-{{ $Match->team_b_id }}">{{ $Match->teamB->name }} Venceu
                                            </option>
                                            <option value="wo-{{ $Match->team_a_id }}">{{ $Match->teamA->name }} Venceu por W.O.
                                            </option>
                                            <option value="wo-{{ $Match->team_b_id }}">{{ $Match->teamB->name }} Venceu por W.O.
                                            </option>
                                        </select>
                                    </div>
                                @endif
                            </div>

                            {{-- Time B --}}
                            <div class="col-md-4">
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

                        {{-- Seleção de Mapa --}}
                        <div class="row justify-content-center mb-5 pb-5 border-bottom text-center">
                            <div class="col-12 col-md-6">
                                <h5 class="fw-bold text-muted mb-3"><i class="bi bi-map-fill"></i> MAPA DA PARTIDA</h5>
                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <select name="map_id" id="map_id"
                                        class="form-select form-select-lg text-center fw-bold shadow-sm cursor-pointer" {{ $Match->winner_id ? 'disabled' : 'required' }}>
                                        <option value="" disabled {{ empty($Match->map_id) ? 'selected' : '' }}>Selecione o
                                            mapa...</option>
                                        @foreach ($maps as $map)
                                            <option value="{{ $map->id }}" {{ ($Match->map_id == $map->id) ? 'selected' : '' }}>
                                                {{ $map->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Seção de Estatísticas --}}
                        <div>
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
                                                    <th scope="col" class="text-end pe-3">PONTUAÇÃO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($Match->player_Infos->where('team_id', $Match->team_a_id) as $stats)
                                                    <tr>
                                                        <td class="ps-3 fw-bold text-dark align-middle">
                                                            <img src="{{ asset($stats->player->img ?? 'assets/players/default.png') }}"
                                                                class="rounded-circle me-2" style="height: 32px; width: 32px;"
                                                                alt="">
                                                            {{ $stats->player->name ?? 'Desconhecido' }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <select name="stats[{{ $stats->id }}][character]"
                                                                class="form-select form-select-sm text-center fw-bold shadow-sm"
                                                                {{ $Match->winner_id ? 'disabled' : 'required' }}>
                                                                <option value="" disabled {{ empty($stats->character_id) ? 'selected' : '' }}>Selecione...</option>
                                                                @foreach ($characters as $character)
                                                                    <option value="{{ $character->id }}" {{ ($stats->character_id == $character->id) ? 'selected' : '' }}>
                                                                        {{ $character->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][kill]"
                                                                value="{{ $stats->kill }}"
                                                                class="form-control form-control-sm text-center fw-bold" min="0"
                                                                {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][death]"
                                                                value="{{ $stats->death }}"
                                                                class="form-control form-control-sm text-center fw-bold text-danger"
                                                                min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][assistance]"
                                                                value="{{ $stats->assistance }}"
                                                                class="form-control form-control-sm text-center fw-bold text-info"
                                                                min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                        <td class="text-center pe-3 align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][score]"
                                                                value="{{ $stats->score }}"
                                                                class="form-control form-control-sm text-center fw-bold text-success"
                                                                min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
                                                    <th scope="col" class="text-end pe-3">PONTUAÇÃO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($Match->player_Infos->where('team_id', $Match->team_b_id) as $stats)
                                                    <tr>
                                                        <td class="ps-3 fw-bold text-dark align-middle">
                                                            <img src="{{ asset($stats->player->img ?? 'assets/players/default.png') }}"
                                                                class="rounded-circle me-2" style="height: 32px; width: 32px;"
                                                                alt="">
                                                            {{ $stats->player->name ?? 'Desconhecido' }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <select name="stats[{{ $stats->id }}][character]"
                                                                class="form-select form-select-sm text-center fw-bold shadow-sm"
                                                                {{ $Match->winner_id ? 'disabled' : 'required' }}>
                                                                <option value="" disabled {{ empty($stats->character_id) ? 'selected' : '' }}>Selecione...</option>
                                                                @foreach ($characters as $character)
                                                                    <option value="{{ $character->id }}" {{ ($stats->character_id == $character->id) ? 'selected' : '' }}>
                                                                        {{ $character->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][kill]"
                                                                value="{{ $stats->kill }}"
                                                                class="form-control form-control-sm text-center fw-bold" min="0"
                                                                {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][death]"
                                                                value="{{ $stats->death }}"
                                                                class="form-control form-control-sm text-center fw-bold text-danger"
                                                                min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][assistance]"
                                                                value="{{ $stats->assistance }}"
                                                                class="form-control form-control-sm text-center fw-bold text-info"
                                                                min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                        <td class="text-center pe-3 align-middle">
                                                            <input type="number" name="stats[{{ $stats->id }}][score]"
                                                                value="{{ $stats->score }}"
                                                                class="form-control form-control-sm text-center fw-bold text-success"
                                                                min="0" {{ $Match->winner_id ? 'readonly' : 'required' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$Match->winner_id)
                        <div class="text-center mt-5">
                            <button type="button" class="btn btn-secondary btn-lg px-5 fw-bold text-uppercase shadow-sm"
                                onclick="confirmarApenasDados()">
                                Salvar Apenas Estatísticas / Mapa
                            </button>
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </section>

    <!-- JavaScript para controle de submissão e alertas -->
    <script>
        function verificarSelecaoVencedor(selectElement) {
            if (!selectElement.value) return;

            // Transforma o value (ex: "wo-12") em tipo ("wo") e id do time ("12")
            let partes = selectElement.value.split('-');
            let tipo = partes[0];
            let teamId = partes[1];

            // Obtém o texto exato da opção limpo
            let labelOpcao = selectElement.options[selectElement.selectedIndex].text;

            let mensagem = `ATENÇÃO: Você está prestes a finalizar a partida definindo:\n"${labelOpcao}"\n\nEsta ação é IRREVERSÍVEL, salvará o resultado final e avançará o time na árvore do torneio.\n\nTem certeza de que deseja prosseguir?`;

            if (confirm(mensagem)) {
                document.getElementById('winner_id').value = teamId;
                document.getElementById('is_wo').value = (tipo === 'wo') ? '1' : '0';

                // Envia o formulário fechando o resultado
                document.getElementById('matchForm').submit();
            } else {
                // Se cancelar, limpa o select para voltar ao estado inicial
                selectElement.value = "";
            }
        }

        function confirmarApenasDados() {
            // Salva apenas os dados normais (KDA, Placar parcial, Mapa) sem definir vencedor definitivo
            document.getElementById('winner_id').value = "";
            document.getElementById('is_wo').value = "0";
            document.getElementById('matchForm').submit();
        }
    </script>

@endsection