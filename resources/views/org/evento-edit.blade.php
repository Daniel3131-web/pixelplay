@extends('layouts.app_main')

@section('title', 'Pixelplay - Editar Evento')

@section('content')

    <section class="container-fluid py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-10 col-lg-8">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Editar Evento: {{ $event->name }}
                </h2>

                <form action="{{ route('org.evento.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- SEÇÃO: Banner do Evento --}}
                    <div class="mb-4">
                        <h5 class="text-uppercase text-white mb-3 fw-bold">Banner do Evento</h5>
                        <label for="img_input" class="w-100" style="cursor: pointer">
                            <div class="upload-box p-4 rounded border d-flex flex-column align-items-center justify-content-center" style="min-height: 160px; background-color: rgba(255,255,255,0.02); border-style: dashed !important; border-color: rgba(255,255,255,0.15);">

                                {{-- Container padrão: Oculta se já houver imagem no banco --}}
                                <div id="preview_container" class="w-100 text-center {{ $event->img ? 'd-none' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                        class="bi bi-camera mb-2 text-white" viewBox="0 0 16 16">
                                        <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                                        <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                                    </svg>
                                    <div class="small fw-bold text-white text-uppercase mb-0" style="font-size: 0.75rem;">
                                        Arraste a logo ou o banner do evento aqui para alterar
                                    </div>
                                </div>

                                {{-- Container de Preview: Exibe a imagem atual se existir --}}
                                <div id="image_preview_wrapper" class="align-items-center gap-3 w-100 {{ $event->img ? 'd-flex' : 'd-none' }}">
                                    <img id="image_preview" src="{{ $event->img ? asset($event->img) : '#' }}" alt="Preview" class="rounded border shadow-sm"
                                        style="width: 100px; height: 60px; object-fit: cover;">
                                    <div>
                                        <span class="d-block text-success small fw-bold text-uppercase">Banner Selecionado</span>
                                        <span id="file_name" class="text-white small text-truncate d-block" style="max-width: 250px;">
                                            {{ $event->img ? basename($event->img) : 'nome-do-arquivo.jpg' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="img" id="img_input" class="d-none" accept="image/*">
                        @error('img')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- SEÇÃO: Informações Básicas --}}
                    <div class="mb-4 pt-2">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Informações Básicas</h5>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-8">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Nome do Evento:</label>
                            <input type="text" name="name" class="form-control form-white-input"
                                placeholder="Ex: PixelPlay Gaming Fest 2026" value="{{ old('name', $event->name) }}" required maxlength="255">
                            @error('name')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Capacidade Máxima:</label>
                            <input type="number" name="max_participants" class="form-control form-white-input" 
                                placeholder="Ex: 500" min="1" value="{{ old('max_participants', $event->max_participants) }}" required>
                            @error('max_participants')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- SEÇÃO: Logística e Distribuição --}}
                    <div class="mb-4 pt-2">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Logística e Tipo de Evento</h5>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Modalidade:</label>
                            <select name="type" class="form-select form-white-input" required>
                                <option value="" disabled>Selecione...</option>
                                {{-- <option value="online" {{ old('type', $event->type) == 'online' ? 'selected' : '' }}>100% Online</option> --}}
                                <option value="presencial" {{ old('type', $event->type) == 'presencial' ? 'selected' : '' }}>Presencial</option>
                                {{-- <option value="corporativo" {{ old('type', $event->type) == 'corporativo' ? 'selected' : '' }}>Corporativo / Fechado</option> --}}
                            </select>
                            @error('type')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-8">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Localização ou Link Principal:</label>
                            <input type="text" name="location" class="form-control form-white-input"
                                placeholder="Ex: Campus Universitário, Curitiba PR ou Link do Discord Oficial" value="{{ old('location', $event->location) }}" required>
                            @error('location')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- SEÇÃO: Transmissão e Valores --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-7">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Canal de Transmissão Oficial (Streaming):</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white" style="border-color: rgba(255,255,255,0.15) !important;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-broadcast" viewBox="0 0 16 16">
                                        <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707m2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708m5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708m2.122-2.122a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.314.5.5 0 1 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM6 8a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                                    </svg>
                                </span>
                                <input type="url" name="streaming_url" class="form-control form-white-input" 
                                    placeholder="https://twitch.tv/pixelplay" value="{{ old('streaming_url', $event->streaming_url) }}">
                            </div>
                            @error('streaming_url')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Ingresso Geral do Evento (R$):</label>
                            <input type="number" step="0.01" min="0" name="entrance_fee"
                                class="form-control form-white-input" placeholder="0.00" value="{{ old('entrance_fee', $event->entrance_fee) }}" required>
                            @error('entrance_fee')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- SEÇÃO: Cronograma e Prazos --}}
                    <div class="mb-4 pt-2">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Cronograma e Prazos</h5>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Fim das Inscrições:</label>
                            <input type="date" name="entry_date" class="form-control form-white-input" 
                                value="{{ old('entry_date', $event->entry_date ? date('Y-m-d', strtotime($event->entry_date)) : '') }}" required>
                            @error('entry_date')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Início:</label>
                            <input type="date" name="start_date" class="form-control form-white-input" 
                                value="{{ old('start_date', $event->start_date ? date('Y-m-d', strtotime($event->start_date)) : '') }}" required>
                            @error('start_date')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Término:</label>
                            <input type="date" name="end_date" class="form-control form-white-input" 
                                value="{{ old('end_date', $event->end_date ? date('Y-m-d', strtotime($event->end_date)) : '') }}" required>
                            @error('end_date')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário de Abertura dos Portões/Check-in:</label>
                            <input type="time" name="start_time" class="form-control form-white-input" 
                                value="{{ old('start_time', $event->start_time ? date('H:i', strtotime($event->start_time)) : '') }}" required>
                            @error('start_time')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário Estimado de Encerramento:</label>
                            <input type="time" name="end_time" class="form-control form-white-input" 
                                value="{{ old('end_time', $event->end_time ? date('H:i', strtotime($event->end_time)) : '') }}" required>
                            @error('end_time')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- SEÇÃO: Detalhes do Evento --}}
                    <div class="mb-5">
                        <label class="form-label small fw-bold text-uppercase text-white mb-2">Descrição Geral e Cronograma de Atividades</label>
                        <textarea name="description" class="form-control textarea-custom" rows="6"
                            placeholder="Descreva as atrações principais do evento, palestras, estandes, cronograma geral e diretrizes para os participantes..." required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- BOTÃO SUBMIT --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold text-uppercase card-custom">
                            Salvar Alterações
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>

    <script>
        // Lógica de upload e preview de imagem
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