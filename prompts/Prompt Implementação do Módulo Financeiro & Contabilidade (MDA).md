# Prompt: Implementação do Módulo Financeiro & Contabilidade (MDA)

## Contexto
Projeto: Sistema MDA (ERP Eclesiástico). Este prompt especifica o escopo, endpoints, regras de negócio e estrutura de banco de dados para implementar ou migrar o Módulo Financeiro e Contábil
 em outro projeto.

Use este documento como especificação para desenvolvedores ou para automações (geração de código, infra, migrations).

---

## Objetivo
Descrever todas as funcionalidades do Módulo Financeiro & Contabilidade, incluindo operações de receita/despesa, conciliação bancária, remessas (malotes), tesouraria, PDV (POS), ativos imobilizados, 
fechamento contábil, relatórios contábeis (BP/DMPL/DMPL/SPED), configurações e integrações com módulos operacionais (relatórios de célula) e notificações.

---

## Funcionalidades Principais
- Painel Financeiro: KPIs (saldo por conta, fluxo de caixa, receitas x despesas, A/P vencidas), gráficos de tendência e atalhos para operações críticas.
- Gestão de Receitas (Income): cadastro, edição, cancelamento de entradas; categorias, meios de pagamento (pix, cash, card), vinculação a célula/relatório semanal (`weekly_reports`) e originadores 
(doadores, eventos).
- Gestão de Despesas (Expense): solicitação, aprovação (workflow), pagamento e estorno; categorias, fornecedores (suppliers) e centros de custo.
- Transações Genéricas (`transactions`): modelo único para representar receitas, despesas, transferências internas e ajustes contábeis; campos para `payment_method`, `type` (credit/debit), `status`, 
`date`, `amount`, `category`, `description`.
- Remessas / Malotes (Financial Remittance): criar malotes, adicionar transações, registrar entrega/recebimento, aceite/recusa, integração com logística de coleta; malotes podem permanecer em
 `pending_delivery` até confirmação.
- Conciliação Bancária: upload de extratos (OFX/CSV), parser/importador, sugestão de match automático com `transactions`, regras de reconciliação manual e relatórios de discrepância.
- PDV / POS: registrar vendas/donativos no ponto de atendimento; integração com `transactions` e geração de recibos.
- Tesouraria: transferências entre contas, fechamento de caixa, relatórios por campus (cathedral/nucleus), dashboard de tesouraria por unidade.
- Configurações Financeiras: CRUD para `banks`, `financial_accounts`, `chart_of_accounts`, `campuses`, `closures` (fechamentos mensais); controlar permissões para criação/alteração.
- Contabilidade / Relatórios Contábeis: geração de Balanço Patrimonial (`bp`), Demonstração do Resultado e do Patrimônio Líquido (`dmpl`), integração / export SPED fiscal/contábil e relatórios 
de recebíveis/pagas.
- Ativo Imobilizado: cadastro de ativos, cálculo de depreciação (manual/executar job), baixa, relatório de movimentação de ativos.
- Históricos e Auditoria: log de alterações em transações, remessas e fechamentos; rastreabilidade de quem aprovou/fechou.
- Exportação e Integração: export CSV/Excel/PDF e endpoints para integração com contabilidade externa.
- Notificações: notificações internas (ex.: malote pendente, conciliações com discrepância, fechamento pendente).

---

## Endpoints / Rotas recomendadas
(Ajustar convenções conforme o projeto alvo)

- `GET /finance` — Dashboard financeiro
- `GET /finance/income` — Listar receitas
- `POST /finance/income` — Criar receita
- `PUT /finance/income/{id}` — Atualizar receita
- `DELETE /finance/income/{id}` — Excluir/estornar receita

- `GET /finance/expense` — Listar despesas
- `POST /finance/expense` — Criar despesa
- `PUT /finance/expense/{id}` — Atualizar despesa
- `POST /finance/expense/{id}/settle` — Liquidar despesa

- `GET /finance/transactions` — Listar transações (filtros por conta, data, tipo)
- `POST /finance/transactions/transfer` — Transferência entre contas

- `GET /finance/remittance` — Listar malotes
- `POST /finance/remittance` — Criar malote
- `POST /finance/remittance/{remittance}/receive-cash` — Registrar recebimento físico
- `POST /finance/remittance/{remittance}/accept-consolidation` — Aceitar consolidação

- `GET /finance/reconciliation` — Tela de conciliação
- `POST /finance/reconciliation/upload` — Upload de extrato bancário
- `POST /finance/reconciliation/reconcile` — Reconciliar transações

- `GET /finance/pos` — Tela POS / registrar vendas
- `POST /finance/pos` — Registrar venda/recebimento no PDV

- `GET /finance/assets` — Listar ativos
- `POST /finance/assets` — Criar ativo
- `POST /finance/depreciation/run` — Rodar processo de depreciação

- Relatórios contábeis:
  - `GET /finance/bp` — Balanço Patrimonial
  - `GET /finance/dmpl` — DMPL
  - `GET /finance/sped/export` — Export SPED
  - `GET /finance/receipts` — Emissão/visualização de recibos

- Configurações:
  - `GET /finance/settings` — Tela de settings
  - `POST /finance/settings/banks` — Criar banco
  - `POST /finance/settings/accounts` — Criar conta financeira
  - `POST /finance/settings/chart-accounts` — Criar conta contábil
  - `POST /finance/settings/closures` — Criar fechamento
  - `PUT /finance/settings/closures/{closure}/unlock` — Reabrir fechamento

