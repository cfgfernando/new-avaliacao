# Prompt: Módulo de Relatórios Semanais de Célula — Frequência, Financeiro, Atividades e Consolidação Analítica

## Contexto
Projeto: Sistema MDA (ERP Eclesiástico). Este documento é um prompt/especificação para implementar ou migrar o módulo completo de **relatórios semanais de célula** em outro projeto já em andamento. 
Cobre a submissão de relatórios por líderes de célula, armazenamento de dados operacionais, sincronização com transações financeiras, consolidação hierárquica e relatórios analíticos avançados 
(DRE, BP, DMPL).

Use este arquivo para gerar migrations, modelos, controllers, validações, componentes frontend, relatórios consolidados e integração com o motor financeiro do projeto alvo.

---

## Objetivo
Descrever de forma completa e independente o fluxo de submissão, visualização e análise de relatórios semanais de célula, incluindo: rotas REST, modelo de dados, regras de negócio, validações,
 UX do formulário wizard, consolidação hierárquica em tempo real, e critérios de aceite para funcionalidade total.

---

## Entidades Principais e Propósito

### 1. Weekly Reports (`weekly_reports`)
Registro semanal de atividade de uma célula: frequência, visitantes, atividades pastorais, financeiro arrecadado, notas e metadados.

**Relacionamentos:**
- `cell_id` (FK) → Célula que enviou o relatório
- `present_member_ids` (JSON) → IDs dos membros presentes (para auditoria de presenças)
- `visitor_names` (JSON) → Nomes dos visitantes (para rastreamento de novo público)

**Campos de Frequência:**
- `committed_members` (int): Número total de membros comprometidos/inscritos
- `present_members` (int): Membros que compareceram
- `visitors` (int): Visitantes adultos novos
- `children` (int): Crianças que compareceram
- `other_cell_visitors` (int): Visitantes de outras células
- **Acessor (computed):** `total_presentes` = present_members + visitors + children

**Campos de Atividades:**
- `word_theme` (string): Tema da mensagem/ensinamento
- `meeting_location` (string): Local da reunião (endereço ou referência)
- `house_of_peace` (int): Casas de Paz abertas/ativas
- `mdas_done` (int): Acompanhamentos (MDA) realizados
- `kg_of_love` (decimal): Total em kg de alimentos/itens sociais coletados
- `reconciliations` (int): Reconciliações ou restaurações de relacionamentos

**Campos Financeiros:**
- `offer_cash` (decimal): Oferta em dinheiro/espécie
- `offer_pix` (decimal): Oferta via PIX/transferência digital
- **Acessor (computed):** `total_oferta` = offer_pix + offer_cash

**Campos Adicionais:**
- `conversions` (int): Decisões de conversão/batismo
- `notes` (text): Anotações livres do líder (situação especial, dificuldades, bênçãos)
- `meeting_date` (date): Data da reunião (UNIQUE por célula)
- `created_at`, `updated_at` (timestamps)

---

## Fluxo de Submissão (Wizard 5 Etapas)

### Etapa 1: Informações Gerais
- **Campos**: `word_theme`, `meeting_location`
- **Validação**: Ambos obrigatórios
- **Ação**: Exibir dica de preenchimento, permitir avançar se válido

### Etapa 2: Presença (Seleção de Membros)
- **Dados**: Lista de membros ativos da célula carregada do `Cell::with('members')`
- **Interação**: Checkboxes para selecionar membros presentes; total dinâmico
- **Campo salvo**: `present_member_ids` (array JSON)
- **Validação**: `present_members >= 0`

### Etapa 3: Visitantes e Público
- **Campos**: `visitors`, `children`, `other_cell_visitors`
- **Interação adicional**: Tabela com nomes de visitantes (campos de texto ou seleção de membros do Radar 48h)
- **Campo salvo**: `visitor_names` (array JSON com nomes)
- **Acessor display**: `total_presentes` = present_members + visitors + children

### Etapa 4: Atividades e Financeiro
- **Atividades**: `house_of_peace`, `mdas_done`, `kg_of_love`, `reconciliations`
- **Financeiro**: `offer_cash`, `offer_pix`
- **Display**: Cards com totalizações em tempo real
- **Ícones/Indicadores**: Material Design Icons (heart_plus, groups, package, wallet_giftcard)

