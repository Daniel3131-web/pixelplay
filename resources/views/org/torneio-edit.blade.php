@extends('layouts.app_main')

@section('title', 'Pixelplay - Editar Torneio')

@section('content')

    <section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-10 col-lg-7">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Editar Torneio: {{ $tournament->name }}
                </h2>

                <div
                    class="d-flex justify-content-between align-items-center mb-4 p-3 bg-dark rounded border border-secondary">
                    <a href="{{ route('org.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>

                    <form action="{{ route('org.torneio.destroy', $tournament->id) }}" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja excluir este torneio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i> Excluir Torneio
                        </button>
                    </form>
                </div>

                <form action="{{ route('org.torneio.update', $tournament->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h5 class="text-uppercase text-white mb-3 fw-bold">Banner da Competição</h5>
                        <label for="img_input" class="w-100" style="cursor: pointer">
                            <div class="upload-box p-4 rounded border d-flex flex-column align-items-center justify-content-center"
                                style="min-height: 160px; background-color: rgba(255,255,255,0.02); border-style: dashed !important;">

                                <div id="preview_container"
                                    class="w-100 text-center {{ $tournament->img ? 'd-none' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                        class="bi bi-camera mb-2 text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                                        <path
                                            d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                                    </svg>
                                    <div class="small fw-bold text-white text-uppercase mb-0" style="font-size: 0.75rem;">
                                        Clique para alterar o banner
                                    </div>
                                </div>

                                <div id="image_preview_wrapper"
                                    class="{{ $tournament->img ? 'd-flex' : 'd-none' }} align-items-center gap-3 w-100">
                                    <img id="image_preview" src="{{ $tournament->img ? asset($tournament->img) : '#' }}"
                                        alt="Preview" class="rounded border shadow-sm"
                                        style="width: 100px; height: 60px; object-fit: cover;">
                                    <div>
                                        <span class="d-block text-success small fw-bold text-uppercase">Banner Atual</span>
                                        <span id="file_name" class="text-white small text-truncate d-block"
                                            style="max-width: 250px;">{{ $tournament->img ? 'Imagem definida' : 'Nenhuma' }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="img" id="img_input" class="d-none" accept="image/*">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-8">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Nome do Torneio:</label>
                            <input type="text" name="name" class="form-control form-white-input"
                                value="{{ $tournament->name }}" required maxlength="255">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Jogo / Categoria:</label>
                            <select name="category" class="form-select form-white-input" required>
                                <option value="valorant" {{ $tournament->category == 'valorant' ? 'selected' : '' }}>Valorant
                                </option>
                                <option value="cs2" {{ $tournament->category == 'cs2' ? 'selected' : '' }}>Counter-Strike 2
                                </option>
                                <option value="lol" {{ $tournament->category == 'lol' ? 'selected' : '' }}>League of Legends
                                </option>
                                <option value="mlbb" {{ $tournament->category == 'mlbb' ? 'selected' : '' }}>Mobile Legends
                                </option>
                                <option value="ow2" {{ $tournament->category == 'ow2' ? 'selected' : '' }}>Overwatch 2
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Máximo de
                                Equipes:</label>
                            <select name="max_participants" class="form-select form-white-input" required>
                                <option value="4" {{ $tournament->max_participants == 4 ? 'selected' : '' }}>4 Equipes
                                </option>
                                <option value="8" {{ $tournament->max_participants == 8 ? 'selected' : '' }}>8 Equipes
                                </option>
                                <option value="16" {{ $tournament->max_participants == 16 ? 'selected' : '' }}>16 Equipes
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Status:</label>
                            <select name="status" class="form-select form-white-input" required>
                                <option value="Agendado" {{ $tournament->status == 'Agendado' ? 'selected' : '' }}>Agendado
                                </option>
                                <option value="Aberto" {{ $tournament->status == 'Aberto' ? 'selected' : '' }}>Aberto</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Taxa de Inscrição
                                (R$):</label>
                            <input type="number" step="0.01" name="entrance_fee" class="form-control form-white-input"
                                value="{{ $tournament->entrance_fee }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Premiação Total
                                (R$):</label>
                            <input type="number" step="0.01" name="awards" class="form-control form-white-input"
                                value="{{ $tournament->awards }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Limite de
                                Inscrição:</label>
                            <input type="date" name="entry_date" class="form-control form-white-input"
                                value="{{ $tournament->entry_date }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Início:</label>
                            <input type="date" name="start_date" class="form-control form-white-input"
                                value="{{ $tournament->start_date }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Término:</label>
                            <input type="date" name="end_date" class="form-control form-white-input"
                                value="{{ $tournament->end_date }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário de
                                Início:</label>
                            <input type="time" name="start_time" class="form-control form-white-input"
                                value="{{ $tournament->start_time }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário de
                                Término:</label>
                            <input type="time" name="end_time" class="form-control form-white-input"
                                value="{{ $tournament->end_time }}" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-uppercase text-white mb-2">Descrição e
                            Regulamento</label>
                        <textarea name="description" class="form-control textarea-custom" rows="5"
                            required>{{ $tournament->description }}</textarea>
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                            SALVAR ALTERAÇÕES
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>

    <script>
        document.getElementById('img_input').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('image_preview').src = e.target.result;
                    document.getElementById('file_name').innerText = file.name;
                    document.getElementById('preview_container').classList.add('d-none');
                    document.getElementById('image_preview_wrapper').classList.remove('d-none');
                    document.getElementById('image_preview_wrapper').classList.add('d-flex');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

@endsection