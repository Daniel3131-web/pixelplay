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

<div class="container py-5">
    
    <div class="row justify-content-center text-center mb-5 text-white">
        <div class="col-lg-8">
            <h1 class="fw-bold display-6">Como podemos te ajudar hoje?</h1>
            <p class="text-white-50 lead">Nossa equipe técnica e de organização de torneios está pronta para resolver o seu problema.</p>
        </div>
    </div>

    <div class="row g-5 justify-content-center">
        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4">Envie uma Mensagem</h4>
                    
                    <form action="{{}}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label fw-semibold">Nome ou Nickname <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light" id="nome" name="nome" placeholder="Seu nome" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                                <input type="email" class="form-control bg-light" id="email" name="email" placeholder="seu@email.com" required>
                            </div>

                            <div class="col-12">
                                <label for="assunto" class="form-label fw-semibold">Qual é o assunto? <span class="text-danger">*</span></label>
                                <select class="form-select bg-light" id="assunto" name="assunto" required>
                                    <option value="" selected disabled>Selecione uma opção...</option>
                                    <option value="inscricao">Problemas com Inscrição de Equipe</option>
                                    <option value="chaves">Dúvida sobre Tabelas e Chaves (Brackets)</option>
                                    <option value="bug">Relatar um Erro na Plataforma</option>
                                    <option value="corporativo">Parcerias e Eventos Corporativos</option>
                                    <option value="outros">Outros Assuntos</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="link_torneio" class="form-label fw-semibold">Link do Torneio / Evento <span class="text-muted small">(Opcional)</span></label>
                                <input type="url" class="form-control bg-light" id="link_torneio" name="link_torneio" placeholder="Ex: pixelplay.com/evento/campeonato-csgo">
                            </div>

                            <div class="col-12">
                                <label for="mensagem" class="form-label fw-semibold">Sua Mensagem <span class="text-danger">*</span></label>
                                <textarea class="form-control bg-light" id="mensagem" name="mensagem" rows="5" placeholder="Descreva seu problema com o máximo de detalhes possível..." required></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3">
                                    Enviar Solicitação
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4 h-100">
                
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Contato Direto</h5>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16"><path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/></svg>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">E-mail</h6>
                                <p class="text-muted small mb-0">suporte@pixelplay.com.br</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">WhatsApp</h6>
                                <p class="text-muted small mb-0">(41) 99999-0000</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold">Horário de Atendimento</h6>
                        <ul class="list-unstyled text-muted small mb-0">
                            <li class="mb-1"><strong>Segunda a Sexta:</strong> 09h às 18h</li>
                        </ul>
                    </div>
                </div>
@endsection
                