### Etapa 5: Resumo e Confirmação
- **Review**: Exibição de todos os campos preenchidos em formato amigável
- **Indicadores**: 
  - Total de Presentes
  - Total de Oferta
  - KGs coletados
  - MDAs realizados
  - Conversões
- **Ação**: Botão "Finalizar" que submete via POST `/reports`

---

## Endpoints / Rotas REST

### Relatórios (Base: `/reports`)
- `GET /reports` — Lista paginada de relatórios com filtros e hierarquia
  - Query params: `start_date`, `end_date`, `search` (word_theme), `network_id`, `district_id`, `area_id`, `sector_id`, `cell_id`
  - Retorna: Paginação com data, tema, célula, totais
- `POST /reports` — Criar novo relatório (apenas líderes/admin)
  - Payload: `cell_id`, `meeting_date`, `word_theme`, `meeting_location`, frequência, atividades, financeiro, notas
  - Validação: Data única por célula
  - Ação secundária: Sincronizar visitantes com Radar 48h; Sincronizar com Transações Financeiras
- `GET /reports/create` — Formulário wizard para criar (Inertia)
  - Retorna: `cell`, `members`, `availableCells`
- `GET /reports/{id}` — Detalhes de um relatório (modal/drawer)
  - Retorna: Todos os campos, dados da célula, relacionamento com transações
- `PUT /reports/{id}` — Editar relatório (apenas líder da célula ou admin)
  - Validação: Mesmas regras de store, exceto única de data (permitir reedição)
  - Ação: Re-sincronizar com transações financeiras e visitantes
- `DELETE /reports/{id}` — Deletar relatório (soft-delete ou hard-delete com cascata para transações)
  - Permissão: Apenas admin ou líder da célula
- `GET /reports/consolidation` — Consolidação hierárquica (período, rede, distrito, área, setor)
  - Query params: `period` (week/month/quarter/year), `network_id`, `district_id`, `area_id`, `sector_id`
  - Retorna: Somatórios, médias, gráficos, dados históricos

### Relatórios Financeiros (Base: `/finance/reports`)
- `GET /finance/reports/dre` — Demonstração de Resultado do Exercício (DRE)
  - Agrupa ofertas por período e exibe receita/despesa
- `GET /finance/reports/bp` — Balanço Patrimonial (BP)
  - Saldo de contas por data
- `GET /finance/reports/dmpl` — Demonstração de Variações do Patrimônio Líquido (DMPL)
  - Variações por período
- `GET /finance/reports/receipts` — Recibos de arrecadação
  - Extrato por célula, data, tipo de arrecadação

Autenticação: Todos endpoints protegidos por autorização. Use policies com base em supervisionamento hierárquico.

---

## Modelo de Dados — Tabelas e Colunas

### 1. `weekly_reports` (Principal)
```sql
CREATE TABLE weekly_reports (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  cell_id BIGINT UNSIGNED NOT NULL,
  meeting_date DATE NOT NULL,
  word_theme VARCHAR(255) NULLABLE,
  meeting_location VARCHAR(255) NULLABLE,
  
  -- Frequência
  committed_members INT DEFAULT 0,
  present_members INT DEFAULT 0,
  visitors INT DEFAULT 0,
  children INT DEFAULT 0,
  other_cell_visitors INT DEFAULT 0,
  
  -- Atividades Pastorais
  house_of_peace INT DEFAULT 0,
  mdas_done INT DEFAULT 0,
  kg_of_love DECIMAL(8, 2) DEFAULT 0.00,
  reconciliations INT DEFAULT 0,
  conversions INT DEFAULT 0,
  
  -- Financeiro
  offer_pix DECIMAL(10, 2) DEFAULT 0.00,
  offer_cash DECIMAL(10, 2) DEFAULT 0.00,
  
  -- Metadados
  notes TEXT NULLABLE,
  present_member_ids JSON NULLABLE,  -- ["member_id1", "member_id2", ...]
  visitor_names JSON NULLABLE,        -- ["John Doe", "Jane Smith", ...]
  
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  deleted_at TIMESTAMP NULLABLE,     -- Soft delete
  
  FOREIGN KEY (cell_id) REFERENCES cells(id) CASCADE ON DELETE,
  UNIQUE KEY unique_cell_meeting (cell_id, meeting_date),
  INDEX idx_meeting_date (meeting_date),
  INDEX idx_cell_id (cell_id)
);
```

