# 🎨 FASE 3: FRONTEND (BLADE + TAILWIND + JQUERY)

## 📋 CONTEXTO DO DESIGN SYSTEM
Atue como um Engenheiro Frontend Sênior e UI/UX Designer. Seu objetivo é implementar a interface web do ERP utilizando **Blade Templates**, **Tailwind CSS** e **jQuery**, aderindo 
estritamente ao Design System abaixo.

A estética do sistema baseia-se em um "Clean Admin Dashboard", caracterizado por uma **Barra Lateral Escura (Deep Navy)**, **Área de Conteúdo Clara (Off-white)**, **Cartões Brancos** e 
**Acentos em Laranja Vibrante (Amber/Orange)** para botões e itens ativos.

---

### 1. Configuração do Tailwind (`tailwind.config.js`) e Cores Base

Configure o seu arquivo Tailwind para estender estas cores exatas, baseadas na nossa identidade visual:

* **Primary (Barra Lateral e Textos Escuros):** * `DEFAULT: '#1c2434'` (Fundo da Sidebar)
    * `dark: '#111827'` (Títulos e textos principais)
    * `light: '#8a99af'` (Texto inativo na Sidebar)
* **Accent (Botões, Ícones e Item Ativo na Sidebar):** * `DEFAULT: '#f59e0b'` (Laranja/Âmbar vibrante - usado no botão "Ver Todos" e "Dashboard")
    * `hover: '#d97706'`
* **Background (Área de Trabalho Principal):** * `DEFAULT: '#f1f5f9'` (Cinza muito claro para contrastar com os cartões brancos)
* **Semantic Colors (Ícones dos Cards e Badges):**
    * `blue: '#3b82f6'` (Ícone "Total de Leads")
    * `green: '#10b981'` (Ícone "Últimos 7 Dias" e Badge "LIDO")
    * `purple: '#8b5cf6'` (Ícone "Atendimento")

### 2. Diretrizes de Estilo Visual e Componentes

A interface deve ser construída utilizando **Blade Components** (`<x-card>`, `<x-button>`, etc.) para manter o código limpo. Aplique as seguintes regras do Tailwind:

* **A Barra Lateral (Sidebar):** * Fundo: `bg-[#1c2434] text-white`.
    * Menu Ativo: O item de menu selecionado deve ter o fundo Laranja (`bg-accent`), cantos arredondados (`rounded-md` ou `rounded-lg`) e texto branco em negrito.
    * Menu Inativo: Texto na cor cinza claro (`text-[#8a99af] hover:text-white`).
* **O Cabeçalho (Top Navbar):** * Fundo: Branco (`bg-white`).
    * Deve conter o título da página atual à esquerda (ex: "Dashboard" em `text-2xl font-bold text-gray-800`).
    * Perfil do usuário à direita.
* **Cartões de Estatísticas (Stats Cards):**
    * Estilo: `bg-white rounded-xl shadow-sm border border-gray-100 p-6`.
    * Layout: Ícone grande à esquerda (com fundo colorido suave e arredondado, ex: `bg-blue-100 text-blue-500 rounded-xl`) e os números à direita.
* **Tabelas de Dados (Data Tables):**
    * Fundo da tabela: `bg-white rounded-xl shadow-sm overflow-hidden`.
    * Cabeçalho da Tabela (`thead`): Fundo cinza bem claro (`bg-gray-50`), texto em caixa alta (`uppercase text-xs font-semibold text-gray-500`).
    * Linhas (`tbody`): Bordas sutis separando as linhas (`border-b border-gray-100`).
* **Badges de Status (Pílulas):**
    * Exemplo de Status "LIDO" ou "ATIVO": Fundo verde muito claro (`bg-green-50`) com texto verde esmeralda (`text-green-600 font-medium text-xs px-2.5 py-0.5 rounded-full border border-green-200`).

### 3. Interatividade com jQuery

* Utilize **jQuery** em um arquivo separado ou bloco de script dedicado para manipular:
    1.  Abertura e fechamento da Sidebar em dispositivos móveis (Mobile-First).
    2.  Máscaras de formulário (`jquery.mask.js`) para campos como Telefone `(41) 99999-9999`, CPF/CNPJ e valores financeiros na cor laranja ao focar no input.

---

**TAREFA:** Utilizando as especificações acima, gere o código para o **Layout Mestre (app.blade.php)** contemplando a Sidebar e a Topbar, e em seguida crie a view do **Dashboard Inicial**
com os 4 cartões de resumo e a tabela de registros recentes, 
idêntico à identidade visual solicitada.