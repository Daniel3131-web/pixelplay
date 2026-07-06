@extends('layouts.app_main')

@section('title', 'Pixelplay - Editar Torneio')

@section('content')

    <section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-10 col-lg-7">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Editar Torneio: {{ $tournament->name }}
                </h2>

                <form action="{{ route('org.torneio.update', $tournament->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Exibição de Erros de Validação --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Evento:</label>
                            <select name="event_id" class="form-select form-white-input" required>
                                <option value="" disabled>Selecione...</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id', $tournament->event_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-8">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Nome do Torneio:</label>
                            <input type="text" name="name" class="form-control form-white-input"
                                value="{{ old('name', $tournament->name) }}" placeholder="Ex: Copa Pixelplay Pro" required
                                maxlength="255">
                        </div>

                        <div class="col">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Jogo / Categoria:</label>
                            <select name="category" class="form-select form-white-input" required>
                                <option value="" disabled>Selecione...</option>
                                <option value="valorant" {{ old('category', $tournament->category) == 'valorant' ? 'selected' : '' }}>Valorant</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-12">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Máximo de Equipes:</label>
                            <select name="max_participants" class="form-select form-white-input" required>
                                <option value="4" {{ old('max_participants', $tournament->max_participants) == 4 ? 'selected' : '' }}>4 Equipes</option>
                                <option value="8" {{ old('max_participants', $tournament->max_participants) == 8 ? 'selected' : '' }}>8 Equipes</option>
                                <option value="16" {{ old('max_participants', $tournament->max_participants) == 16 ? 'selected' : '' }}>16 Equipes</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Taxa de Inscrição (R$):</label>
                            <input type="number" step="0.01" name="entrance_fee" class="form-control form-white-input"
                                value="{{ old('entrance_fee', $tournament->entrance_fee) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Premiação Total (R$):</label>
                            <input type="number" step="0.01" name="awards" class="form-control form-white-input"
                                value="{{ old('awards', $tournament->awards) }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Fim das inscrições:</label>
                            <input type="date" name="entry_date" class="form-control form-white-input"
                                value="{{ old('entry_date', $tournament->entry_date ? \Carbon\Carbon::parse($tournament->entry_date)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Início:</label>
                            <input type="date" name="start_date" class="form-control form-white-input"
                                value="{{ old('start_date', $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Término:</label>
                            <input type="date" name="end_date" class="form-control form-white-input"
                                value="{{ old('end_date', $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário de Início:</label>
                            <input type="time" name="start_time" class="form-control form-white-input"
                                value="{{ old('start_time', $tournament->start_time ? \Carbon\Carbon::parse($tournament->start_time)->format('H:i') : '') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário de Término:</label>
                            <input type="time" name="end_time" class="form-control form-white-input"
                                value="{{ old('end_time', $tournament->end_time ? \Carbon\Carbon::parse($tournament->end_time)->format('H:i') : '') }}" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-uppercase text-white mb-2">Descrição e Regulamento</label>
                        <textarea name="description" class="form-control textarea-custom" rows="5" required>{{ old('description', $tournament->description) }}</textarea>
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