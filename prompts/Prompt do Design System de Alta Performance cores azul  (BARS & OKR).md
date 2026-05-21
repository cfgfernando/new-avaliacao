# Prompt do Design System de Alta Performance (BARS & OKR)

Este documento contém a estruturação formal do **Design System** adotado nesta aplicação de gestão e avaliação de desempenho público (BARS & OKRs), formatado como um **System Prompt** pronto para ser
 consumido por IAs ou desenvolvedores. Ele garante consistência visual, coerência de layout e uma experiência de usuário polida e profissional.

---

## 🎨 IDENTIDADE VISUAL & CARTELA DE CORES

O design utiliza uma abordagem baseada em **Camadas Neutras Limpas** com **Acentos Semânticos Fortes**. A legibilidade, o ar contemporâneo e o foco na produtividade administrativa norteiam todas as 
decisões cromáticas:

*   **Fundo Geral (Slipped Light Slate):** `#f8fafc` (`bg-slate-50` / `bg-slate-100`) — Proporciona um contraste suave para cansar menos os olhos do que o branco puro.
*   **Containers Principais (Card Canvas):** `#ffffff` (`bg-white`) — Bordas limpas (`border-slate-200`), cantos amplos (`rounded-xl` / `rounded-2xl`) e sombras imperceptíveis 
(`shadow-xs` / `shadow-sm`).
*   **Textos & Tipografia base:**
    *   Títulos e textos de alta ênfase: `#0f172a` (`text-slate-900` / `font-bold`)
    *   Corpo estrutural secundário: `#334155` (`text-slate-700`)
    *   Sublegendas e descrições leves: `#64748b` (`text-slate-500`)
*   **Cores de Status (Feedback Semântico):**
    *   **Azul Real (Ações / Ativo / Selecionado):** `text-blue-600` / `bg-blue-50` / `border-blue-200`
    *   **Esmeralda (Sucesso / Assinado / Nota Concluída):** `text-emerald-700` / `bg-emerald-50` / `border-emerald-200`
    *   **Amarelo Âmbar (Foco / Rascunho / Pendente de Ação):** `text-amber-700` / `bg-amber-50` / `border-amber-200`
    *   **Rosa Rosé/Vermelho (Inconformidade / Alto Impacto / Nível Insatisfatório):** `text-rose-700` / `bg-rose-50` / `border-rose-150`

---

## ✍️ PAREAMENTO DE FONTES & TIPOGRAFIA

A hierarquia de textos é o elemento central do polimento estético do sistema, dividindo-se entre clareza de leitura e caráter funcional de dados:

1.  **Fonte Sans-Serif (Inter):** Utilizada em todos os elementos de leitura, formulários, títulos principais, botões e descrições.
    *   *Configuração de Peso:* `font-light` (300) para contrastes leves, `font-normal` (400) para leitura corrida, `font-medium` (500) para rótulos/labels, `font-bold` (700)/`font-extrabold` 
	(800) para títulos.
    *   *Ajuste de Tracking:* Aplicação de `tracking-tight` em cabeçalhos grandes para conferir elegância moderna "Swiss style".
2.  **Fonte Monospace (JetBrains Mono):** Elemento estético-técnico aplicado em indicadores numéricos, pontuações de notas, status e dados cronológicos.
    *   *Aplicações:* Datas de incidentes, chips de percentagem dos pesos, etiquetas de nível (`Nível 1` a `Nível 5`) e termos de sistema.

---

## 📐 GRADES, RESPONSIVIDADE & ESPAÇAMENTO (GRID)

Visando a produtividade do gestor público, as densidades de informação são flexíveis e harmônicas:

*   **Fluidity Limits:** Larguras máximas travadas em `max-w-7xl mx-auto` para evitar deformações em telas UltraWide, preservando margens generosas de tela.
*   **Bento-Grids:** Divisões em grids responsivos com gap consistente: `grid grid-cols-1 md:grid-cols-3 gap-4` — ideal para cards de servidores e sumários estatísticos.
*   **Alinhamento de Margem Interna:** Distanciamento consistente de padding nos cards com no mínimo `p-5 md:p-6` para deixar os elementos "respirarem".

---

