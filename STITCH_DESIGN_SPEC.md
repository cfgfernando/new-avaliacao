# Especificação Completa do Sistema para Stitch: Avaliação de Desempenho Público (BARS & OKR)

Este documento descreve detalhadamente a arquitetura funcional, os módulos, a estrutura de dados e as diretrizes estéticas do sistema de **Avaliação de Desempenho Público (APD)** baseado em metodologias mistas de **BARS (Behaviorally Anchored Rating Scales)** e **OKRs (Metas Quantitativas/Objetivos)**. Este material serve como especificação direta para criação de projetos, geração de telas e configuração do Design System no **Stitch**.

---

## 1. Visão Geral do Sistema

O sistema é um portal administrativo de alta performance voltado à gestão de pessoas no setor público. Ele foi projetado para mitigar a subjetividade das avaliações de funcionários públicos por meio de duas abordagens integradas:
1.  **BARS (Behaviorally Anchored Rating Scales):** Réguas de avaliação comportamental onde cada nota de 1 a 5 é ancorada a uma descrição detalhada de comportamento real observado.
2.  **OKRs (Metas Quantitativas):** Objetivos com métricas claras, valores-alvo e percentuais de atingimento reais.
3.  **Diário de Incidentes Críticos (Incidentes Críticos):** Registro diário e contínuo de condutas positivas e negativas do servidor, utilizado como fundamentação jurídica para a nota final e para possíveis recursos administrativos.

A aplicação é executada em uma estrutura clássica corporativa (Bento Grid, Sidebar Escura e Canvas Claro) com foco em produtividade, conformidade com a legislação pública e auditoria completa de ações.

---

## 2. Perfis de Usuário & Permissões (ACL)

A aplicação conta com controle de acesso dinâmico (ACL) baseado em Perfis (Roles) e Permissões:

*   **Administrador Geral (Admin):**
    *   Gestão de usuários (Servidores e Avaliadores).
    *   Configuração e controle dos Ciclos de Avaliação.
    *   Criação e edição do banco de perguntas e dimensões de competência.
    *   Cadastro de Âncoras BARS por questão.
    *   Gestão de Secretarias/Lotações (Offices) e Menus.
    *   Acesso aos Logs de Auditoria Global.
*   **Avaliador (Gestor / Chefe Imediato):**
    *   Acesso ao Diário de Incidentes dos servidores de sua equipe.
    *   Lançamento de incidentes críticos (positivos e negativos).
    *   Abertura e preenchimento de avaliações durante ciclos ativos.
    *   Pactuação e acompanhamento de metas quantitativas (OKRs) individuais.
    *   Submissão de avaliações concluídas.
*   **Servidor Avaliado (Funcionário Público):**
    *   Visualização de suas avaliações anteriores e notas consolidadas.
    *   Acompanhamento do diário de incidentes (quando liberado).
    *   Visualização de suas metas pactuadas.
    *   Assinatura de relatórios de desempenho e ciência de ciclos finalizados.

---

## 3. Estrutura de Banco de Dados & Entidades

Para a modelagem de telas no Stitch, as principais entidades e seus atributos no banco de dados são:

### 3.1. Usuários (`users`)
*   `id` (Int, PK)
*   `name` (String) - Nome do servidor.
*   `email` (String, Unique)
*   `role` (String) - Perfil de acesso (admin, avaliador, servidor).
*   `office_id` (FK -> `offices`) - Secretaria, órgão ou lotação física.
*   `cargo` (String) - Cargo público ocupado.
*   `matricula` (String) - Matrícula funcional única.
*   `data_admissao` (Date) - Data de entrada no serviço público.

### 3.2. Secretarias / Lotações (`offices`)
*   `id` (Int, PK)
*   `name` (String, Unique) - Nome do órgão/secretaria.
*   `sigla` (String) - Sigla gerada dinamicamente.
*   `is_active` (Boolean) - Status de ativação.

### 3.3. Ciclos de Avaliação (`evaluation_cycles`)
*   `id` (Int, PK)
*   `name` (String) - Nome do ciclo (ex: "Avaliação Anual de Desempenho 2026").
*   `start_date` (Date) - Início do período avaliativo.
*   `end_date` (Date) - Fim do período avaliativo.
*   `cutoff_score` (Decimal 4,2) - Nota mínima de corte para aprovação/bonificação (padrão: `3.00`).
*   `weights` (JSON) - Pesos percentuais das categorias avaliadas (ex: `{"assiduidade": 1.5, "produtividade": 2.0}`).
*   `status` (Enum) - `draft` (Rascunho), `active` (Ativo), `suspended` (Suspenso) ou `completed` (Concluído).
*   `block_on_pad` (Boolean) - Bloqueio de novos registros quando em processo de homologação.
*   `global_goals` (Boolean) - Integração com metas macros institucionais.

