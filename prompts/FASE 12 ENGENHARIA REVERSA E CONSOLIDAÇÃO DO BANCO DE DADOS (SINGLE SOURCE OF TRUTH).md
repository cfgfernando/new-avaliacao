# 🗄️ FASE 12: ENGENHARIA REVERSA E CONSOLIDAÇÃO DO BANCO DE DADOS (SINGLE SOURCE OF TRUTH)

## 📋 CONTEXTO DA FASE
Atue como um Arquiteto de Banco de Dados e Desenvolvedor Backend Sênior. Nós possuímos o esquema exato de um banco de dados legado (MySQL) altamente estruturado e otimizado para o **MDA CHURCH ERP**. 

A partir deste momento, **todas as Migrations e Models Eloquent** gerados devem respeitar ESTRITAMENTE a modelagem, os tipos de dados (enums, decimais, jsons) e as chaves estrangeiras detalhadas
 abaixo, sem alucinações.

---

## 🧩 MAPEAMENTO DE ENTIDADES POR MÓDULO

### 1. 🌳 Hierarquia MDA e Estrutura Física
A base estrutural da igreja. Células pertencem a Setores, que pertencem a Áreas, etc.
- **`campuses`**: Estrutura física (`type`: 'cathedral', 'nucleus'). Possui campos de CNPJ, endereço e contato.
- **`networks` (Redes)**: Topo da pirâmide. Tem `supervisor_id` e `color_hex`.
- **`districts` (Distritos)**: Pertencem a uma rede.
- **`areas` (Áreas)**: Pertencem a um distrito e a um `campus_id`.
- **`sectors` (Setores)**: Pertencem a uma área.
- **`cells` (Células)**: A base. Possui `sector_id`, `leader_id`, `parent_cell_id` (para multiplicações), latitude/longitude, dia e horário.

### 2. 👥 Pessoas, CRM e Liderança
- **`users`**: Tabela de autenticação atrelada a `role_id`, `campus_id` e `cell_id`.
- **`roles`**: Controle de permissões (RBAC). Possui coluna `core_tier` Enum ('admin', 'supervisor', 'leader', 'member') e coluna JSON `permissions`.
- **`members`**: Perfis completos. Possui colunas avançadas: `member_type`, `status` (Assíduo, Preventiva, etc), `mentor_id` (quem cuida), `portal_access_status` e colunas JSON (`trilho_lideranca`, 
`transfer_log`).
- **`visitors`**: Radar de visitantes. Possui `pipeline_stage`, `contact_status`, `visits_count`, e tracking geográfico.
- **`visitor_contacts`**: Histórico de interações com visitantes (Radar 48h).
- **`pastoral_notes`**: Anotações confidenciais de pastores sobre membros.

### 3. 📊 Operacional (Relatório de Célula)
- **`weekly_reports`**: O motor da célula.
  - **ATENÇÃO:** Esta tabela utiliza armazenamento JSON nativo para listas dinâmicas: `present_member_ids` (IDs dos membros presentes) e `visitor_names` (array de nomes de visitantes).
  - Métricas quantitativas: `children`, `house_of_peace`, `mdas_done`, `kg_of_love`, `conversions`.
  - Financeiro declarado: `offer_pix`, `offer_cash`.

### 4. 💰 Financeiro (Compliance e Malotes)
O módulo mais complexo, estruturado para contabilidade real.
- **`chart_of_accounts`**: Plano de contas recursivo (`parent_id`) com tipos 'asset', 'liability', 'equity', 'revenue', 'expense' e classificação tributária (`tax_classification`).
- **`cost_centers`**: Centros de custo departamentais.
- **`financial_accounts`**: Contas bancárias e cofres físicos (`balance_cache`).
- **`transactions`**: Movimentações (Entradas/Saídas). Tipos de pagamento via Enum, recorrência e links para `weekly_report_id`, `member_id`, `supplier_id`.
- **Malotes (Remittances & Batches)**: `financial_batches` (dados declarados vs confirmados) e `financial_remittances` (consolidação polimórfica `remittable_type` para unir relatórios de células).
- **`journal_entries` & `accounting_audits`**: O "Livro Diário" (Partidas Dobradas) para auditoria intocável das transações.
- **`fixed_assets`**: Controle de Ativo Imobilizado (Cálculo de depreciação de bens).

### 5. 📱 Engajamento e Portal do Membro
- **`events` & `event_enrollments`**: Gestão de eventos e inscrições.
- **`member_posts`**: Mural de comunicados, atrelado a `member_post_categories`.
- **`member_post_views`**: Tracking de leitura dos comunicados (tabela pivô com timestamp `viewed_at`).
- **`volunteers` & `volunteer_work_entries`**: Valor Justo de trabalho voluntário (ITG 2002), calculando horas vs `fair_value_hour`.
- **`products`**: Estoque e precificação para Cantinas e Livrarias (`unit_price`, `cost_price`).

### 6. 🛡️ Sistema e UX
- **`menus`**: Construção dinâmica do menu lateral hierárquico com base na permissão (`required_permission`, `allowed_roles`).
- **`notifications`**: Alertas internos do sistema (`is_read`).

---

## 🛠️ INSTRUÇÕES DE EXECUÇÃO PARA A IA

1. **Geração de Migrations:** Quando solicitado para criar as Migrations, você deve reproduzir fielmente as restrições de chave estrangeira (`ON DELETE SET NULL`, `ON DELETE CASCADE`), os tipos 
`ENUM` e `JSON`, e os valores *default* (ex: `is_active = 1`)
 extraídos do banco legado.
2. **Models Eloquent:** - Configure o `$casts` adequado em todos os Models para tratar colunas JSON (ex: `protected $casts = ['present_member_ids' => 'array'];`).
   - Configure o `$casts` para os Enums nativos das tabelas (ex: `transaction_status`).
   - Todos os relacionamentos polimórficos (ex: em `financial_remittances`) devem ser devidamente declarados com `morphTo()`.
3. **Regra de Ouro:** Não renomeie tabelas ou colunas. O backend PHP deve se acoplar de forma fluida a este banco de dados preexistente para evitar quebras de migração.

[AÇÃO EXIGIDA DA IA]: Confirme que você mapeou a arquitetura do banco de dados na sua memória e compreendeu o uso intensivo de chaves estrangeiras, colunas JSON e auditoria financeira. 
Aguarde o comando para iniciar a geração do código PHP focado em um módulo específico.