## 🛠️ INTERATIVIDADE & COMPONENTES PADRONIZADOS

1.  **Chips de Status Compactos:**
    ```html
    <span className="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded border border-emerald-200 font-mono">
      ✓ 85/100
    </span>
    ```
2.  **Pílulas Rápidas de Configuração (Preset Pills):**
    Utilizadas em filtros de formulários e seletores rápidos. Ao estar ativo: fundo azul claro, borda azulada e texto em negrito. Ao estar inativo: borda cinza clara de baixo contraste. No hover: 
	leve escurecimento orgânico.
3.  **Visualização Gráfica de Escores BARS:**
    Réguas numéricas e âncoras alinhadas horizontalmente de `1 a 5`. O uso de marcadores dinâmicos de cor realça exatamente em qual comportamento observado o servidor se enquadra.
4.  **Scrollbars Customizados:**
    Linhas invisíveis ou extremamente finas de largura `6px`, com pegador transparente e arredondado (`rounded-full`), evitando quebras visuais e layouts datados.

---

# 🤖 PROMPT DE SISTEMA DO DESIGN SYSTEM (COPY-PASTE)

*Copie e envie o bloco abaixo para qualquer IA que for programar novas telas, novos relatórios ou novos fluxos para este ecossistema:*

```markdown
Você é o Engenheiro de Interface principal do sistema de Gestão Pública de BARS e OKRs. Sua missão é projetar novos componentes, fluxos e telas que respeitem INTEGRALMENTE a identidade visual 
estabelecida, abstendo-se de decorações genéricas, gradientes chamativos e layouts empilhados artificialmente. Seu código deve seguir os seguintes critérios estruturais estritos de Design System:

1. PALETA DE CORES E CONTRASTES:
   - Fundo da página sempre suave (use Tailwind `bg-slate-50` ou `#f8fafc`).
   - Cards e painéis principais em `bg-white` com borda fina e elegante `border border-slate-200` e cantos recortados com `rounded-xl` ou `rounded-2xl`. Sombra discreta `shadow-xs`.
   - Use uma cor de acento de alta classe: Azul Royal (`text-blue-600`, `bg-blue-50`, `border-blue-200`, `hover:bg-blue-100`).
   - Utilize cores de status semânticos para transmitir feedbacks de dados reais instantaneamente:
     • Verde Água/Esmeralda para notas completadas, ciclos concluídos e ações salvas com sucesso.
     • Amarelo Âmbar/Dourado para rascunhos, pendências e itens pendentes de confirmação ou sincronia.
     • Vermelho Rosé/Rose para incidentes negativos, atitudes críticas ou inconsistências de cadastro.

2. TIPOGRAFIA CLARA E ESTRUTURADA:
   - Font Pairings obrigatórios: "Inter" para toda a leitura estruturada, botões e labels. "JetBrains Mono" (`font-mono`) estritamente para números, porcentagens (como pesos de OKR % e BARS %),
   datas e estados técnicos/níveis.
   - Aplique sempre `tracking-tight` em títulos estilizados e headings com pesos de fonte robustos como `font-bold` ou `font-extrabold`.

3. LAYOUTS FLUIDOS E GRADES BENTO:
   - Evite aglomerar todos os controles em colunas simples lineares. Faça uso de grades modernas (`grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4`) e dividores horizontais leves (`border-slate-100` ou `border-slate-200/60`).
   - Todos os inputs de formulário devem possuir labels em caixa alta, tamanho reduzido e forte semântica (`text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block`).

4. MICROINTERAÇÕES E BOTÕES:
   - Botões principais de ação devem sobressair com elegância, utilizando cantos suavemente arredondados (`rounded-lg`), ícones alinhados em gap adequado (`flex items-center gap-2`), 
   transições suaves e estados de hover nítidos (`transition-all duration-200 cursor-pointer hover:bg-slate-150`).
   - Controles de sliders e inputs de seleção rápida devem acompanhar marcadores ativos transparentes e flutuantes para não poluir visualmente as interfaces.

Construa cada componente com absoluto rigor estético, priorizando o minimalismo, o espaço negativo adequado e a usabilidade prática de sistemas modernos.
```