**Índices recomendados:**
- `UNIQUE(cell_id, meeting_date)` — Previne duplicatas
- `INDEX(meeting_date)` — Filtros por período
- `INDEX(cell_id)` — Filtros por célula
- `INDEX(created_at)` — Ordenação padrão

---

## Relações e Integridade

### De `weekly_reports` para outras entidades:
- **FK para `cells`**: `weekly_reports.cell_id` → `cells.id` (CASCADE DELETE)
- **Relação implícita com `members`** (via `present_member_ids` JSON): Buscar membros por IDs
- **Relação com `transactions`**: 
  - Se `offer_cash > 0` ou `offer_pix > 0`, criar/atualizar transações em `transactions` table
  - Coluna `transactions.weekly_report_id` (FK) rastreia vínculo inverso
- **Relação com `financial_remittances`** (Malotes):
  - Transações podem ser agrupadas em malotes (`financial_remittances`)
  - Se malote está em `pending_delivery`, permitir reedição de transações

### Cascata de exclusão:
- Ao deletar `weekly_reports`, deletar transações vinculadas (`transactions.weekly_report_id`)
- Ao deletar `cells`, deletar todos relatórios da célula (cascade)

---

## Regras de Negócio Detalhadas

### Submissão e Autenticação:
1. **Apenas Líderes de Célula e Admin** podem submeter relatórios
   - Líderes só podem submeter para sua própria célula
   - Admin pode submeter para qualquer célula
   - Supervisores têm permissão de visualização/auditoria apenas

2. **Unicidade por Data**:
   - Não é permitido submeter dois relatórios para a mesma célula no mesmo dia
   - Erro: `meeting_date.unique` com mensagem clara

3. **Campos Numéricos**:
   - Todos valores nulos são convertidos para zero antes de salvar
   - Valores negativos são bloqueados via validação

### Integração com Financeiro:
1. **Sincronização de Transações**:
   - Ao criar/atualizar relatório, sincronizar automaticamente com `transactions` table
   - Se `offer_cash > 0`: Criar/atualizar transação com `payment_method='cash'`
   - Se `offer_pix > 0`: Criar/atualizar transação com `payment_method='pix'`
   - Ambas transações com `type='credit'`, `category='Dízimo/Oferta'`, `status='pending'`

2. **Atualização de Malotes**:
   - Se transação foi vinculada a malote em `pending_delivery`, desvinculação automática
   - Permite reedição de valores sem afetar malotes já recebidos
   - Se malote foi recebido (`status != 'pending_delivery'`), impedir exclusão de transação

### Integração com Visitantes (Radar 48h):
1. Ao salvar `visitor_names`, invocar `MemberService::syncVisitorsFromReport()`
2. Isso popula ou atualiza membros temporários no Radar 48h
3. Líderes podem promover visitantes a membros a partir dessa listagem

### Consolidação Hierárquica:
1. **Agregação de Dados**:
   - `GET /reports/consolidation` calcula somas e médias para período/hierarquia selecionada
   - Suporta filtros: Network → District → Area → Sector → Cell
   - Retorna somatórios de: `total_presentes`, `total_oferta`, `kg_of_love`, `mdas_done`, `conversions`, etc.

2. **Cálculos de Média**:
   - Média de presentes por reunião
   - Ticket médio de oferta
   - Taxa de conversão por célula/setor

3. **Comparações Período a Período**:
   - Compare semana/mês/trimestre/ano com períodos anteriores
   - Calcule variação percentual

---

## Validações e Permissões

