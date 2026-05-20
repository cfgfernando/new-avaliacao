# Módulo: Célula — Descrição Técnica e Prompt Backend

Objetivo: documentar por completo o módulo de gestão de `Células` (rota `/cells`) e fornecer um prompt pronto para gerar o backend (migrations, models, controllers, rotas, seeders, validações) 
em outro projeto Laravel.

**Resumo funcional:**
- CRUD de Células (index, show, create, store, edit, update, destroy)
- Relações principais: `Cell` pertence a `Sector`, tem `leader` (User), tem muitos `members` e `reports` (WeeklyReport).
- Campos principais: nome, setor, endereço, latitude, longitude, líder, dia/hora de reunião, descrição, whatsapp_group, is_active, parent_cell_id (para multiplicações).
- Regras de negócio: restrição de acesso por roles (leader/supervisor/admin), proteção para líderes não alterarem campos estruturais, ao excluir célula desvincular membros.

---

**Rotas (essenciais)**
- Resource RESTful (web): `Route::resource('cells', CellController::class);`
  - GET `/cells` → `CellController@index` (lista)
  - GET `/cells/create` → `CellController@create` (form)
  - POST `/cells` → `CellController@store` (criar)
  - GET `/cells/{cell}` → `CellController@show` (detalhes)
  - GET `/cells/{cell}/edit` → `CellController@edit` (form editar)
  - PUT `/cells/{cell}` → `CellController@update` (atualizar)
  - DELETE `/cells/{cell}` → `CellController@destroy` (remover)

---

**Model: `Cell` (app/Models/Cell.php)**
- Atributos principais (persistidos):
  - `id` (PK)
  - `sector_id` (FK -> sectors)
  - `parent_cell_id` (nullable FK -> cells)
  - `name` string
  - `address` string nullable
  - `latitude` decimal(10,8) nullable
  - `longitude` decimal(11,8) nullable
  - `leader_id` nullable FK -> users
  - `meeting_day` string nullable
  - `meeting_time` time/string nullable
  - `description` text nullable
  - `whatsapp_group` string nullable
  - `is_active` boolean default true
  - timestamps
- Relações: `sector()`, `leader()`, `members()`, `reports()`, `parentCell()`, `childCells()`

---

**Migration resumida (SQL mínimo)**

CREATE TABLE `cells` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `sector_id` BIGINT NOT NULL,
  `parent_cell_id` BIGINT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address` VARCHAR(500) NULL,
  `latitude` DECIMAL(10,8) NULL,
  `longitude` DECIMAL(11,8) NULL,
  `leader_id` BIGINT NULL,
  `meeting_day` VARCHAR(50) NULL,
  `meeting_time` TIME NULL,
  `description` TEXT NULL,
  `whatsapp_group` VARCHAR(500) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL, `updated_at` TIMESTAMP NULL
);

Adicione `foreign key` para `sector_id`, `parent_cell_id` (se desejar) e `leader_id`.

---

**Controller: `CellController` (comportamento observado)**
- `index()` → filtra pela autoridade do usuário (leaders veem só suas células, supervisores veem supervisionadas), retorna lista com `members_count` e `reports_count`.
- `show(Cell $cell)` → autorização (leader/supervisor), carrega `members` e últimos `reports`, calcula média de presença.
- `store(Request $r)` → valida campos (name, sector_id, optional leader_id, address, lat/long, meeting_day/time, description, whatsapp_group, is_active), cria registro.
- `update(Request $r, Cell $cell)` → valida; aplica regra: se authUser.isLeader(), remove validações para `name`, `sector_id`, `leader_id` (líderes não alteram meta-estrutura).
- `destroy(Cell $cell)` → antes de deletar, desvincula membros (Member::where('cell_id',$id)->update(['cell_id'=>null])) e então deleta.

---

**Validações (exemplos)**
- `name` => required|string|max:255
- `sector_id` => required|exists:sectors,id
- `leader_id` => nullable|exists:users,id
- `address` => nullable|string|max:500
- `latitude`, `longitude` => nullable|numeric
- `meeting_time` => nullable|date_format:H:i
- `is_active` => boolean

---

**Seeders sugeridos**
- `SectorSeeder` (alguns setores de exemplo)
- `CellSeeder` com 10 células exemplo ligadas a setores, alguns com `leader_id` e `meeting_day/time`
- `MemberSeeder` vinculado a algumas células para testar contadores

---

**Hooks/Business rules**
- Ao excluir célula: desvincular membros e possivelmente cancelar malotes/relatórios vinculados.
- Ao criar relatório (`WeeklyReport`) para uma célula, sincronizar transações financeiras (método `syncFinancialTransaction` no `ReportController`).
- Proteção de edição de campos sensíveis para perfis `leader`.

---

**Dependências / requisitos**
- Laravel ^12, PHP >= 8.2
- Models `Sector`, `User`, `Member`, `WeeklyReport` presentes
- Rotas protegidas por middleware `auth`

---

PROMPT BACKEND PRONTO (copiar e colar para um LLM gerador de código):

"Contexto: Estou construindo um módulo Laravel 12 para gerenciar Células (Entidade organizacional). O sistema usa autenticação e hierarquias (sectors, leaders, supervisors). Preciso gerar migrations, model, controller, rotas e seeders para replicar o módulo em outro projeto.

Tarefa: Gere os seguintes arquivos PHP compatíveis com Laravel 12 e padrões Eloquent:

1) Migration `create_cells_table` com colunas: `id`, `sector_id` (FK), `parent_cell_id` (nullable FK), `name` string, `address` nullable, `latitude` decimal(10,8) nullable, `longitude` decimal(11,8) nullable, `leader_id` nullable FK users, `meeting_day` nullable string, `meeting_time` nullable time, `description` text nullable, `whatsapp_group` nullable string, `is_active` boolean default true, timestamps. Incluir `down()` para dropar tabela.

2) Model `app/Models/Cell.php` com `$guarded = []`, relações: `sector()`, `leader()`, `members()`, `reports()`, `parentCell()`, `childCells()`.

3) Controller `app/Http/Controllers/CellController.php` com métodos `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`.
   - `index` deve escopar por autoridade do usuário (se leader apenas suas células; se supervisor, células supervisionadas) e retornar `Inertia::render('Cells/Index', [...])` com `cells`, `sectors`, `leaders`.
   - `show` deve autorizar líder/supervisor, carregar membros e últimos 10 relatórios, calcular média de presença, e retornar `Inertia::render('Cells/Show', [...])`.
   - `store` e `update` devem validar os campos conforme regras: `name` required|string|max:255, `sector_id` required|exists:sectors,id, `leader_id` nullable|exists:users,id, `meeting_time` date_format:H:i, `is_active` boolean. `update` deve impedir líderes de alterar `name`, `sector_id` e `leader_id` removendo essas rules se o authUser for leader.
   - `destroy` deve desvincular membros (`Member::where('cell_id',$id)->update(['cell_id' => null])`) antes de deletar a célula.

4) Rotas: adicionar `Route::resource('cells', CellController::class);` ao `routes/web.php` dentro do grupo `auth`.

5) Seeders: `SectorSeeder`, `CellSeeder` com 10 registros, `MemberSeeder` para popular alguns membros por célula. Incluir comandos `php artisan db:seed --class=CellSeeder`.

Responda apenas com o código PHP completo para cada arquivo (migration, model, controller, seeder, e trecho de rotas) — sem explicações adicionais." 

---

Fim do arquivo `MODULO_CELULA_BACKEND.md`.