---

## Tabelas Principais e Colunas Sugeridas
(Confirme com as migrations do projeto alvo; abaixo são esboços baseados no projeto MDA.)

- `transactions`
  - `id`, `weekly_report_id` (nullable), `cell_id` (nullable), `description`, `amount` (decimal), `type` (enum: credit/debit/transfer/adjustment), `category`, `payment_method` (pix/cash/card/transfer), 
  `status` (pending/settled/cancelled), `date`, `user_id`, `financial_remittance_id` (nullable), `created_at`, `updated_at`

- `financial_remittances` (malotes)
  - `id`, `reference_code`, `sender_id` (user), `receiver_id` (user), `status` (pending_delivery, in_transit, received, cancelled), `amount_total`, `campus_id`, `delivery_date`, `received_at`, `notes`,
  `created_at`, `updated_at`

- `remittance_items` (pivot transactions <-> remittances)
  - `id`, `financial_remittance_id`, `transaction_id`, `amount`, `created_at`, `updated_at`

- `banks`
  - `id`, `name`, `code`, `agency`, `account_number`, `created_at`, `updated_at`

- `financial_accounts`
  - `id`, `bank_id`, `campus_id`, `name`, `account_type` (cash/bank/treasury), `currency`, `balance`, `created_at`, `updated_at`

- `suppliers`
  - `id`, `name`, `document` (CNPJ/CPF), `contact`, `bank_account_info`, `created_at`, `updated_at`

- `chart_of_accounts`
  - `id`, `code`, `name`, `type` (asset, liability, equity, revenue, expense), `parent_id`, `created_at`, `updated_at`

- `closures` (fechamentos mensais)
  - `id`, `year`, `month`, `status` (closed/open/locked), `locked_by_user_id`, `locked_at`, `created_at`, `updated_at`

- `fixed_assets`
  - `id`, `name`, `serial`, `purchase_date`, `purchase_value`, `useful_life_months`, `residual_value`, `depreciation_method`, `current_value`, `location`, `status`, `created_at`, `updated_at`

- `bank_statements` (importados)
  - `id`, `bank_id`, `file_name`, `period_start`, `period_end`, `imported_by_user_id`, `parsed` (json), `created_at`, `updated_at`

- `financial_receipts` / `donation_receipts`
  - `id`, `transaction_id`, `receipt_number`, `generated_by_user_id`, `issued_at`, `pdf_path`, `created_at`, `updated_at`

- `users`, `roles`, `notifications`, `menus` — suporte a permissões e alertas.

---

## Regras de Negócio e Validações
- Transações vinculadas a `weekly_reports` devem preservar integridade: alteração de relatório atualiza transações (sincronização), mas se transação já estiver vinculada a um `financial_remittance`
 com status `pending_delivery`, comportamento definido (ex.: desvincular ou bloquear alteração).
- Apenas usuários com permissão financeira (papéis) podem criar/autorizar pagamentos e fechar malotes.
- Fechamentos mensais (`closures`) bloqueiam lançamentos posteriores para o período; devem existir endpoints para reabrir (`unlock`) com log de auditoria.
- Conciliação automática: preferências configuráveis (margem de tolerância, matching por valor+data+descrição), e possibilidade de reconciliação manual.
- Movimentações entre contas atualizam saldos de `financial_accounts` imediatamente em transação atômica.
- PDV registra transação e gera recibo; permite cancelamento com usuário/justificativa.
- Depreciação: job agendado ou endpoint para execução; gerar lançamentos contábeis de depreciação.

---

## Integrações com Outros Módulos
- `weekly_reports` → ao criar/atualizar relatório, sincronizar `offer_cash` e `offer_pix` como `transactions` (como no projeto MDA).
- `members` / `cells` → transações podem ser associadas a `cell_id` e a user/leader.
- `notifications` → alertas para malotes pendentes, conciliações com diferenças e fechamentos pendentes.
- Exportadores / APIs → endpoints para exportação SPED e integração com sistemas contábeis externos.

---

## UX / Telas (referência)
- `Finance/Dashboard` — KPIs, gráfico de fluxo, saldo por conta e pendências.
- `Finance/Transactions` — listagem com filtros avançados, detalhes e ações (reconciliar, estornar).
- `Finance/Remittance` — criar malote, adicionar transações, acompanhar entrega/recebimento.
- `Finance/Reconciliation` — upload de extrato, sugestões de correspondência, painel de conferência.
- `Finance/Settings` — bancos, contas, chart-of-accounts, fechamentos.
- `Finance/Assets` — listagem e gestão de ativo imobilizado, cálculo de depreciação.

---

## Critérios de Aceitação
- Lançamentos criados via UI/API aparecem corretamente em `transactions` e atualizam saldos.
- Conciliação identifica e permite resolver automaticamente >= 70% dos lançamentos com regras definidas.
- Malotes (`financial_remittances`) podem ser criados, receber transações e mudar status (`pending_delivery` → `received`) com trilha de auditoria.
- Fechamento contábil (`closures`) bloqueia criação/edição de transações no período, e reabertura exige autenticação/justificativa.
- Relatórios contábeis (BP/DMPL) geram dados compatíveis com os lançamentos.

---
