# 🚀 Boilerplate Core Administrativo (Sistema Padrão)

Este é o template padrão (boilerplate) para o desenvolvimento de sistemas administrativos integrados utilizando **Laravel, Blade Templates, Tailwind CSS, Vite e jQuery/AlpineJS**.

O sistema já vem equipado com uma estrutura robusta de administração core contendo:
*   🔑 **Autenticação completa** (Laravel Breeze).
*   👥 **Controle de Acessos** (ACL - Usuários, Perfis e Permissões dinâmicas).
*   📊 **Dashboard Administrativo** construído no padrão Bento Grid com visual moderno e responsivo.
*   📋 **Navegação Dinâmica** (Sidebar alimentada dinamicamente pelo banco de dados a partir de categorias e menus).
*   🛡️ **Logs de Auditoria** automáticos integrados no banco de dados.

---

## 🛠️ Requisitos
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   Banco de Dados (MySQL / MariaDB ou SQLite)

---

## 🚀 Como Executar em um Novo Projeto

Siga os passos abaixo para configurar e rodar o boilerplate em uma nova máquina ou diretório:

### 1. Clonar o Repositório
```bash
git clone https://github.com/cfgfernando/sistema-padrao.git seu-novo-projeto
cd seu-novo-projeto
```

### 2. Instalar as Dependências do PHP (Composer)
```bash
composer install
```

### 3. Instalar as Dependências do Frontend (NPM)
```bash
npm install
```

### 4. Configurar as Variáveis de Ambiente
Copie o arquivo de exemplo `.env.example` para `.env`:
*   **Linux / macOS:**
    ```bash
    cp .env.example .env
    ```
*   **Windows (PowerShell):**
    ```powershell
    Copy-Item .env.example .env
    ```

Abra o arquivo `.env` gerado e defina os dados da sua conexão de banco de dados.

### 5. Configurar o Banco de Dados (É necessário criar um novo?)
**Sim, você deve criar um banco de dados vazio no seu gerenciador (MySQL/Laragon, phpMyAdmin, etc.) antes de migrar.**

No seu `.env`, preencha as credenciais correspondentes ao banco criado:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_criado
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 6. Gerar a Chave de Criptografia da Aplicação
```bash
php artisan key:generate
```

### 7. Executar as Migrations e os Seeders do Core
Este comando cria toda a estrutura de tabelas administrativas e insere o usuário master padrão:
```bash
php artisan migrate --seed
```

### 8. Compilar os Assets de Estilos (Tailwind / Vite)
*   **Para Desenvolvimento Local:**
    ```bash
    npm run dev
    ```
*   **Para Compilação Final (Produção):**
    ```bash
    npm run build
    ```

---

## 🔑 Credenciais Padrão de Acesso

Após rodar o comando com `--seed`, o sistema criará o usuário administrador inicial:
*   **URL de Login:** `/login`
*   **E-mail:** `admin@sistema.com`
*   **Senha:** `admin123`

> 💡 **Nota de Segurança:** Lembre-se de alterar as credenciais de e-mail e senha no primeiro acesso através do menu de perfil do usuário.
