@extends('layouts.player')

@section('title', 'Pixelplay - Torneio')

@push('styles')
    <link rel="stylesheet" href="/css/player/torneio.css">
    <link rel="stylesheet" href="/css/chaveamento.css">
@endpush

@section('content')

    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0">
                    <img src="/assets/cards/card.png" class="card-img-top" alt="Foto do Torneio ou Evento"
                        style="height: 400px; object-fit: cover;">
                    <div class="card-img-overlay d-flex flex-column justify-content-between p-3" style="max-height: 400px;">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge bg-danger fs-6 shadow-sm opacity-100 d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16"> <circle cx="8" cy="8" r="8" /></svg>
                                AO VIVO
                            </span>
                            <span class="badge bg-danger fs-6 shadow-sm opacity-100">Fechado</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-100 h-100">
                            <video controls width="90%" height="90%" src="" class="object-fit-contain rounded"></video>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column bg-light rounded-bottom">
                        <div class="card-title fw-bold text mb-4">
                            <h5>{{ $Tournament->name }}</h5>
                            <span class="d-block text-muted fw-bold">ID {{ $Tournament->id }}</span>
                        </div>

                        <div class="row text-center align-items-center mt-auto">
                            <div class="col-3 border-end">
                                <span class="d-block text-muted fw-bold">DATA</span>
                                <span class="fw-bold">12/07 - 25/07</span>
                            </div>
                            <div class="col-4 border-end">
                                <span class="d-block text-muted fw-bold">VAGAS</span>
                                <span class="fw-bold text-danger"> {{ $Tournament->participants }} / {{ $Tournament->participants }}</span>
                            </div>
                            <div class="col-5">
                                <span class="d-block text-muted fw-bold">PREMIAÇÃO</span>
                                <span class="fw-bold text-success">R$ 10.000</span>
                            </div>
                        </div>

                        <div class="row py-5">
                            <div class="col">
                                <div class="row mb-4">
                                    <span class="d-block text-muted fw-bold">DESCRIÇÃO</span>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit vitae placeat culpa
                                        dolores, eligendi consequatur! Eius debitis cum rem corrupti ut. Non doloremque
                                        eveniet blanditiis reprehenderit exercitationem cumque, unde neque.</p>
                                </div>

                                <!-- INICIO DO BRACKET -->
                                <div class="row mb-4">
                                    <span class="d-block text-muted fw-bold mb-3">CHAVEAMENTO</span>
                                    <div class="bracket-wrapper">
                                        <div class="bracket-container">

                                            <!-- OITAVAS DE FINAL -->
                                            <div class="bracket-round">
                                                <div class="bracket-round__title">Oitavas de Final</div>
                                                <div class="bracket-round__matches">

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">1</span>
                                                            <span class="bracket-slot__name">SonGokuBR</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">16</span>
                                                            <span class="bracket-slot__name">Raditz99</span>
                                                            <span class="bracket-slot__score badge bg-secondary">0</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">8</span>
                                                            <span class="bracket-slot__name">VegetaKing</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">9</span>
                                                            <span class="bracket-slot__name">NappaBR</span>
                                                            <span class="bracket-slot__score badge bg-secondary">1</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">5</span>
                                                            <span class="bracket-slot__name">PiccoloGod</span>
                                                            <span class="bracket-slot__score badge bg-secondary">2</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">12</span>
                                                            <span class="bracket-slot__name">KrillinMax</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">4</span>
                                                            <span class="bracket-slot__name">GohanSSJ</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">13</span>
                                                            <span class="bracket-slot__name">YamchaFan</span>
                                                            <span class="bracket-slot__score badge bg-secondary">1</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">3</span>
                                                            <span class="bracket-slot__name">TrunksTime</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">14</span>
                                                            <span class="bracket-slot__name">TienShin</span>
                                                            <span class="bracket-slot__score badge bg-secondary">0</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">6</span>
                                                            <span class="bracket-slot__name">Bardock17</span>
                                                            <span class="bracket-slot__score badge bg-secondary">1</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">11</span>
                                                            <span class="bracket-slot__name">Android18</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">7</span>
                                                            <span class="bracket-slot__name">FreezaLord</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">10</span>
                                                            <span class="bracket-slot__name">CoolerPT</span>
                                                            <span class="bracket-slot__score badge bg-secondary">2</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">2</span>
                                                            <span class="bracket-slot__name">Cell_Max99</span>
                                                            <span class="bracket-slot__score badge bg-secondary">1</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">15</span>
                                                            <span class="bracket-slot__name">BeerusDeus</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="bracket-connector"></div>

                                            <!-- QUARTAS DE FINAL -->
                                            <div class="bracket-round">
                                                <div class="bracket-round__title">Quartas de Final</div>
                                                <div class="bracket-round__matches">

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">1</span>
                                                            <span class="bracket-slot__name">SonGokuBR</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">8</span>
                                                            <span class="bracket-slot__name">VegetaKing</span>
                                                            <span class="bracket-slot__score badge bg-secondary">1</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">4</span>
                                                            <span class="bracket-slot__name">GohanSSJ</span>
                                                            <span class="bracket-slot__score badge bg-secondary">2</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">5</span>
                                                            <span class="bracket-slot__name">PiccoloGod</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">3</span>
                                                            <span class="bracket-slot__name">TrunksTime</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">6</span>
                                                            <span class="bracket-slot__name">Bardock17</span>
                                                            <span class="bracket-slot__score badge bg-secondary">0</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">2</span>
                                                            <span class="bracket-slot__name">Cell_Max99</span>
                                                            <span class="bracket-slot__score badge bg-secondary">1</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">7</span>
                                                            <span class="bracket-slot__name">FreezaLord</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="bracket-connector"></div>

                                            <!-- SEMIFINAIS -->
                                            <div class="bracket-round">
                                                <div class="bracket-round__title">Semifinais</div>
                                                <div class="bracket-round__matches">

                                                    <div class="bracket-match bracket-match--indent-top">
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">1</span>
                                                            <span class="bracket-slot__name">SonGokuBR</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">5</span>
                                                            <span class="bracket-slot__name">PiccoloGod</span>
                                                            <span class="bracket-slot__score badge bg-secondary">2</span>
                                                        </div>
                                                    </div>

                                                    <div class="bracket-match bracket-match--indent-bottom">
                                                        <div class="bracket-slot bracket-slot--loser">
                                                            <span class="bracket-slot__seed">3</span>
                                                            <span class="bracket-slot__name">TrunksTime</span>
                                                            <span class="bracket-slot__score badge bg-secondary">2</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--winner">
                                                            <span class="bracket-slot__seed">7</span>
                                                            <span class="bracket-slot__name">FreezaLord</span>
                                                            <span class="bracket-slot__score badge bg-success">3</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="bracket-connector"></div>

                                            <!-- FINAL -->
                                            <div class="bracket-round">
                                                <div class="bracket-round__title">Final</div>
                                                <div class="bracket-round__matches justify-content-center">

                                                    <div class="bracket-match">
                                                        <div class="bracket-slot bracket-slot--live bracket-slot--pending">
                                                            <span class="bracket-slot__seed">1</span>
                                                            <span class="bracket-slot__name">SonGokuBR</span>
                                                            <span class="bracket-slot__score badge bg-secondary text-dark">?</span>
                                                        </div>
                                                        <div class="bracket-slot bracket-slot--live bracket-slot--pending">
                                                            <span class="bracket-slot__seed">7</span>
                                                            <span class="bracket-slot__name">FreezaLord</span>
                                                            <span class="bracket-slot__score badge bg-secondary text-dark">?</span>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2 d-flex justify-content-center">
                                                        <span class="badge bg-danger fs-6 shadow-sm opacity-100 d-flex align-items-center justify-content-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16"> <circle cx="8" cy="8" r="8" /></svg>
                                                            AO VIVO
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- FIM DO BRACKET -->
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection