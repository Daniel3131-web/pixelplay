@extends('layouts.app_main')

@section('title', 'Pixelplay - Enviar Notificação')

@section('content')

    <section class="container-fluid py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-10 col-lg-8">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Enviar Nova Notificação
                </h2>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-4" role="alert">
                        <strong>Sucesso!</strong> {{ session('success') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('org.notificacao.store') }}" method="POST">
                    @csrf

                    {{-- SEÇÃO: Conteúdo da Notificação --}}
                    <div class="mb-4">
                        <h5 class="text-uppercase text-white mb-3 fw-bold">Conteúdo da Mensagem</h5>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase text-white mb-1">Título da Notificação:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-white" style="border-color: rgba(255,255,255,0.15) !important;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                                </svg>
                            </span>
                            <input type="text" name="title" class="form-control form-white-input"
                                placeholder="Ex: Atualização importante no regulamento" value="{{ old('title') }}" required maxlength="255">
                        </div>
                        @error('title')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase text-white mb-2">Corpo da Mensagem (Conteúdo)</label>
                        <textarea name="message" class="form-control textarea-custom" rows="5"
                            placeholder="Escreva aqui os detalhes da notificação..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- SEÇÃO: Destinatário Dinâmico --}}
                    <div class="mb-4 pt-2">
                        <h5 class="text-uppercase text-white mb-1 fw-bold">Definir Destinatários</h5>
                    </div>

                    <div class="row g-3 mb-5">
                        {{-- Tipo de Alvo --}}
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Enviar para:</label>
                            <select id="target_type" name="target_type" class="form-select form-white-input" required>
                                <option value="user" {{ old('target_type') == 'user' ? 'selected' : '' }}>Um Usuário Específico</option>
                                <option value="event" {{ old('target_type') == 'event' ? 'selected' : '' }}>Todos de um Evento</option>
                                <option value="tournament" {{ old('target_type') == 'tournament' ? 'selected' : '' }}>Todos de um Torneio</option>
                            </select>
                        </div>

                        {{-- Input Dinâmico: Usuário --}}
                        <div class="col-sm-6" id="wrapper_user">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Selecione o Usuário:</label>
                            <select name="user_id" id="select_user" class="form-select form-white-input">
                                <option value="" disabled selected>Escolha o usuário...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} (#{{ $user->id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Input Dinâmico: Evento --}}
                        <div class="col-sm-6 d-none" id="wrapper_event">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Selecione o Evento:</label>
                            <select name="event_id" id="select_event" class="form-select form-white-input">
                                <option value="" disabled selected>Escolha o evento...</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Input Dinâmico: Torneio --}}
                        <div class="col-sm-6 d-none" id="wrapper_tournament">
                            <label class="form-label small fw-bold text-uppercase text-white mb-1">Selecione o Torneio:</label>
                            <select name="tournament_id" id="select_tournament" class="form-select form-white-input">
                                <option value="" disabled selected>Escolha o torneio...</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}" {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                        {{ $tournament->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tournament_id')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- BOTÃO SUBMIT --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold text-uppercase card-custom">
                            Disparar Notificação
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>

    {{-- Script para alternar a exibição dos campos --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const targetType = document.getElementById('target_type');
            const wrapperUser = document.getElementById('wrapper_user');
            const wrapperEvent = document.getElementById('wrapper_event');
            const wrapperTournament = document.getElementById('wrapper_tournament');

            const selectUser = document.getElementById('select_user');
            const selectEvent = document.getElementById('select_event');
            const selectTournament = document.getElementById('select_tournament');

            function handleTargetChange() {
                // Esconde todos
                wrapperUser.classList.add('d-none');
                wrapperEvent.classList.add('d-none');
                wrapperTournament.classList.add('d-none');

                // Remove obrigatoriedade temporariamente para evitar travas no HTML5
                selectUser.required = false;
                selectEvent.required = false;
                selectTournament.required = false;

                // Mostra o selecionado
                if (targetType.value === 'user') {
                    wrapperUser.classList.remove('d-none');
                    selectUser.required = true;
                } else if (targetType.value === 'event') {
                    wrapperEvent.classList.remove('d-none');
                    selectEvent.required = true;
                } else if (targetType.value === 'tournament') {
                    wrapperTournament.classList.remove('d-none');
                    selectTournament.required = true;
                }
            }

            targetType.addEventListener('change', handleTargetChange);
            handleTargetChange(); // Executa no load da página para manter dados do old()
        });
    </script>

@endsection