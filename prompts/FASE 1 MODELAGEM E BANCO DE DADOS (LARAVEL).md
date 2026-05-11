# 🗄️ FASE 1: MODELAGEM E BANCO DE DADOS (LARAVEL)

Com base no `01_Contexto_Master`, atue como um DBA e Arquiteto backend. Sua tarefa é gerar as Migrations e os Models do Eloquent com rigor absoluto na integridade referencial.

**Entidades Necessárias:**
1. **Organização:** `hierarchy_nodes` (id, name, type[enum:'Network', 'District', 'Area', 'Sector'], parent_id), `cells` (id, node_id, leader_id, meeting_day, address).
2. **Pessoas:** `users` (padrão Laravel + role, cell_id), `members` (user_id, mentor_id, baptism_date, status), `visitors` (name, phone, status, assigned_cell_id, last_contact_at).
3. **Operacional:** `weekly_reports` (cell_id, report_date, present_members, visitors, children, mda_count, conversions, kg_social, offer_pix, offer_cash, status[enum:'Draft', 'Submitted', 'Conciliated']).
4. **Financeiro:** `accounts`, `expenses`, `journal_entries`.

**Requisitos Técnicos:**
- Use tipagem estrita nos métodos do Laravel 11+.
- Todos os Models devem conter os relacionamentos completos (`hasMany`, `belongsTo`, `hasOne`). Exemplo: Uma `Cell` pertence a um `hierarchy_node` e tem muitos `weekly_reports`.
- Adicione `$fillable` ou `$guarded` adequadamente em todos os Models para segurança de Mass Assignment.

[INSTRUÇÃO PARA A IA]: Gere APENAS o código PHP das Migrations (dentro de `database/migrations`) e dos Models (dentro de `app/Models`). Não gere Controllers ainda.