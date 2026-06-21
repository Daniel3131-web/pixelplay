@extends('layouts.app_main')

@section('title', 'Pixelplay - ' . $Team->name)

@push('styles')
    <style>
        .player-badge,
        .member-card {
            background-color: #ffffff;
            border-radius: 12px;
            transition: 0.2s;
        }

        .player-badge:hover,
        .member-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .member-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }

        .card-custom {
            border-radius: 15px;
        }
    </style>
@endpush

@section('content')

    <section class="container py-5">

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-custom border-0 shadow-sm bg-light overflow-hidden">
                    <img src="{{ asset($Team->img) }}" class="w-100" style="height: 160px; object-fit: cover;" alt="{{ $Team->name }}">

                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-1 text-uppercase">{{ $Team->name }}</h3>
                            <span class="text-muted small fw-bold">ID do Time: #{{ $Team->id }}</span>
                        </div>

                        <div class="mb-4">
                            <span class="d-block text-muted small fw-bold text-uppercase mb-1">Descrição</span>
                            <p class="text-secondary small mb-0">{{ $Team->description ?? 'Sem descrição definida.' }}</p>
                        </div>

                        <div class="row text-center bg-white py-3 rounded border mb-4 g-0">
                            <div class="col border-end">
                                <span class="d-block text-muted small fw-bold">MEMBROS</span>
                                @if ($Team->users_count >= $Team->max_participants)
                                    <span class="fw-bold fs-5 text-danger">{{ $Team->users_count }} /
                                        {{ $Team->max_participants }}</span>
                                @else
                                    <span class="fw-bold fs-5 text-dark">{{ $Team->users_count }} /
                                        {{ $Team->max_participants }}</span>
                                @endif
                            </div>
                            <div class="col">
                                <span class="d-block text-muted small fw-bold">PRIVACIDADE</span>
                                @if ($Team->privacy == 'public')
                                    <span class="fw-bold fs-5 text-success text-uppercase"
                                        style="font-size: 0.9rem;">{{ $Team->privacy }}</span>
                                @else
                                    <span class="fw-bold fs-5 text-danger text-uppercase"
                                        style="font-size: 0.9rem;">{{ $Team->privacy }}</span>
                                @endif
                            </div>
                        </div>
                        @php
                            $isMember = $Team->users->contains(Auth::id());
                            $isFull = $Team->users_count >= $Team->max_participants;
                        @endphp

                        <div class="pt-2 border-top">
                            @if($isMember)
                                <form action="{{ route('player.time.leave') }}" method="POST"
                                    onsubmit="return confirm('Tem certeza absoluta que deseja sair deste time?');">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold text-uppercase"
                                        style="font-size: 0.85rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                                            <path fill-rule="evenodd"
                                                d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                                        </svg>
                                        Sair do Time
                                    </button>
                                </form>
                            @elseif($isFull)
                                <button type="button"
                                    class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold text-uppercase"
                                    style="font-size: 0.85rem;" disabled>
                                    <i class="bi bi-dash-circle-fill"></i> Time Lotado
                                </button>
                            @else
                                <form action="{{ route('player.time.join', $Team->id) }}" method="POST">
                                    @csrf
                                    
                                    {{-- VERIFICAÇÃO DE TIME PRIVADO: Adiciona o campo de senha se não for público --}}
                                    @if($Team->privacy == 'private')
                                        <div class="mb-3">
                                            <label for="team_password" class="form-label text-muted small fw-bold text-uppercase">Senha do Time</label>
                                            <input type="password" name="password" id="team_password" class="form-control text-center" placeholder="Digite a senha para entrar" required>
                                            @error('password')
                                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    <button type="submit"
                                        class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-bold text-uppercase"
                                        style="font-size: 0.85rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                            <path fill-rule="evenodd"
                                                d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
                                        </svg>
                                        Entrar no time
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom border-0 shadow-sm bg-light p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold text-uppercase text-secondary mb-1">Integrantes do time</h5>
                        <p class="text-muted small mb-0">Estes são os seus companheiros de equipe atualmente</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle bg-white rounded shadow-sm border overflow-hidden">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="ps-4">Jogador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Team->users as $member)
                                    <tr style="cursor: pointer" onclick="window.location.href='/profile/{{ $member->id }}'">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                @if ($member->img)
                                                    <img src="{{ asset($member->img) }}" class="me-3 rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary" style="width: 40px; height: 40px;" alt="Foto de Perfil">
                                                @else
                                                    <div class="me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2 border-primary"
                                                        style="width: 40px; height: 40px; font-size: 0.85rem;">
                                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="fw-bold d-block text-dark">
                                                        {{ $member->name }}
                                                        @if($member->id === Auth::id())
                                                            <span class="badge bg-info text-dark ms-1" style="font-size: 0.65rem;">Você</span>
                                                        @endif
                                                        @if($member->id == $Team->leader_id)
                                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Líder</span>
                                                        @endif
                                                    </span>
                                                    <span class="text-muted small">Membro</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection