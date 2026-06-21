# Estrutura do Projeto - PixelPlay

## Organização

O projeto foi reorganizado para separar responsabilidades de forma clara:

### `app/Http/Controllers/`

#### `Api/` - Controllers de Recurso
- **Tournament/** - Controladores de Torneios
- **Team/** - Controladores de Times
- **Event/** - Controladores de Eventos
- **Match/** - Controladores de Partidas
- **Payment/** - Controladores de Pagamentos

#### `Dashboard/` - Controllers de Dashboard
- **OrgController** - Painel do Organizador
- **ProfileController** - Perfil do Usuário

#### `Auth/` - Autenticação (mantido)

### `app/Http/Requests/`

Separado por domínio de negócio:
- **Tournament/** - Validações de Torneios
- **Team/** - Validações de Times
- **Event/** - Validações de Eventos
- **Match/** - Validações de Partidas
- **Payment/** - Validações de Pagamentos
- **Auth/** - Validações de Autenticação (mantido)

### `app/Services/`

Serviços de lógica de negócio:
- **TeamService.php** - Operações de Times
- **TournamentService.php** - Operações de Torneios
- **EventService.php** - Operações de Eventos
- **PaymentService.php** - Operações de Pagamentos

### `app/Models/`

Modelos do banco de dados (para organizar melhor, considere):
- **Tournament/** - Tournament, TournamentMatch
- **Team/** - Team, User
- **Game/** - Character, Map, PlayerInfos

## Benefícios

✅ **Escalabilidade** - Fácil adicionar novos recursos
✅ **Manutenibilidade** - Código organizado por domínio
✅ **Testabilidade** - Services facilitam testes
✅ **Reutilização** - Lógica de negócio centralizada
✅ **Clareza** - Estrutura intuitiva

## Próximos Passos

1. Reorganizar Models por domínio
2. Atualizar Controllers para usar Services
3. Adicionar testes para Services
4. Criar mais Services conforme necessário

## Rotas

As rotas foram atualizadas em `routes/web.php` para usar os novos namespaces dos Controllers.
