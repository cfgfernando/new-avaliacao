# Prompt Frontend Genérico — Módulo Célula (Pronto para Stitch)

Objetivo: fornecer uma especificação de UI em linguagem neutra (sem citar frameworks, bibliotecas ou tecnologias) para gerar telas do módulo de gestão de Células. Este prompt deve ser usado em um 
gerador visual como o Stitch para produzir telas funcionais conforme o contrato.

Público-alvo: gerador de telas (Stitch) ou equipe de design/front-end; não mencione tecnologias específicas.

---

Resumo do módulo

- Função: permitir a gestão de unidades organizacionais chamadas “Células”, incluindo listagem, visualização detalhada, criação, edição e exclusão.
- Usuários: administradores, supervisores e líderes; cada papel tem permissões distintas (ver, criar, editar, excluir).

---

Dados esperados (contrato de props / API)

- Lista de células (`/cells`): array de objetos com as propriedades
  - `id` (identificador)
  - `name` (nome da célula)
  - `sector` (nome do setor/área)
  - `leader` (nome do líder, ou nulo)
  - `members_count` (inteiro)
  - `reports_count` (inteiro)
  - `meeting_day` (string, ex: "Domingo")
  - `meeting_time` (string HH:MM)
  - `address` (string)
  - `is_active` (boolean)
  - `whatsapp_group` (URL ou null)

- Detalhe da célula (`/cells/{id}`): objeto com campos acima +
  - `description` (texto)
  - `avg_attendance` (número)
  - `members` (array de objetos simplificados)
  - `reports` (array de relatórios recentes)

- Endpoints esperados (nomes abstratos):
  - GET `/cells` → lista
  - GET `/cells/{id}` → detalhe
  - POST `/cells` → criar
  - PUT `/cells/{id}` → atualizar
  - DELETE `/cells/{id}` → excluir

---

Páginas e componentes a gerar

- Página: Lista de Células
  - Topo com KPIs: total de células, células ativas, total de membros.
  - Filtros: busca por nome/líder, filtro por setor, filtro por status (Ativa/Inativa).
  - Grid de cartões: cada cartão mostra nome, setor, líder, badges de membros/relatórios, dia/hora de reunião e ações (Ver, Editar, Excluir).
  - Estados: loading, vazio (empty state), erro de carregamento.

- Página: Detalhe da Célula
  - Cabeçalho com nome, setor, badge de status, ação para entrar no grupo de WhatsApp (se houver), botão Editar.
  - Seções: informações básicas, médias (presença média), lista de membros com status, lista de relatórios recentes.
  - Ações contextuais: chamar líder, enviar mensagem para grupo, navegar para relatório.

- Página: Criar / Editar Célula (formulário)
  - Campos: nome, setor (select), líder (select opcional), endereço, latitude, longitude, dia reunião, hora reunião, descrição, link do grupo, ativo/inativo.
  - UX: validação por campo com mensagens claras; indicar campos obrigatórios; feedback visual após submissão (sucesso/erro).
  - Comportamento: no modo edição, preencher campos com os valores atuais; botão Cancelar volta à lista; botão Salvar envia dados ao endpoint apropriado.

- Componente: Modal de confirmação para exclusão
  - Texto de aviso explicando que membros serão desvinculados ao excluir.
  - Botões: Confirmar (excluir) e Cancelar.

---

Interações e regras UX

- Proteções de permissão: ocultar ou desabilitar ações incompatíveis com o papel do usuário (por exemplo, botão Criar para perfis sem permissão).
- Exclusão: requer confirmação explícita no modal; ao confirmar, disparar solicitação de exclusão e mostrar indicador de progresso.
- Busca e filtros: aplicar em cliente quando a lista estiver carregada; permitir combinar filtros.
- Feedback visual: mostrar banners de sucesso/erro após operações (criar/editar/excluir).
- Acessibilidade: garantir navegação por teclado, labels descritivos, contraste adequado e aria-attributes para componentes dinâmicos.
- Responsividade: layout adaptável para tela pequena (cards empilhados) e grande (grid de colunas).

---

Estados e validações

- Loading: exibido enquanto dados são carregados; bloquear ações relevantes até completar.
- Empty state: exibir mensagem e CTA para criação de nova célula quando a lista estiver vazia.
- Validations (servidor/cliente):
  - `name` — obrigatório, texto até 255 caracteres.
  - `sector` — obrigatório (seleção de lista).
  - `meeting_time` — formato válido de hora (HH:MM) quando informado.
  - `latitude`/`longitude` — numéricos quando informados.
  - Mensagens de erro devem ser apresentadas ao lado dos campos.

---

Estética e consistência (orientações)

- Use uma hierarquia visual clara: títulos fortes para cabeçalhos, subtítulos para meta-informações e tipografia legível para listas.
- Botões de ação devem ter distinção clara (primário para ações principais, secundário para ações auxiliares, perigo para exclusão).
- Cartões de item devem conter ações rápidas e um link para a página de detalhe.

---

Prompt para Stitch (copiar/colar)

"Contexto: Gere as telas para o módulo de gestão de Células conforme o contrato abaixo. Não mencionar ou assumir frameworks ou bibliotecas específicas — entregue telas e componentes em termos de telas,
 componentes visuais, estados e interações.

Contrato/props:
- Lista: array de objetos com `id,name,sector,leader,members_count,reports_count,meeting_day,meeting_time,address,is_active,whatsapp_group`.
- Detalhe: objeto com campos listados e arrays `members` e `reports`.

Especificações de telas a gerar:
- Tela Lista de Células com KPIs, filtros, grid de cartões, estados (loading, empty, error) e ações (ver, editar, excluir com confirmação).
- Tela Detalhe da Célula com informações, lista de membros e relatórios recentes, ações contextuais (editar, abrir grupo).
- Tela de Formulário (Criar/Edit) com validação por campo, feedback, e comportamentos de sucesso/erro.

Regras de interação:
- Ocultar/desabilitar ações conforme permissões do usuário.
- Confirmação necessária para exclusão; mostrar progresso durante operação.
- Buscar e filtrar client-side quando aplicável.
- Fornecer estados acessíveis e responsivos.

Entrega esperada do Stitch:
- Estruturas de tela (layouts), componentes reutilizáveis (Card, Form, Modal, List), especificações de props e eventos para cada componente, e texto das mensagens UX (labels, placeholders, validações, 
confirmações)."

---

Observação: este arquivo é neutro em tecnologia por propósito — execute-o no Stitch para gerar automaticamente as telas.

Fim do documento.
