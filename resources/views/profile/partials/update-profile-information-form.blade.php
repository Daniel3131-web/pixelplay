<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="mb-4">
            <x-input-label for="img_input" :value="__('Foto de Perfil')" />

            <label for="img_input" class="w-100" style="cursor: pointer;">
                <div class="upload-box p-4 rounded border border-2 border-dashed d-flex flex-column flex-sm-row align-items-center justify-content-start gap-3 bg-light transition-all"
                    style="min-height: 140px;">

                    <div id="current_image_container" class="flex-shrink-0">
                        <img src="{{ asset($user->img ?? 'assets/profiles/avatar/default.png') }}"
                            class="rounded-circle object-fit-cover shadow-sm border border-2 border-primary"
                            style="width: 72px; height: 72px;" alt="Foto de Perfil Atual">
                    </div>

                    <div id="preview_container" class="text-center text-sm-start flex-grow-1">
                        <div
                            class="d-flex align-items-center justify-content-center justify-content-sm-start text-muted mb-1 gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-camera" viewBox="0 0 16 16">
                                <path
                                    d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z" />
                                <path
                                    d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                            </svg>
                            <span class="fw-semibold text-dark">Alterar imagem</span>
                        </div>
                        <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                            Clique ou arraste e solte uma nova imagem aqui (PNG, JPG ou WEBP).
                        </p>
                    </div>

                    <div id="image_preview_wrapper" class="d-none align-items-center gap-3 w-100">
                        <img id="image_preview" src="#" alt="Nova Imagem"
                            class="rounded-circle shadow-sm border border-2 border-success object-fit-cover flex-shrink-0"
                            style="width: 72px; height: 72px;">
                        <div class="overflow-hidden">
                            <span class="d-block text-success small fw-bold text-uppercase">Nova Imagem Pronta!</span>
                            <span id="file_name" class="text-muted small text-truncate d-block"
                                style="max-width: 220px;">nome-do-arquivo.jpg</span>
                        </div>
                    </div>

                </div>
            </label>

            <input type="file" name="img" id="img_input" class="d-none" accept="image/png, image/jpeg, image/webp">
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imgInput = document.getElementById('img_input');
        const previewContainer = document.getElementById('preview_container');
        const currentImageContainer = document.getElementById('current_image_container');
        const imagePreviewWrapper = document.getElementById('image_preview_wrapper');
        const imagePreview = document.getElementById('image_preview');
        const fileName = document.getElementById('file_name');

        imgInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    fileName.textContent = file.name;

                    // Oculta os elementos iniciais
                    previewContainer.classList.add('d-none');
                    if (currentImageContainer) currentImageContainer.classList.add('d-none');

                    // Exibe o novo preview flexível
                    imagePreviewWrapper.classList.remove('d-none');
                    imagePreviewWrapper.classList.add('d-flex');
                };

                reader.readAsDataURL(file);
            }
        });
    });
</script>