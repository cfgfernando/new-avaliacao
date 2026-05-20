# 🧹 Comando de Purga Avançada: Higienização de Rotas, Arquivos e Banco de Dados

Atue como um Engenheiro de Software Sênior e Administrador de Banco de Dados (DBA) especialista no ecossistema Laravel. O objetivo desta tarefa é rodar uma limpeza cirúrgica e radical no projeto para 
transformá-lo no nosso **Boilerplate/Template Padrão**. 

Com base no arquivo `routes/web.php` atual, mapeamos o que é core (essencial) e o que pertence ao modelo de negócio antigo (igrejas/células e finanças/contabilidade eclesiástica). Você deve gerar os 
comandos e códigos para eliminar tudo o que for excedente.

---

## 🎯 1. Escopo de Preservação Absoluta (O que DEVE ficar)

Apenas os seguintes elementos e rotas devem permanecer no ecossistema:
- **Autenticação:** Toda a estrutura nativa do Breeze (`require __DIR__.'/auth.php';`).
- **Dashboard Principal:** Rota raiz `/` redirecionando para `dashboard`, e a rota `dashboard` vinculada ao controle principal.
- **Perfil do Usuário:** Rotas `profile.edit`, `profile.update` e `profile.destroy`.
- **Administração Core (Menu Lateral Base):**
  - **Gerenciar Menus:** Categorias de menus e itens de menus (`\App\Http\Controllers\Admin\MenuController`).
  - **Usuários:** CRUD completo administrativo (`\App\Http\Controllers\Admin\UserController`).
  - **Perfis (Roles):** Ordenação e CRUD (`\App\Http\Controllers\Admin\RoleController`).
  - **Permissões (Permissions):** CRUD granular (`\App\Http\Controllers\Admin\PermissionController`).
  - **Logs de Sistema (Auditoria):** Roteamento de logs customizados (`\App\Models\AuditLog`) e auditoria forense existente (`AuditController`).

---

## 🚫 2. Lista de Elementos a Purgar (O que DEVE sumir)

Remova completamente os seguintes controladores, subpastas de views, regras de negócio e referências:
1. **Módulo de Células, Membros e Visitantes:** `CellController`, `MemberController`, `VisitorController`.
2. **Módulo Financeiro Antigo & ERP V8:** `TransactionController`, `IncomeController`, `ExpenseController`, `FinancialAccountController`, `FinancialBatchController`, `ChartOfAccountController`, 
`CostCenterController`, `SupplierController`, `BankController`, `UnitController`, `FinanceDashboardController`, `FinanceSettingController`.
3. **Módulo Contábil & Inteligência:** `AccountingDashboardController`, `TrialBalanceController`, `IncomeStatementController`, `BalanceSheetController`, `ApprovalController`, `ReconciliationController`, 
`ManualEntryController`, `PDFReportController`.
4. **Módulo Operacional/Relatórios Semanais:** `WeeklyReportController`, `ReportController`, rotas de `hierarchy`, `consolidation` e `radar` dentro do `OperacionalController`.

---

## 🛠️ 3. Instruções de Execução Requeridas

Analise o cenário acima e gere o plano de ação técnico estruturado em:

### 3.1. Script de Migração para Limpeza do Banco de Dados (MySQL)
Gere uma migration isolada do Laravel (ou comandos SQL brutos) contendo o método `down()` ou um script de limpeza estruturado usando `Schema::dropIfExists` para remover todas as tabelas do negócio 
antigo, incluindo:
- Tabelas operacionais: `cells`, `members`, `visitors`, `weekly_reports`, `visitor_contacts`, etc.
- Tabelas financeiras/contábeis: `transactions`, `incomes`, `expenses`, `financial_accounts`, `financial_batches`, `chart_of_accounts`, `cost_centers`, `journal_entries`, `journal_entry_items`, 
`fixed_assets`, `closures`, `suppliers`, `banks`, `units`, etc.
- *Nota:* Certifique-se de **NÃO** incluir na remoção as tabelas: `users`, `roles`, `permissions`, `menus`, `menu_categories`, `audit_logs`, `migrations` e `sessions`.

### 3.2. Estrutura de Arquivos a Serem Deletados
Forneça os comandos em lote de terminal Linux (`rm -rf`) ou uma lista explícita dos caminhos dos arquivos físicos que devem ser apagados nas seguintes pastas:
- `app/Http/Controllers/` (incluindo as subpastas `Finance/` e `Accounting/`)
- `app/Models/` (modelos eclesiásticos e financeiros)
- `database/migrations/` (migrations correspondentes às tabelas removidas)
- `resources/views/` (pastas das views operacionais, contábeis e financeiras)

### 3.3. Código Refatorado e Higienizado do Arquivo `routes/web.php`
Forneça o código limpo, finalizado e higienizado para o arquivo `routes/web.php`, contendo estritamente as rotas públicas, o middleware de autenticação protegendo o dashboard, o perfil, o grupo
administrativo core (`menus`, `users`, `roles`, `permissions`, `logs/audits`) e a chamada de autenticação do Breeze.

---
### 📦 Retorno Esperado:
Entregue o código da rota limpa, o script de migração para purga do MySQL e os comandos de terminal para deleção física dos arquivos.