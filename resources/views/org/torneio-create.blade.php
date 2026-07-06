@extends('layouts.app_main')

@section('title', 'Pixelplay - Criar Torneio')

@push('styles')
@endpush

@section('content')

    <section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-10 col-lg-7">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Criar Novo Torneio
                </h2>

                <form action="{{ route('org.torneio.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <h5 class="text-uppercase text-white mb-3 fw-bold">Banner da Competição</h5>
                        <label for="img_input" class="w-100" style="cursor: pointer">
                            <div class="upload-box p-4 rounded border d-flex flex-column align-items-center justify-content-center"
                                style="min-height: 160px; background-color: rgba(255,255,255,0.02); border-style: dashed !important;">

                                <div id="preview_container" class="w-100 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                        class="bi bi-camera mb-2 text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                                        <path
                                            d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                                    </svg>
                                    <div class="small fw-bold text-white text-uppercase mb-0" style="font-size: 0.75rem;">
                                        Arraste a logo ou o banner do torneio aqui
                                    </div>
                                </div>

                                <div id="image_preview_wrapper" class="d-none align-items-center gap-3 w-100">
                                    <img id="image_preview" src="#" alt="Preview" class="rounded border shadow-sm"
                                        style="width: 100px; height: 60px; object-fit: cover;">
                                    <div>
                                        <span class="d-block text-success small fw-bold text-uppercase">Banner
                                            Carregado!</span>
                                        <span id="file_name" class="text-white small text-truncate d-block"
                                            style="max-width: 250px;">nome-do-arquivo.jpg</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="img" id="img_input" class="d-none" accept="image/*">
                    </div>

                    <div class="mb-4 pt-2">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Informações Básicas</h5>
                    </div>

                    


                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                        <label class="form-label small fw-bold text-uppercase text-white mb-1">Evento:</label>
                        <select name="event_id" class="form-select form-white-input" required>
                            <option value="" disabled selected>Selecione...</option>
                            @foreach ($events as $event)
                                <option value="{{$event->id}}">{{$event->name}}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="col-sm-8">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Nome do Torneio:</label>
                            <input type="text" name="name" class="form-control form-white-input"
                                placeholder="Ex: Copa Pixelplay Pro" required maxlength="255">
                        </div>

                        <div class="col">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Jogo / Categoria:</label>
                            <select name="category" class="form-select form-white-input" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="valorant">Valorant</option>
                                {{-- <option value="cs2">Counter-Strike 2</option>
                                <option value="lol">League of Legends</option>
                                <option value="mlbb">Mobile Legends</option>
                                <option value="ow2">Overwatch 2</option> --}}
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-12">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Máximo de
                                Equipes:</label>
                            <select name="max_participants" class="form-select form-white-input" required>
                                <option value="4" selected>4 Equipes</option>
                                <option value="8">8 Equipes</option>
                                <option value="16">16 Equipes</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Taxa de Inscrição
                                (R$):</label>
                            <input type="number" step="0.01" min="0" name="entrance_fee"
                                class="form-control form-white-input" placeholder="0.00 (Deixe 0 para Grátis)" value="0.00"
                                required>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Premiação Total
                                (R$):</label>
                            <input type="number" step="0.01" min="0" name="awards" class="form-control form-white-input"
                                placeholder="Ex: 500.00" required maxlength="8">
                        </div>
                    </div>

                    <div class="mb-4 pt-2">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Cronograma e Prazos</h5>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Fim das
                                Inscrição:</label>
                            <input type="date" name="entry_date" class="form-control form-white-input" required>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Início:</label>
                            <input type="date" name="start_date" class="form-control form-white-input" required>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Data de Término:</label>
                            <input type="date" name="end_date" class="form-control form-white-input" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário de Início das
                                Partidas:</label>
                            <input type="time" name="start_time" class="form-control form-white-input" required>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Horário Estimado de
                                Término:</label>
                            <input type="time" name="end_time" class="form-control form-white-input" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-uppercase text-white mb-2">Descrição e
                            Regulamento</label>
                        <textarea name="description" class="form-control textarea-custom" rows="5"
                            placeholder="Regras específicas, formato de chaves (MD1, MD3), canais de comunicação (Discord), etc..."
                            required></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold text-uppercase card-custom">
                            Publicar Torneio
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>

    <script>
        // Lógica de upload e preview de imagem idêntica à de criação de times
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