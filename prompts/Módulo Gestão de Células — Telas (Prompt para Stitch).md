# Módulo Gestão de Células — Telas (Prompt para Stitch)

## Contexto
- Módulo: Gestão de Células do Sistema MDA (ERP Eclesiástico).
- Objetivo do prompt: gerar designs de telas para todas as views do módulo, com foco em UX mobile-first e componentes reutilizáveis.
- Público: designers de interface e desenvolvedores frontend.

## Objetivo
Gerar no Stitch os designs das telas listadas abaixo, com variações responsivas (mobile/tablet/desktop) e estados (vazio, carregando, erro, sucesso).

## Telas Principais

### Dashboard de Células
- Objetivo: visão consolidada rápida das células sob responsabilidade do usuário.
- Elementos: KPI cards (nº de células, membros ativos, presença média, arrecadação semanal), gráfico de tendência, lista de alertas (relatórios pendentes), atalho "Criar Relatório".
- Interações: filtros por período/região, clicar em card abre detalhe da célula, refresh manual e auto-refresh breve.

### Lista de Células
- Objetivo: localizar e gerenciar células.
- Elementos: barra de busca com filtros (nome, líder, região, status), tabela/lista com avatar, nome, líder, membros ativos, próxima reunião, ações (editar, desativar, abrir).
- Ações em lote: selecionar múltiplas células, atribuir líder, exportar CSV.
- Estados: vazio (CTA para criar), carregando, filtro aplicado.

### Detalhe da Célula
- Objetivo: visão completa da célula selecionada.
- Seções: cabeçalho (nome, código, status, mapa), contatos & líderes, resumo de KPIs (presença média, últimos relatórios, arrecadação), timeline de reuniões/atividades, membros em destaque.
- Ações rápidas: abrir Wizard de Relatório, marcar presença em massa, transferir membro, gerar remessa financeira.
- Modais: editar membro rápido, confirmar desativação.

### Criar / Editar Célula (Formulário)
- Objetivo: criar ou atualizar dados mestres da célula.
- Campos: nome, código, endereço (com auto-geocoding), líder, supervisor, telefone, horário de reunião, avatar, tags, observações.
- UX: validação inline, preview de localização em mapa, upload de imagem com crop, auto-sugestões de líderes.
- Estados: salvando, sucesso com toast, erro com foco no campo.

### Gestão de Membros (Lista da Célula)
- Objetivo: visualizar e gerenciar membros vinculados à célula.
- Elementos: busca com debounce, filtros por papel/status, lista com avatar, nome, papel, presença recente, ações (perfil, marcar presença, transferir, remover).
- Ações rápidas: Marcar Todos / Limpar Todos, importar CSV, convidar por link.
- Estados: membros inativos agrupados, paginação/infinite scroll.

### Perfil do Membro
- Objetivo: ficha completa do membro.
- Seções: dados pessoais, contato, papel histórico, presença (gráfico), notas/observações, documentos (uploads), histórico de transferências.
- Ações: editar perfil, promover/demote papel, marcar presença individual, enviar mensagem.

### Wizard: Relatório Semanal (Submit)
- Objetivo: fluxo guiado para criar/editar relatório semanal da célula (5 etapas).
- Tela global: header com progress bar, step indicator, animações suaves, footer de ações (voltar/continuar/finalizar).
  - Step 1 — Informações Gerais: meeting_date, word_theme, meeting_location, observações iniciais. Validação: campos obrigatórios.
  - Step 2 — Presença: grid de membros com checkboxes, estatísticas (cadastrados/presentes/ausentes), busca e "Marcar Todos".
  - Step 3 — Visitantes: adicionar visitantes (nome, contato opcional), chips editáveis, contadores de crianças/outros visitantes.
  - Step 4 — Finanças & Social: inputs monetários (offer_pix, offer_cash), social metrics (kg_of_love, casas_de_paz, mdas_done) e cartão com total consolidado.
  - Step 5 — Resumo Final: resumo dos dados, botões para ajustar passos anteriores e textarea de notes.
- Tela de Sucesso: animação, cartões com Total Presentes e Total Oferta, botões "Novo Relatório" e "Ir ao Dashboard".
- UX extras: auto-save rascunho local, tratamento de erros server-side (422) mostrando mensagens por campo e mantendo usuário na etapa correta.

### Relatórios / Histórico
- Objetivo: listar e filtrar relatórios semanais.
- Elementos: tabela com data, líder, total presentes, total oferta, status (conciliado/pendente), ações (ver, editar, exportar).
- Filtros: período, célula, valor mínimo, presença mínima.
- Ações: exportar PDF/CSV, bulk delete (soft), comparar semanas.

### Transações Vinculadas
- Objetivo: visualizar transações geradas a partir de relatórios.
- Elementos: lista de transações (pix/espécie), status (pending, reconciled), link para malote/ remessa, detalhes (descrição, amount, date).
- Ações: reconciliar, desvincular de malote, ver histórico do remittance.

### Notificações & Tarefas
- Objetivo: centralizar alertas relacionados às células.
- Elementos: inbox/lista com ícones, filtros por tipo (relatório pendente, remessa pendente, visitante convertido).
- Ações: marcar como lido, abrir recurso relacionado, criar tarefa.

### Configurações & Permissões (Tela Admin)
- Objetivo: gerenciar papéis e escopos.
- Elementos: lista de papéis, mapeamento de permissões por ação, atribuição de líder/supervisor a células, visibilidade por região.
- Ações: editar regras, auditar logs de permissão.

### Import / Export
- Objetivo: importar membros e exportar relatórios/dados.
- Telas: assistente de importação (upload, mapear colunas, preview, aplicar), tela de exportação (seleção de campos, formato, agendar).
- UX: validação de arquivo, preview de erros, feedback detalhado pós-import.

### Modais e Componentes de Interação
- ConfirmModal: confirmação destrutiva (ex.: deletar, desativar).
- QuickEdit Modal: edição rápida de campos do membro ou célula.
- Map Picker: selecionar endereço com geocoding.
- Upload/Image Cropper: para avatar e documentos.
- Toast/Alert Center: exibir feedbacks instantâneos.

### Estados e Variantes de UI
- Estados: vazio, carregando, erro, sucesso, confirmação, passo do wizard.
- Variantes: mobile (stacked), tablet, desktop (grid com sidebars).

### Recomendações para Stitch
- Gerar telas mobile-first e versões responsivas (mobile/tablet/desktop).
- Priorizar fluxos: Wizard de Relatório e Lista/Detalhe da Célula.
- Produzir assets: components list, tokens de UI (cores, tipografia, espaçamentos).
- Incluir variações de estados (erro, loading, vazio) e microinterações (transições de etapa e toasts).

---

Próximo passo: revisar o arquivo e me dizer se quer que eu gere uma versão compacta (resumo técnico) ou crie tickets de implementação.
