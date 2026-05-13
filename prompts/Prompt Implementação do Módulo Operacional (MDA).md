# Prompt: Implementação do Módulo Operacional (MDA)

## Contexto
Projeto: Sistema MDA (ERP Eclesiástico). Este prompt descreve o escopo, funcionalidades, rotas e estrutura de banco de dados necessárias para implementar ou migrar o "Módulo Operacional" 
em outro projeto em desenvolvimento.

Use este documento como especificação para desenvolvedores ou para automatizar geração de código/DB com ferramentas de IA/infra.

---

## Objetivo
Fornecer instruções completas para implementar o Módulo Operacional, cobrindo: dashboards, relatórios semanais, radar 48h (visitantes), gestão de membros/células, organograma (hierarquia),
 consolidações, permissões RBAC e integrações com finanças e notificações.

---

## Escopo de Funcionalidades (alto nível)
- Dashboard Operacional: KPIs (total de células, membros ativos, visitantes pendentes, taxa de crescimento), cartões, lista de relatórios recentes e visão geral de células.
- Relatórios Semanais (Weekly Reports): criação, edição, listagem, filtros por hierarquia (rede/distrito/área/setor/célula) e paginação.
- Consolidação Mensal: visão agregada por célula/setor/área/distrito/rede (soma de presença, ofertas, indicadores).
- Radar 48h: monitoramento de visitantes não contactados há mais de 48 horas, registrar contato e restaurar visitantes.
- Gestão de Membros: CRUD de membros, notas pastorais, árvore de mapeamento e sincronização com relatórios.
- Gestão de Visitantes: CRUD básico, funil de atendimento, sugestão de célula para visitantes e consolidação em membro.
- Estrutura Hierárquica (Organograma): CRUD para `networks`, `districts`, `areas`, `sectors`, `cells`; ações para mover, multiplicar (criar células-filhas) e deletar com exclusão segura.
- Permissões e RBAC: checks para `operational` view e permissões por role, restrição de criação/edição conforme autoridade hierárquica.
- Integrações: sincronização de ofertas do relatório com `transactions` e integração com o módulo de remessas (malotes) para fechamento manual.
- Export/Relatórios: ações de exportação de inteligência e relatórios em CSV/Excel/PDF.

---

## Endpoints / Rotas recomendadas
(ajustar conforme convenção do projeto)

- `GET /operacional/dashboard` — Dashboard de supervisão
- `GET /reports` — Listar relatórios (filtros por hierarquia)
- `GET /reports/create` — Formulário para criar relatório semanal
- `POST /reports` — Criar relatório semanal
- `GET /reports/{report}/edit` — Editar relatório
- `PUT /reports/{report}` — Atualizar relatório
- `GET /reports/consolidation` — Visão de consolidação mensal
- `GET /radar` — Radar 48h
- `GET /radar/history` — Histórico do radar
- `POST /radar/{visitor}/contact` — Registrar contato de visitante
- `POST /visitors` — Criar visitante
- `PUT /visitors/{visitor}` — Atualizar visitante
- `POST /visitors/{visitor}/consolidate` — Consolidar visitante em membro
- `RESOURCE /cells` — CRUD de células
- `POST /hierarchy/networks` — CRUD hierarquia (networks/districts/areas/sectors/cells)
- `POST /hierarchy/multiply` — Criar células-filhas / multiplicar
- `POST /hierarchy/move` — Reorganizar nó na hierarquia


---

## Tabelas do Módulo Operacional (relacionadas)
Abaixo tabela + colunas principais (baseado no modelo existente). Confirme nas migrations do seu projeto antes de aplicar.

- `cells`
  - colunas principais: `id`, `name`, `sector_id`, `leader_id`, `parent_cell_id`, `created_at`, `updated_at`

- `networks`
  - colunas principais: `id`, `name`, `supervisor_id`, `created_at`, `updated_at`

- `districts`
  - colunas principais: `id`, `name`, `network_id`, `supervisor_id`, `created_at`, `updated_at`

- `areas`
  - colunas principais: `id`, `name`, `district_id`, `supervisor_id`, `created_at`, `updated_at`

- `sectors`
  - colunas principais: `id`, `name`, `area_id`, `supervisor_id`, `created_at`, `updated_at`