### 3.4. Perguntas de Avaliação (`evaluation_questions`)
*   `id` (Int, PK)
*   `text` (Text) - Enunciado da dimensão/competência avaliada (ex: "Relacionamento Interpessoal").
*   `category` (String) - Grupo da pergunta (ex: "Comportamental", "Técnico").
*   `is_active` (Boolean) - Status.

### 3.5. Âncoras BARS (`bars_anchors`)
*   `id` (Int, PK)
*   `question_id` (FK -> `evaluation_questions`)
*   `score` (TinyInteger) - Nota correspondente (de 1 a 5).
*   `behavioral_description` (Text) - Comportamento modelo associado à nota.

### 3.6. Avaliações (`evaluations`)
*   `id` (Int, PK)
*   `cycle_id` (FK -> `evaluation_cycles`)
*   `evaluator_id` (FK -> `users`)
*   `evaluated_id` (FK -> `users`)
*   `status` (Enum) - `pending` (Pendente), `submitted` (Enviada), `under_appeal` (Em Recurso) ou `completed` (Finalizada).
*   `final_score` (Decimal 4,2) - Média ponderada calculada automaticamente.
*   `submitted_at` (Timestamp) - Data de envio.
*   `mista_weights` (Boolean) - Habilitação de pesos diferenciados por cargo.

### 3.7. Metas Quantitativas / OKRs (`quantitative_goals`)
*   `id` (Int, PK)
*   `evaluation_id` (FK -> `evaluations`)
*   `description` (String) - Descrição da meta.
*   `metric` (String) - Indicador de sucesso (ex: "Processos analisados", "% de acórdãos").
*   `target_value` (Decimal 12,2) - Alvo pactuado.
*   `achieved_value` (Decimal 12,2) - Valor real alcançado.
*   `weight` (Decimal 4,2) - Peso específico da meta.

### 3.8. Diário de Incidentes Críticos (`employee_diary_incidents`)
*   `id` (Int, PK)
*   `employee_id` (FK -> `users`) - Servidor afetado.
*   `reporter_id` (FK -> `users`) - Avaliador ou fiscal que registrou a conduta.
*   `category` (String) - Categoria associada (iniciativa, disciplina, assiduidade, etc.).
*   `description` (Text) - Detalhamento minucioso do fato.
*   `type` (Enum) - `positive` (Positivo) ou `negative` (Negativo).
*   `incident_date` (Date) - Data da ocorrência.

---

## 4. Mapa das Telas e Fluxos (Navegação)

Ao gerar telas no Stitch para este projeto, mapeie a seguinte árvore de navegação do painel administrativo:

1.  **Dashboard Principal (`/dashboard`):**
    *   Painel em Bento Grid contendo cards estatísticos de status rápidos.
    *   Listagem resumida de avaliações em andamento e prazos dos ciclos ativos.
    *   Gráfico com percentuais de servidores acima/abaixo da nota de corte (`cutoff_score`).
2.  **Ciclos de Avaliação (`/admin/evaluation-cycles`):**
    *   Listagem de ciclos em formato de tabela elegante.
    *   Formulário de criação de ciclo contendo controle de datas, definição de nota de corte e atribuição de pesos para categorias.
3.  **Perguntas & Âncoras BARS (`/admin/evaluation-questions`):**
    *   Tela de CRUD de perguntas divididas por categorias comportamentais.
    *   Subtela de detalhamento de âncoras BARS, exibindo a régua de 1 a 5 com suas respectivas descrições de comportamento modelo.
4.  **Fluxo de Preenchimento da Avaliação APD (`/evaluations/evaluate/{id}`):**
    *   Formulário interativo contendo:
        *   **Seção de Identificação:** Dados do servidor avaliado e cargo.
        *   **Seção BARS (Comportamental):** Exibição da pergunta e régua de seleção de notas de 1 a 5. Ao passar o mouse ou selecionar cada número, exibe dinamicamente a descrição da âncora BARS correspondente.
        *   **Seção OKR (Metas Quantitativas):** Tabela dinâmica para adicionar metas pactuadas, informando a métrica, o alvo e o valor alcançado.
        *   **Seção Diário de Incidentes:** Vínculo de incidentes críticos já registrados no ciclo para justificar notas baixas (obrigatório se nota comportamental < 3.0).
5.  **Diário de Incidentes Críticos (`/admin/logs-incidents`):**
    *   Linha do tempo (Timeline) elegante exibindo registros rápidos de incidentes de servidores filtrados por Secretaria. Cada evento mostra um badge indicador (`Positivo` verde / `Negativo` vermelho), descrição do comportamento observado, data e o avaliador que reportou.
