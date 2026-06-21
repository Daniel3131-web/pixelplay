@extends('layouts.app_main')

@section('title', 'Pixelplay - Criar Partida')

@section('content')

    <section class="container-fluid bg-dark-layout py-5 min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100 m-0">
            <div class="col-md-8 col-lg-6">

                <h2 class="text-center text-uppercase text-white fw-bold mb-5" style="letter-spacing: 2px;">
                    Configurar Nova Partida
                </h2>

                <form action="{{ route('org.partida.store', $tournament->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    
                    <div class="mb-4">
                        <h5 class="text-uppercase text-white mb-3 fw-bold">Informações da Partida</h5>
                        
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-uppercase text-white">Time A:</label>
                                <select name="team_a_id" class="form-select form-white-input" required>
                                    <option value="" disabled selected>Selecione o time...</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-uppercase text-white">Time B:</label>
                                <select name="team_b_id" class="form-select form-white-input" required>
                                    <option value="" disabled selected>Selecione o time...</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white">Fase:</label>
                            <select name="stage" class="form-select form-white-input" required>
                                <option value="Oitavas de Final">Oitavas de Final</option>
                                <option value="Quartas de Final">Quartas de Final</option>
                                <option value="Semi Final">Semi Final</option>
                                <option value="Final">Final</option>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label small fw-bold text-uppercase text-white">Ordem da Chave:</label>
                            <input type="text" name="order_of_keys" class="form-control form-white-input" placeholder="Ex: 1, A1, etc" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase text-white">Status Inicial:</label>
                        <select name="match_status" class="form-select form-white-input" required>
                            <option value="Agendada" selected>Agendada</option>
                            <option value="Em Andamento">Em Andamento</option>
                        </select>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold text-uppercase">
                            Criar Partida
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>

@endsection