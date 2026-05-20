# Prompt de Design System - MDA Church

Este documento define as diretrizes visuais e tokens de design para o desenvolvimento do sistema MDA Church. Utilize estas definições para garantir consistência em todos os componentes e telas.

## 1. Identidade Visual e Cores

A paleta de cores é focada em uma estética corporativa e moderna, utilizando tons profundos para estrutura e cores vibrantes para ações e estados.

### Paleta Principal (Primary)
- **Primary Dark:** `#1c2434` (Utilizada em fundos de sidebar, cabeçalhos e textos principais)
- **Primary Soft:** `#1351b4` (Cor institucional principal para botões e links)

### Cores de Suporte e Estados
- **Orange (Action):** `#ff9c00` (Utilizada para destaques, notificações e botões de ação secundária)
- **Green (Success):** `#10b981` (Status "LIDO", validações positivas e indicadores de crescimento)
- **Purple (Support):** `#8b5cf6` (Ícone de "Atendimento" e categorias específicas)
- **Red (Danger):** `#ef4444` (Erros, exclusões e alertas críticos)

### Superfícies e Neutros (Surface)
- **Surface:** `#FFFFFF` (Fundo principal das páginas)
- **Surface Dim:** `#d6dade` (Bordas e divisores sutis)
- **Surface Container Low:** `#f0f4f8` (Fundo de cards e áreas de conteúdo secundário)

## 2. Tipografia

O sistema utiliza a família tipográfica **Libre Franklin** para toda a interface.

- **Headlines:** Peso Bold para títulos de seção.
- **Body:** Peso Regular para textos de leitura e Labels.
- **Escala:** Utilizar hierarquia visual clara entre títulos, subtítulos e corpo de texto para manter a legibilidade em dispositivos móveis.

## 3. Componentes e Estrutura (Layout)

### Bordas e Arredondamento
- **Roundness:** `ROUND_EIGHT` (8px). Aplicado a botões, inputs e cards para um visual amigável, porém profissional.

### Navegação
- **Sidebar (Desktop/Mobile):** Fundo em `#1c2434` com itens ativos em `#ff9c00` ou com variação de opacidade.
- **Bottom NavBar (Mobile):** Fundo claro com ícones e labels definidos na paleta Primary.

## 4. Diretrizes de UX
- **Consistência:** Todos os formulários (como Criar/Editar Célula) devem seguir o padrão de validação inline.
- **Espaçamento:** Utilizar margens generosas para evitar cortes de componentes em telas mobile.
- **Wizard:** Fluxos de etapas (Relatórios) devem possuir indicadores de progresso claros.

---
**Instrução para IA:** Ao gerar novos códigos ou componentes, aplique estas classes e cores rigorosamente para manter a fidelidade ao projeto MDA Church.