### Validação de Entrada (FormRequest):
```php
[
    'cell_id' => 'required|exists:cells,id',
    'meeting_date' => [
        'required', 'date',
        Rule::unique('weekly_reports')->where(fn($q) => 
            $q->where('cell_id', $request->cell_id)
        )
    ],
    'word_theme' => 'required|string|max:255',
    'meeting_location' => 'required|string|max:255',
    'committed_members' => 'required|integer|min:0|max:1000',
    'present_members' => 'required|integer|min:0|max:1000',
    'visitors' => 'required|integer|min:0|max:500',
    'children' => 'required|integer|min:0|max:500',
    'other_cell_visitors' => 'nullable|integer|min:0|max:500',
    'house_of_peace' => 'nullable|integer|min:0|max:100',
    'mdas_done' => 'nullable|integer|min:0|max:100',
    'kg_of_love' => 'nullable|numeric|min:0|max:10000',
    'reconciliations' => 'nullable|integer|min:0|max:100',
    'conversions' => 'nullable|integer|min:0|max:100',
    'offer_pix' => 'nullable|numeric|min:0|max:999999.99',
    'offer_cash' => 'nullable|numeric|min:0|max:999999.99',
    'notes' => 'nullable|string|max:2000',
    'present_member_ids' => 'nullable|array|max:500',
    'visitor_names' => 'nullable|array|max:100',
]
```

### Permissões (Policies):
- `viewAny`: Usuário deve ser líder com supervisão ou admin
- `view`: Usuário pode visualizar se é líder/admin ou supervisor da célula/hierarquia
- `create`: Apenas se `isLeader() || isAdmin()`
- `update`: Apenas se `isAdmin()` ou (`isLeader()` e célula é a dele)
- `delete`: Apenas admin

### Filtros por Hierarquia:
- **Admin**: Acesso total
- **Líderes**: Apenas sua célula
- **Supervisores**: Células sob supervisão (setor, área, distrito, rede)

---

## UX / Interações Essenciais

### Página de Listagem (`/reports`)
- **Cards de Estatísticas** no topo:
  - Total de relatórios no período
  - Total de presentes (sum)
  - Total financeiro (sum)
  - Taxa média de presença
  
- **Barra de Filtros**:
  - Data início/fim (date pickers)
  - Busca por tema (word_theme)
  - Filtros hierárquicos em cascata (Network → District → Area → Sector → Cell)
  - Botão "Filtrar" com debounce
  - Botão "Reset Filtros"

- **Tabela de Relatórios**:
  - Colunas: Data, Tema, Célula, Presentes (presente_members), Visitantes, Total Oferta
  - Ordenação clicável em colunas
  - Paginação (10 itens por página)
  - Ações: "Ver Detalhes" (modal), "Editar" (se permissão), "Excluir" (se permissão)

- **Modal de Detalhes**:
  - Exibir todos os campos em formato amigável
  - Ícones para cada seção (frequência, atividades, financeiro)
  - Indicadores de status
  - Botões de edição/exclusão se pertinente

### Página de Criação/Edição (`/reports/create` e `/reports/{id}/edit`)
- **Wizard com 5 etapas**:
  - Barra de progresso visual no topo
  - Exibição clara do passo atual (Step X/5)
  - Botões "Voltar" e "Continuar" (ou "Finalizar" na última)
  - Validação inline e exibição de erros

- **Etapa 1 - Informações Gerais**:
  - Campo de tema com autocomplete (temas históricos)
  - Campo de local com sugestões (locais históricos)

- **Etapa 2 - Presença**:
  - Lista de membros com checkboxes
  - Filtro/busca por nome
  - Contador dinâmico de selecionados
  - Indicador de "X de Y membros presentes"

- **Etapa 3 - Visitantes**:
  - Campos para `visitors`, `children`, `other_cell_visitors`
  - Tabela dinâmica para adicionar nomes de visitantes
  - Botão "Adicionar Visitante"
  - Total dinâmico: "Total Presentes = X"

- **Etapa 4 - Atividades e Financeiro**:
  - Cards para cada atividade com ícone e campo numérico
  - Inputs para `offer_cash` e `offer_pix` com formatação de moeda
  - Display do `total_oferta` em tempo real

- **Etapa 5 - Resumo**:
  - Cards-resumo de cada seção
  - KPIs: Total Presentes, Total Oferta, KG Coletados, MDAs, Conversões
  - Botão de confirmação com spinner durante submit

### Página de Consolidação (`/reports/consolidation`)
- **Filtros de Período**:
  - Seletor: Semana | Mês | Trimestre | Ano
  - Data range picker (opcional)

- **Filtros Hierárquicos** (cascata):
  - Network → District → Area → Sector → Cell (opcional)

