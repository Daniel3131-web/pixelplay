@extends('layouts.guest_main')

@section('content')

    <style>
        body {
            background-color: var(--a2-color);

            >main {
                opacity: 1;
            }
        }
    </style>

    <div class="text-white py-5">
        <div class="container py-5 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="fw-bold display-4 mb-4">Elevando o nível dos eSports no Brasil</h1>
                    <p class="lead text-white-50 mb-0">
                        Da paixão pelos games à criação de uma infraestrutura profissional para campeonatos amadores e
                        eventos corporativos gamificados.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5 my-4">
        <div class="row align-items-center g-5">

            <div class="col-lg-6 text-white">
                <h2 class="fw-bold mb-4">Quem é a PixelPlay?</h2>
                <p class="text-white-50" style="line-height: 1.8;">
                    Fundada em 2018, com sede em Curitiba (PR), a <strong>PixelPlay Eventos Digitais</strong> nasceu com um
                    objetivo claro: democratizar o acesso a competições de alto nível. Começamos organizando pequenos
                    encontros locais e ligas amadoras, mas rapidamente percebemos que o cenário brasileiro de games
                    precisava de mais profissionalismo e estrutura.
                </p>
                <p class="text-white-50" style="line-height: 1.8;">
                    Hoje, com atuação em todo o território nacional, organizamos desde campeonatos de comunidade até
                    megaeventos corporativos para empresas de tecnologia e universidades. Nós transformamos a maneira como
                    jogadores, equipes e organizadores vivenciam a competição.
                </p>

                <div class="d-flex gap-3 mt-4">
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-primary shadow-sm flex-fill">
                        <h5 class="fw-bold text-primary mb-1">Missão</h5>
                        <p class="small text-muted mb-0">Entregar a melhor experiência competitiva com tecnologia e
                            organização impecável.</p>
                    </div>
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-success shadow-sm flex-fill">
                        <h5 class="fw-bold text-success mb-1">Visão</h5>
                        <p class="small text-muted mb-0">Ser a maior plataforma e produtora de eventos gamificados do
                            Brasil.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="card border-0 bg-primary text-white text-center shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">
                                <h2 class="display-5 fw-bold mb-0">2018</h2>
                                <p class="mb-0">Ano de Fundação</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-light text-center shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">
                                <h2 class="display-5 fw-bold text-dark mb-0">+40</h2>
                                <p class="text-muted mb-0">Eventos Anuais</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-light text-center shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">
                                <h2 class="display-5 fw-bold text-dark mb-0">100%</h2>
                                <p class="text-muted mb-0">Atuação Nacional</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-success text-white text-center shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">
                                <h2 class="display-5 fw-bold mb-0">+10k</h2>
                                <p class="mb-0">Jogadores Impactados</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="bg-light py-5 border-top border-bottom">
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="fw-bold">Nossa Evolução Tecnológica</h2>
                    <p class="text-muted">De planilhas manuais para um ecossistema inteligente e centralizado.</p>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="text-center px-3">
                        <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-4"
                            style="width: 80px; height: 80px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-controller" viewBox="0 0 16 16">
                                <path
                                    d="M11.5 6.027a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm-1.5 1.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zm2.5-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm-1.5 1.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zm-6.5-3h1v1h1v1h-1v1h-1v-1h-1v-1h1v-1z" />
                                <path
                                    d="M3.051 3.26a.5.5 0 0 1 .354-.613l1.932-.518a.5.5 0 0 1 .62.39c.655-.079 1.35-.117 2.043-.117.72 0 1.443.041 2.12.126a.5.5 0 0 1 .622-.399l1.932.518a.5.5 0 0 1 .306.729c.14.09.266.19.373.297.408.408.78 1.05 1.095 1.772.32.733.599 1.591.805 2.466.206.875.34 1.78.364 2.606.024.816-.059 1.602-.328 2.21a1.42 1.42 0 0 1-1.445.83c-.636-.067-1.115-.394-1.513-.773-.245-.232-.496-.526-.739-.808-.126-.148-.25-.292-.368-.423-.728-.804-1.597-1.527-3.224-1.527-1.627 0-2.496.723-3.224 1.527-.119.131-.242.275-.368.423-.243.282-.494.575-.739.808-.398.38-.877.706-1.513.773a1.42 1.42 0 0 1-1.445-.83c-.27-.608-.352-1.395-.329-2.21.024-.826.16-1.73.365-2.606.206-.875.486-1.733.805-2.466.315-.722.687-1.364 1.094-1.772a2.324 2.324 0 0 1 .436-.302l-.04-.002.04.002z" />
                            </svg>
                        </div>
                        <h5 class="fw-bold">Gestão 360º</h5>
                        <p class="text-muted small">Crescemos muito rápido. Para garantir a melhor experiência,
                            desenvolvemos nosso próprio sistema de controle de chaves, inscrições e relatórios em tempo
                            real.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-center px-3">
                        <div class="bg-white text-success rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-4"
                            style="width: 80px; height: 80px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-people-fill" viewBox="0 0 16 16">
                                <path
                                    d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                            </svg>
                        </div>
                        <h5 class="fw-bold">Comunidade em Foco</h5>
                        <p class="text-muted small">A comunicação fragmentada ficou no passado. Nossa plataforma conecta
                            jogadores e organizadores através de perfis públicos, notificações instantâneas e suporte
                            dedicado.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5 mt-3 text-center">
        <h3 class="fw-bold text-white mb-3">Pronto para o próximo nível?</h3>
        <p class="text-white-50 mb-4">Inscreva sua equipe em nossos torneios ou leve um evento corporativo de eSports para a
            sua empresa.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('player.eventos') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">Ver Eventos
                Atuais</a>
            <a href="{{ route('contato') }}" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold">Fale com a
                Equipe</a>
        </div>
    </div>
@endsection