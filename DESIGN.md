# Design Doc: Sistema de Gestão de Desempenho Público (BARS & OKR)

**Autor:** Carlos Fernando Gomes  
**Status:** `Aprovado`  
**Data:** 2026-05-23  
**Revisores:** [Lista de Revisores]

---

## 1. Contexto e Problema (Context & Scope)

### 1.1 Histórico
A administração pública exige um sistema de avaliação de desempenho que combine rigor técnico (BARS) com alinhamento estratégico (OKR). Sistemas SaaS tradicionais falham em atender requisitos de 
soberania de dados e latência em redes governamentais limitadas.

### 1.2 Escopo
Este documento descreve a arquitetura e os padrões de interface do sistema rodando sob a infraestrutura **Antigravity** (Local Single-Tenant).

---

## 2. Objetivos (Goals)

*   **Integridade de Dados:** Garantir que 100% dos registros de avaliação sejam imutáveis após a assinatura.
*   **Performance Local:** Tempo de resposta da interface inferior a 100ms (latência zero percebida).
*   **Independência de Rede:** Funcionamento total em ambientes offline ou redes restritas (Zero-CDN).
*   **Padronização Visual:** Garantir consistência técnica e ergonômica para auditores e gestores.

---

## 3. Não-Objetivos (Non-Goals)

*   **Multi-tenancy:** O sistema não será construído para rodar como um serviço compartilhado (SaaS público).
*   **Integração com Redes Sociais:** Não haverá autenticação via provedores externos (OAuth público).
*   **Customização pelo Usuário:** O layout e as cores são fixos para manter a conformidade legal e técnica.

---

## 4. Design Proposto (Proposed Design)

### 4.1 Arquitetura de Software
O sistema utiliza o **Antigravity Core**, uma arquitetura *Single-Tenant* onde cada instância é isolada. O frontend é construído com Tailwind CSS para garantir que o CSS final seja otimizado e 
servido localmente.

### 4.2 Modelo de Avaliação
1.  **BARS (Behaviorally Anchored Rating Scales):** Implementação de escala 1-5 baseada em evidências comportamentais.
2.  **OKRs (Objectives and Key Results):** Monitoramento de metas com atualização via cronologia de incidentes.

---

## 5. Especificações Técnicas e de UI (Detailed Design)

### 5.1 Identidade Visual (Design Tokens)
*   **Fundo:** `bg-slate-50` (Slipped Light Slate) para redução de fadiga.
*   **Superfície:** `bg-white` (Card Canvas) com bordas `border-slate-200`.
*   **Tipografia:** 
    *   *Inter:* Leitura administrativa e labels.
    *   *JetBrains Mono:* Dados técnicos, percentuais e identificadores únicos.

### 5.2 Componentes de Alta Densidade
Os componentes devem ser projetados para exibir o máximo de informação sem ruído visual:
*   **Chips de Status:** `<span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded border">`
*   **Inputs:** Rótulos em `uppercase tracking-widest` para estética técnica.

### 5.3 Restrições de Assets
*   **Fontes:** Devem ser servidas localmente em formatos WOFF2.
*   **Ícones:** SVGs inline ou fontes de ícones locais para evitar requisições externas.

---

## 6. Segurança e Privacidade (Security & Privacy)

*   **LGPD (Brasil):** O armazenamento local garante que os dados sensíveis dos servidores não saiam do perímetro da instituição.
*   **Auditoria:** Cada alteração em um OKR ou nota BARS gera um log de auditoria assinado digitalmente dentro da instância Antigravity.

---

## 7. Alternativas Consideradas (Alternatives Considered)

*   **Arquitetura Cloud (SaaS):** Rejeitada devido à dependência de latência e riscos de conformidade com dados de servidores públicos.
*   **Componentes de UI Genéricos (Material Design):** Rejeitados por serem excessivamente volumosos e não otimizados para a densidade de dados necessária em auditorias.

---

## 8. Cross-Cutting Concerns (Desempenho e Observabilidade)

*   **Monitoramento:** O sistema deve expor métricas de performance locais para o Antigravity Dashboard.
*   **Acessibilidade:** Conformidade com WCAG 2.1, garantindo alto contraste nas cores semânticas (Azul Real para ações, Esmeralda para sucesso).

---

### Apêndice: Tabela de Cores Semânticas

| Status | Aplicação | Classe Tailwind |
| :--- | :--- | :--- |
| Ativo | Botões/Foco | `blue-600` |
| Concluído | Sucesso | `emerald-700` |
| Pendente | Atenção | `amber-700` |
| Crítico | Erros/BARS Nível 1 | `rose-700` |