6.  **Servidores & Resultados (`/admin/evaluated-users`):**
    *   Ficha funcional do servidor público contendo pontuação acumulada, histórico de notas por ciclo em gráficos de linhas, e PDF para assinatura digital.

---

## 5. Design System (Stitch / Tailwind CSS Spec)

Diretrizes estéticas estritas baseadas no Design System de Alta Performance estabelecido para a aplicação:

### 5.1. Paleta de Cores e Tokens Visuais
*   **Fundo Geral (Workspace Canvas):** `#f8fafc` (Tailwind `bg-slate-50` / `bg-slate-100`) - Neutralidade visual e descanso aos olhos.
*   **Containers e Módulos (Cards):** `#ffffff` (Tailwind `bg-white`) com borda fina de baixo contraste `border border-slate-200` e cantos arredondados generosos `rounded-xl` ou `rounded-2xl`. Sombra imperceptível `shadow-xs`.
*   **Textos:**
    *   Títulos de Destaque: `#0f172a` (`text-slate-900`, `font-bold` ou `font-extrabold`).
    *   Leitura e Corpo do Texto: `#334155` (`text-slate-700`).
    *   Descritores e Metadados: `#64748b` (`text-slate-500`).
*   **Acento Principal (Accent):** Azul Royal Real (`text-blue-600`, `bg-blue-50`, `border-blue-200`, `hover:bg-blue-100`). Usado para botões primários, itens de navegação selecionados e foco de inputs.
*   **Status Semântico:**
    *   *Sucesso / Ação Concluída:* Verde Esmeralda (`text-emerald-700` / `bg-emerald-50` / `border-emerald-200`).
    *   *Pendente / Alerta de Ciclo / Rascunho:* Dourado Âmbar (`text-amber-700` / `bg-amber-50` / `border-amber-200`).
    *   *Inconformidade / Incidente Negativo / Crítico:* Vermelho Rose (`text-rose-700` / `bg-rose-50` / `border-rose-150`).

### 5.2. Tipografia e Fontes
*   **Textos Gerais, Botões e Labels:** Fonte **Inter** (peso 400 para leitura, 500 para labels, 700+ para títulos).
*   **Dados Técnicos, Valores e Métricas:** Fonte **JetBrains Mono** (`font-mono`). Deve ser usada rigorosamente para expressar notas (ex: `✓ 4.5/5.0`), pesos de OKR `%`, datas funcionais, matricula e indicativos de níveis de âncoras (`Nível 1` a `Nível 5`).
*   **Ajuste de Tracking:** Use sempre `tracking-tight` em cabeçalhos de seções para um visual elegante.

### 5.3. Elementos e Microinterações
*   **Tabelas de Dados:** Fundo branco com cabeçalhos estruturados em caixa alta, tamanho reduzido e forte semântica (`text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono`). Linhas separadas por divisores horizontais leves (`border-slate-100` ou `border-slate-200/60`).
*   **Botões:** Cantos arredondados do tipo `rounded-lg` ou `rounded-xl`. Efeito hover suave (`transition-all duration-200 hover:bg-slate-100` ou `hover:bg-blue-600/90`).
*   **Réguas BARS:** Réguas horizontais com 5 botões seletores arredondados. O botão selecionado ativa a cor semântica do nível, enquanto os inativos mantêm contornos elegantes em cinza.

---

## 6. Prompt para Geração de Telas no Stitch (Markdown de Entrada)

Ao solicitar que o Stitch gere uma tela ou fluxo para este projeto, utilize o prompt estruturado abaixo:

```markdown
Crie uma tela para o sistema de Avaliação de Desempenho Público (BARS & OKRs) baseando-se estritamente nas seguintes regras de design e estrutura:

1. AESTHETICS: Clean Admin Dashboard, estilo minimalista corporativo. Fundo #f8fafc (slate-50). Cards brancos (#ffffff) com bordas finas border-slate-200, cantos rounded-xl e sombras imperceptíveis shadow-xs.
2. CORES: Acento primário em Azul Royal (text-blue-600 / bg-blue-50 / border-blue-200). Status: Verde Esmeralda (sucesso/concluído), Amarelo Âmbar (pendente/rascunho), Vermelho Rose (incidente crítico negativo).
3. TIPOGRAFIA: Fonte Inter para textos principais, labels e cabeçalhos. Fonte JetBrains Mono (font-mono) obrigatoriamente para números de notas, percentuais, datas de incidentes e identificadores de níveis (ex: "Nível 5").
4. LAYOUT: Grid tipo Bento responsivo. Inputs com labels em caixa alta, tamanho minúsculo e espaçado (text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block).
5. INTERATIVIDADE: Hover states suaves com transition-all duration-200. Sem gradientes invasivos.
```