- `members`
  - colunas principais (extracto do `Member` model):
    `id`, `name`, `cpf`, `email`, `role`, `contact`, `birth_date`, `address_street`, `address_number`, `address_complement`, `address_neighborhood`, `city`, `address_state`, `zip_code`, `latitude`, `longitude`, `baptism_date`, `first_visit_at`, `consolidated` (boolean), `discipler_id`, `mentor_id`, `cell_id`, `status`, `member_type`, `trilho_lideranca` (json), `is_active` (boolean), `user_id`, `portal_access_status`, `created_at`, `updated_at`

- `visitors`
  - colunas principais (extracto do `Visitor` model):
    `id`, `name`, `inviter_name`, `phone`, `cell_id`, `address_street`, `address_neighborhood`, `city`, `latitude`, `longitude`, `visited_at` (datetime), `contact_status`, `contacted_at`, `contacted_by_user_id`, `contact_notes`, `consolidated` (boolean), `visits_count`, `contacts_count`, `created_at`, `updated_at`

- `visitor_contacts`
  - colunas principais: `id`, `visitor_id`, `user_id`, `status`, `notes`, `type`, `was_visited`, `created_at`, `updated_at`

- `weekly_reports`
  - colunas principais (extracto do `WeeklyReport` model):
    `id`, `cell_id`, `meeting_date`, `word_theme`, `meeting_location`, `committed_members`, `present_members`, `visitors`, `children`, `other_cell_visitors`, `house_of_peace`, 
	`mdas_done`, `kg_of_love`, `reconciliations`, `conversions`, `offer_pix`, `offer_cash`, `notes`, `present_member_ids` (json), `visitor_names` (json), `created_at`, `updated_at`

- `transactions` (integração financeira)
  - colunas relevantes: `id`, `weekly_report_id`, `cell_id`, `description`, `amount`, `type` (credit/debit), `payment_method` (pix/cash), `status`, `date`, `user_id`, `financial_remittance_id`,
  `created_at`, `updated_at`

- `users`, `roles`, `menus`, `notifications` — tabelas de suporte/infra que interagem com o módulo operacional (controle de acessos, menus dinâmicos, notificações internas).

---

## Observações Técnicas / Regras de Negócio Importantes
- Apenas Líderes de célula e Admins podem criar/editar `weekly_reports` para sua célula.
- O `meeting_date` deve ser único por `cell_id` (uma regra de negócio: não permitir mais de um relatório por célula na mesma data).
- Relatórios geram transações (PIX/ESPÉCIE) via sincronização com `transactions`, mas NÃO encerram automaticamente malotes; fechamento do malote é ação manual.
- Radar 48h: visitantes com `contact_status = 'pending'` e `visited_at <= now() - 48h` devem ser sinalizados como críticos.
- Hierarquia deve suportar movimentação de nós mantendo integridade referencial (mover não deve duplicar dados).
- Controle de acesso hierárquico: supervisores só veem dados dentro de sua jurisdição (lista de `cell_id` supervisionadas pelo usuário).

---

## UX / Telas (referência)
- `Operational/Dashboard` — Vue/Inertia: KPIs, cartões, relatórios recentes, visão de células.
- `Supervision/Index` — Visão macro da rede: métricas consolidadas, ranking de células.
- `Reports/Index`, `Reports/Submit`, `Reports/Consolidation` — CRUD e consolidação.
- `Radar/Index` — Lista de visitantes críticos e ações de contato.

---

## Critérios de Aceitação
- Endpoints implementados retornam dados filtrados por hierarquia corretamente.
- Usuários com permissões insuficientes recebem 403 em ações proibidas.
- Criação/edição de relatórios valida `meeting_date` único por célula.
- Consolidação mensal retorna métricas agregadas consistentes com os relatórios semanais.
- Radar identifica corretamente visitantes com >48h sem contato.

---

## Como usar este prompt
1. Copie este arquivo para o projeto alvo.
2. Ajuste nomes de tabelas/colunas se necessário (ver migrations do projeto).
3. Siga as rotas e models como guia para implementar controllers, services e views.
4. Testes recomendados: cobertura de autorização, integridade de consolidação e sincronização financeira.

---

Se desejar, posso gerar:
- um conjunto de migrations SQL para as tabelas listadas (esqueleto);
- controllers + services esqueleto (Laravel) com validações e regras de negócio;
- especificação OpenAPI mínima para os endpoints acima.

Escolha o próximo passo que prefere que eu gere automaticamente.