- **Dashboard com Cards**:
  - Total Presentes (com progress bar estimado)
  - Geral Financeiro (com ícone de wallet)
  - KG do Amor (alimentos coletados)
  - MDAs Realizados
  - Taxa de Conversão
  - Média de Presentes por Reunião

- **Gráficos Analíticos**:
  - Evolução de presentes por semana (line chart)
  - Evolução de oferta por semana (bar/line chart)
  - Distribuição por célula (pie/donut chart)
  - Top 5 células por oferta (ranking)

- **Tabelas Detalhadas**:
  - Relatórios individuais consolidados
  - Breakdown por célula/setor
  - Comparação semana a semana

- **Exports**:
  - Botão "Exportar PDF" (print-friendly layout)
  - Botão "Exportar Excel" (com formatação de dados)

---

## Exemplos de Payloads

### Criar Relatório
```json
{
  "cell_id": 5,
  "meeting_date": "2026-05-15",
  "word_theme": "Fruto do Espírito - Amor",
  "meeting_location": "Casa de Paz - Rua das Flores, 123",
  "committed_members": 12,
  "present_members": 10,
  "visitors": 3,
  "children": 2,
  "other_cell_visitors": 1,
  "house_of_peace": 2,
  "mdas_done": 5,
  "kg_of_love": 15.5,
  "reconciliations": 1,
  "conversions": 1,
  "offer_pix": 250.00,
  "offer_cash": 120.50,
  "notes": "Grande presença. Visitantes muito interessados. Uma irmã foi restaurada com sua família.",
  "present_member_ids": ["1", "2", "3", "5", "7", "8", "10", "11", "12", "14"],
  "visitor_names": ["João Silva", "Maria Santos", "Pedro Oliveira"]
}
```

### Resposta de Sucesso (200)
```json
{
  "message": "Relatório criado com sucesso!",
  "report": {
    "id": 42,
    "cell_id": 5,
    "meeting_date": "2026-05-15",
    "word_theme": "Fruto do Espírito - Amor",
    "present_members": 10,
    "visitors": 3,
    "children": 2,
    "total_presentes": 15,
    "offer_pix": 250.00,
    "offer_cash": 120.50,
    "total_oferta": 370.50,
    "created_at": "2026-05-15T14:30:00Z"
  }
}
```

### Erro de Validação (422)
```json
{
  "errors": {
    "meeting_date": ["Já existe um relatório registrado para esta célula nesta data."],
    "committed_members": ["O campo membros compromissados é obrigatório."]
  }
}
```

### GET /reports (com filtros)
```
GET /reports?start_date=2026-05-01&end_date=2026-05-31&sector_id=2&sort_by=meeting_date&direction=desc
```

**Resposta (200)**:
```json
{
  "data": [
    {
      "id": 42,
      "cell_id": 5,
      "cell_name": "Célula Vida",
      "meeting_date": "2026-05-15",
      "word_theme": "Fruto do Espírito",
      "present_members": 10,
      "visitors": 3,
      "total_presentes": 15,
      "offer_cash": 120.50,
      "offer_pix": 250.00,
      "total_oferta": 370.50
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "next": "...",
    "prev": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 42
  }
}
```

### GET /reports/consolidation
```
GET /reports/consolidation?period=month&network_id=1
```

**Resposta (200)**:
```json
{
  "period": "March 2026",
  "sums": {
    "total_presentes": 456,
    "total_offer": 12450.75,
    "kg_of_love": 125.3,
    "mdas_done": 48,
    "conversions": 12,
    "reconciliations": 8
  },
  "averages": {
    "total_presentes": 18.2,
    "total_offer": 497.63,
    "kg_of_love": 5.01,
    "mdas_done": 1.92,
    "conversions": 0.48
  },
  "weekly": [
    {
      "week": "Week 1 (Mar 1-7)",
      "sums": { "total_presentes": 110, "total_offer": 2850.00 },
      "reports_count": 6
    }
  ],
  "charts": {
    "presence_trend": [...],
    "financial_trend": [...],
    "top_cells": [...]
  }
}
```

---

## Migrations / Esqueleto (Laravel)

