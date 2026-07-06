@extends('layouts.guest_main')

@section('title', 'Pixelplay - Faq')

@push('styles')
    <link rel="stylesheet" href="/css/landing.css">
@endpush


@section('content')

    <style>
        body {
            background-color: var(--a2-color);

            >main {
                opacity: 1;
            }
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <!-- Cabeçalho do FAQ -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-white display-6">Perguntas Frequentes</h2>
                    <p class="text-white-50 lead">Tudo o que você precisa saber sobre a nova plataforma de gestão de eventos e
                        torneios da PixelPlay.</p>
                </div>

                <!-- Início do Accordion -->
                <div class="accordion shadow-sm border rounded" id="faqPixelPlay">

                    <!-- Pergunta 1: Inscrição -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Como faço para inscrever minha equipe em um campeonato?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#faqPixelPlay">
                            <div class="accordion-body text-muted">
                                Esqueça os formulários genéricos! Agora, você acessa a página oficial do evento na nossa
                                plataforma, clica em "Inscrever Equipe" e preenche os dados. O sistema valida as informações
                                automaticamente para evitar duplicidade e você recebe a <strong>confirmação da sua vaga
                                    diretamente no e-mail</strong>, além de poder gerenciar os jogadores no seu perfil
                                público.
                            </div>
                        </div>
                    </div>

                    <!-- Pergunta 2: Acompanhamento de Chaves -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Onde acompanho as chaves (brackets) e os resultados das partidas?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#faqPixelPlay">
                            <div class="accordion-body text-muted">
                                Você não precisa mais caçar resultados em grupos de WhatsApp. Todas as chaves de torneio,
                                tabelas de classificação e resultados são <strong>atualizados em tempo real</strong> no
                                painel do evento. Assim que uma partida acaba e o resultado é validado, a próxima fase da
                                chave é gerada automaticamente.
                            </div>
                        </div>
                    </div>

                    <!-- Pergunta 3: Notificações -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Como serei avisado sobre os horários das partidas e mudanças no cronograma?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#faqPixelPlay">
                            <div class="accordion-body text-muted">
                                Nossa plataforma conta com um sistema de <strong>notificações multicanal</strong>. Você
                                receberá alertas diretamente no seu painel de jogador, além de lembretes por e-mail, sempre
                                que houver aproximação do horário da sua partida, mudanças de servidor ou alterações de
                                cronograma por parte da organização.
                            </div>
                        </div>
                    </div>

                    <!-- Pergunta 4: Organizadores/Regras -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Sou organizador de um evento corporativo. Posso criar regras personalizadas?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#faqPixelPlay">
                            <div class="accordion-body text-muted">
                                Sim! O painel do organizador permite o <strong>cadastro estruturado e 100%
                                    customizável</strong> de eventos. Você pode definir os tipos de torneio (eliminação
                                simples, dupla, pontos corridos), categorias por jogo, limites de vagas e estrutura de
                                premiações de acordo com a necessidade da sua empresa ou faculdade.
                            </div>
                        </div>
                    </div>

                    <!-- Pergunta 5: Relatórios -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                A plataforma gera relatórios após o fim do campeonato?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#faqPixelPlay">
                            <div class="accordion-body text-muted">
                                Com certeza. Entendemos que dados são essenciais para escalar seus eventos. Ao final de cada
                                torneio, os organizadores têm acesso a relatórios estatísticos detalhados, incluindo
                                <strong>indicadores de performance por jogador, engajamento das equipes, taxas de abandono
                                    (W.O) e exportação de dados</strong> para facilitar o planejamento das próximas edições.
                            </div>
                        </div>
                    </div>

                    <!-- Pergunta 6: Integrações -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                O sistema possui integração com Twitch, YouTube ou meios de pagamento?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                            data-bs-parent="#faqPixelPlay">
                            <div class="accordion-body text-muted">
                                Sim, nossa arquitetura foi desenhada para ser escalável. As páginas de evento suportam a
                                incorporação de <strong>plataformas de streaming (como Twitch e YouTube)</strong> para
                                transmissão ao vivo. Para eventos pagos, o sistema é integrado a gateways de pagamento
                                seguros, garantindo a inscrição automática após a confirmação financeira.
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Fim do Accordion -->

                <!-- Card de Suporte -->
                <div class="mt-5">
                    <div class="card bg-light border-0 shadow-sm rounded-4">
                        <div class="card-body text-center p-5">
                            <h4 class="fw-bold mb-3">Sua dúvida não está aqui?</h4>
                            <p class="text-muted mb-4">Nossa equipe de suporte está pronta para ajudar você ou sua
                                organização a escalar o próximo grande evento de eSports.</p>
                            <a href="{{ route('contato') }}"
                                class="btn btn-primary px-5 py-2 rounded-pill fw-semibold shadow-sm">
                                Falar com o Suporte
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection