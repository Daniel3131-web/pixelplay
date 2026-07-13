# 🎮 Pixelplay

> Digite aqui uma descrição curta e impactante do seu projeto. Exemplo: Uma plataforma moderna para gerenciamento e streaming de jogos independentes.

---

## Tecnologias Utilizadas

Este projeto foi desenvolvido utilizando as seguintes tecnologias:

*   **Backend:** Laravel 11 (PHP)
*   **Database:** MySQL
*   **Frontend:** Blade / Vite (Vue/React se aplicar)
*   **E-mail:** Brevo API

---

## Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina:
*   [PHP 8.2 ou superior](https://php.net)
*   [Composer](https://getcomposer.org)
*   [MySQL](https://mysql.com)
*   [Node.js & NPM](https://nodejs.org)

---

## Como Executar o Projeto

Siga os passos abaixo para rodar a aplicação localmente:

### 1. Clonar o Repositório
```bash
git clone https://github.com
cd pixelplay
```

### 2. Instalar as Dependências
```bash
# Instalar dependências do PHP
composer install

# Instalar dependências do Frontend
npm install
```

### 3. Configurar as Variáveis de Ambiente
Crie uma cópia do arquivo `.env.example` e mude o nome para `.env`:
```bash
cp .env.example .env
```
Abra o arquivo `.env` e configure as credenciais do seu banco de dados local (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

### 4. Configurar a Chave e o Banco de Dados
```bash
# Gerar a APP_KEY do Laravel
php artisan key:generate

# Criar o banco de dados e rodar as migrações com os dados iniciais
php artisan migrate --seed
```

### 5. Iniciar os Servidores
Abra dois terminais diferentes para rodar o backend e o frontend:

*   **Terminal 1 (Backend PHP):**
    ```bash
    php artisan serve
    ```
*   **Terminal 2 (Compilação de Assets):**
    ```bash
    npm run dev
    ```

Agora, acesse o projeto pelo navegador através do endereço: `http://localhost:8000`

---