### Migração Principal
```php
// database/migrations/[timestamp]_create_weekly_reports_table.php
Schema::create('weekly_reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cell_id')->constrained('cells')->cascadeOnDelete();
    $table->date('meeting_date');
    
    // Frequência
    $table->integer('committed_members')->default(0);
    $table->integer('present_members')->default(0);
    $table->integer('visitors')->default(0);
    $table->integer('children')->default(0);
    $table->integer('other_cell_visitors')->default(0);
    
    // Atividades
    $table->integer('house_of_peace')->default(0);
    $table->integer('mdas_done')->default(0);
    $table->decimal('kg_of_love', 8, 2)->default(0);
    $table->integer('reconciliations')->default(0);
    $table->integer('conversions')->default(0);
    
    // Financeiro
    $table->decimal('offer_pix', 10, 2)->default(0);
    $table->decimal('offer_cash', 10, 2)->default(0);
    
    // Metadados
    $table->string('word_theme')->nullable();
    $table->string('meeting_location')->nullable();
    $table->text('notes')->nullable();
    $table->json('present_member_ids')->nullable();
    $table->json('visitor_names')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
    
    // Índices
    $table->unique(['cell_id', 'meeting_date']);
    $table->index('meeting_date');
});
```

### Ligação com Transações (se necessário)
```php
// Se transactions table não tiver weekly_report_id, adicionar via migration
Schema::table('transactions', function (Blueprint $table) {
    $table->foreignId('weekly_report_id')
        ->nullable()
        ->constrained('weekly_reports')
        ->cascadeOnDelete()
        ->after('cell_id');
});
```

---

## Models / Eloquent (Esqueleto)

### WeeklyReport Model
```php
class WeeklyReport extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'cell_id', 'meeting_date', 'word_theme', 'meeting_location',
        'committed_members', 'present_members', 'visitors', 'children',
        'other_cell_visitors', 'house_of_peace', 'mdas_done', 'kg_of_love',
        'reconciliations', 'conversions', 'offer_pix', 'offer_cash', 'notes',
        'present_member_ids', 'visitor_names'
    ];
    
    protected $casts = [
        'present_member_ids' => 'array',
        'visitor_names' => 'array',
        'meeting_date' => 'date:Y-m-d',
        'kg_of_love' => 'decimal:2',
        'offer_pix' => 'decimal:2',
        'offer_cash' => 'decimal:2',
    ];
    
    // Accessors (computed properties)
    protected function totalPresentes(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->present_members + $this->visitors + $this->children,
        );
    }
    
    protected function totalOferta(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->offer_pix + $this->offer_cash,
        );
    }
    
    // Relationships
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }
    
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
```

---

## Controllers / Rotas Esqueleto

### ReportController (CRUD + Consolidação)
```php
class ReportController extends Controller
{
    public function index(Request $request) { /* ... */ }
    public function create(Request $request) { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function edit(WeeklyReport $report) { /* ... */ }
    public function update(Request $request, WeeklyReport $report) { /* ... */ }
    public function destroy(WeeklyReport $report) { /* ... */ }
    public function consolidation(Request $request) { /* ... */ }
    
    private function syncFinancialTransaction(WeeklyReport $report) { /* ... */ }
}
```

### Routes
```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('reports', ReportController::class)
        ->except(['show']);
    Route::get('reports/consolidation', [ReportController::class, 'consolidation'])
        ->name('reports.consolidation');
});
```

---

## Critérios de Aceitação

1. ✅ **CRUD Completo**:
   - Líderes podem criar relatórios para sua célula
   - Admin pode criar para qualquer célula
   - Edição mantém mesmas validações
   - Exclusão (soft-delete) limpa transações vinculadas

2. ✅ **Validação de Unicidade**:
   - Uma célula não pode ter mais de um relatório por data
   - Mensagem clara para usuário

3. ✅ **Integração Financeira**:
   - Ao criar/editar relatório, transações são sincronizadas
   - Se malote está em `pending_delivery`, é deletado antes de sincronizar
   - Novos valores são refletidos em relatórios financeiros (DRE, BP)

4. ✅ **Integração com Visitantes**:
   - `visitor_names` é passado para `MemberService::syncVisitorsFromReport()`
   - Visitantes aparecem no Radar 48h

5. ✅ **Filtros e Busca**:
   - Filtros hierárquicos funcionam em cascata (Network → Cell)
   - Busca por tema funciona com LIKE
   - Ordenação por data, presentes, oferta
   - Debounce para performance

