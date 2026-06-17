@extends('layouts.player')

@section('title', 'Pixelplay - Criar time')

@push('styles')
@endpush

@section('content')

    <section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-8 col-lg-5">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">Criação de Time
                </h2>

                <form action="{{ route('player.time.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Informações do Time</h5>
                    </div>

                    <div class="mb-4">
                        <label for="img_input" class="w-100" style="cursor: pointer">
                            <div class="upload-box p-4 rounded mx-auto mx-md-0 border d-flex flex-column align-items-start justify-content-center"
                                style="min-height: 140px;">

                                <div id="preview_container" class="w-100 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                        class="bi bi-camera mb-2 text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                                        <path
                                            d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                                    </svg>
                                    <div class="small fw-bold text-center text-white text-uppercase mb-0" style="font-size: 0.75rem;">Arraste e
                                        solte sua imagem aqui, ou navegue.</div>
                                </div>

                                <div id="image_preview_wrapper" class="d-none align-items-center gap-3 w-100">
                                    <img id="image_preview" src="#" alt="Preview" class="rounded border shadow-sm"
                                        style="width: 70px; height: 70px; object-fit: cover;">
                                    <div>
                                        <span class="d-block text-success small fw-bold text-uppercase">Imagem
                                            Pronta!</span>
                                        <span id="file_name" class="text-white small text-truncate d-block"
                                            style="max-width: 180px;">nome-do-arquivo.jpg</span>
                                    </div>
                                </div>

                            </div>
                        </label>
                        <input type="file" name="img" id="img_input" class="d-none" accept="image/*" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Nome do Time:</label>
                            <input type="text" name="name" class="form-control form-white-input"
                                placeholder="Ex: Team Pixel" required maxlength="255">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Sigla:</label>
                            <input type="text" name="acronym" class="form-control form-white-input"
                                placeholder="EX: TPXL (máx 5 letras)" maxlength="5" required
                                style="text-transform: uppercase">
                        </div>

                        <div class="col-sm-12">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Jogadores:</label>
                            <input type="text" class="form-control form-white-input" placeholder="5" readonly
                                style="cursor: not-allowed">
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Privado:</label>
                            <select name="privacy" id="privacySelect" class="form-select form-white-input"
                                onchange="togglePassword()" required>
                                <option value="public" selected>Não (Público)</option>
                                <option value="private">Sim (Privado)</option>
                            </select>
                        </div>

                        <div class="col-sm-6" id="passwordWrapper"
                            style="opacity: 0.3; pointer-events: none; transition: 0.3s;">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Senha:</label>
                            <input type="password" name="password" id="passwordInput" class="form-control form-white-input"
                                placeholder="Defina a senha" minlength="8">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-uppercase text-white mb-2">Descrição</label>
                        <textarea name="description" class="form-control textarea-custom" rows="4"
                            placeholder="Conte um pouco sobre as metas e regras do time..." required
                            maxlength="255"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Criar Time</button>
                    </div>

                </form>

            </div>
        </div>
    </section>

    <script>
        function togglePassword() {
            const privacy = document.getElementById('privacySelect').value;
            const wrapper = document.getElementById('passwordWrapper');
            const input = document.getElementById('passwordInput');

            if (privacy === 'private') {
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
                input.setAttribute('required', 'required');
                input.focus();
            } else {
                wrapper.style.opacity = '0.3';
                wrapper.style.pointerEvents = 'none';
                input.removeAttribute('required');
                input.value = '';
            }
        }

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