6. ✅ **Consolidação Hierárquica**:
   - Aggregation funciona para período selecionado
   - Calcula somatórios e médias corretamente
   - Comparações período a período
   - Gráficos renderizam sem erro

7. ✅ **Permissões**:
   - Líderes só veem suas células
   - Supervisores veem hierarquia
   - Admin vê tudo
   - Tentativa de acesso não autorizado retorna 403

8. ✅ **UX do Wizard**:
   - 5 etapas carregam sem erro
   - Validação inline por etapa
   - Botões "Voltar" / "Continuar" funcionam
   - Progresso visual atualiza corretamente
   - Resumo mostra dados corretos
   - Submit cria registro sem erro

9. ✅ **Testes Unitários**:
   - Teste de criação com dados válidos
   - Teste de validação de unicidade de data
   - Teste de permissão (leader vs admin vs supervisor)
   - Teste de sincronização com transações
   - Teste de consolidação com múltiplas células

10. ✅ **Performance**:
    - Listagem retorna em < 500ms (10 itens)
    - Consolidação retorna em < 2s (dados de 1 mês)
    - Índices criados em campos-chave

---

## Integração com Projeto Alvo — Passo a Passo

1. **Copiar este arquivo** para `Prompts/REPORTS_MODULE_PROMPT.md`

2. **Gerar migrations**:
   - Criar `create_weekly_reports_table.php`
   - Executar `php artisan migrate`

3. **Criar Models**:
   - `WeeklyReport` com cast de datas e arrays
   - Definir relações com `Cell` e `Transaction`

4. **Implementar Controllers**:
   - `ReportController` com métodos CRUD, `consolidation()`
   - `FormRequest` com validações detalhadas
   - `ReportPolicy` para autorização

5. **Criar Rotas**:
   - Registrar resource routes + `consolidation`

6. **Implementar Frontend**:
   - Vue componentes do Wizard (5 etapas)
   - Página de listagem com filtros
   - Modal de detalhes
   - Página de consolidação com gráficos

7. **Integração com Financeiro**:
   - Implementar `syncFinancialTransaction()` em `ReportController`
   - Garantir que cria `Transaction` com `weekly_report_id`
   - Testar cascade delete

8. **Integração com Visitantes**:
   - Chamar `MemberService::syncVisitorsFromReport()` após salvar
   - Validar que visitantes aparecem em Radar 48h

9. **Testes**:
   - Criar `ReportControllerTest` com casos de uso principais
   - Testar CRUD, validações, permissões
   - Testar consolidação com múltiplos relatórios

10. **Deploy**:
    - Executar migrations em staging
    - Validar UX do wizard em mobile
    - Teste load (múltiplos líderes submetendo simultaneamente)

---

## Observações Técnicas e Boas Práticas

### Performance:
- Use `with()` para eager load de `cell` em listagens
- Index `meeting_date` e `cell_id` para queries frequentes
- Cache consolidação por período (Redis) — expire em 1 hora
- Considere materializar somatórios diários para relatórios históricos

### Segurança:
- Validar que `present_member_ids` contém IDs reais de membros da célula
- Sanitizar `notes` (pode conter texto livre do usuário)
- Rate-limit submissão de relatórios (máx 1 por dia por célula)

### Auditoria:
- Registrar mudanças em `audit_logs` se requisito de governança
- Notamment: quem criou, quem editou, quando
- Tracker de sincronização com transações (debugging)

### Extensibilidade:
- Adicionar campos dinâmicos na etapa 4 via JSON (customização por rede)
- Permitir relatórios customizados (query builder)
- Webhooks para eventos de submissão (notificações, BI)

### Integração com BI:
- Exportar dados consolidados via API para Tableau/PowerBI
- Endpoint: `GET /api/reports/data?format=json&period=month&start=2026-01-01&end=2026-12-31`

---

## Próximos Passos que Posso Gerar Automaticamente

- Migrations completas e testadas
- Models com Factory + Seeder
- Controllers + FormRequests + Policies
- Vue components do Wizard (5 etapas)
- Testes unitários e de integração
- Dashboard de Consolidação (Inertia page)
- Scripts de importação histórica de relatórios (se migração de sistema legado)


