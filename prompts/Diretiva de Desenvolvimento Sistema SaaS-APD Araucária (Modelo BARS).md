# Diretiva de Desenvolvimento: Sistema SaaS-APD Araucária (Modelo BARS)

Você é um engenheiro de software Full-Stack sênior especialista em sistemas de gestão pública (GovTech) e direito administrativo brasileiro. Seu objetivo é reproduzir integralmente o sistema 
**SaaS-APD Araucária**, uma plataforma robusta de Avaliação Periódica de Desempenho desenvolvida para os servidores públicos municipais com foco em progressão de carreira estável baseada na
 metodologia BARS (escala ancorada por comportamentos).

---

## 1. Visão Geral do Sistema e Roteiros BARS
O sistema gerencia avaliações de desempenho no funcionalismo público, reduzindo a subjetividade de notas arbitrárias e garantindo a ampla defesa e segurança processual para resistir a processos 
jurídicos.

### As Categorias de Servidores e Roteiros Aplicáveis:
Cada funcionário possui uma categoria com 40 perguntas específicas (5 para cada um dos 8 critérios regulamentares):
- `'saude'`: Médicos, enfermeiros, técnicos fáticos e atendentes.
- `'guarda'`: Policiamento preventivo, patrulha, disciplina e porte técnico.
- `'quadro_geral'`: Cargos burocráticos, analistas de sistemas e escriturários.
- `'educacao'`: Professores, pedagogos fáticos e auxiliares escolares.

### Os 8 Critérios Regulamentares de Avaliação (de 'a' a 'h'):
- **a)** Assiduidade e Pontualidade
- **b)** Disciplina
- **c)** Capacidade de Iniciativa
- **d)** Responsabilidade
- **e)** Cooperação
- **f)** Qualidade do Trabalho Executado
- **g)** Participação em Programas de Desenvolvimento de RH
- **h)** Avaliação pelo Usuário do Serviço Prestado

---

## 2. Matriz de Perfis de Usuário & ACL (Permissions Matrix)

- **Chefia Imediata (Avaliador)**: Visualiza subordinados de seu setor, inicia avaliações e as finaliza mediante validações de segurança.
- **Servidor Público (Avaliado)**: Acompanha seu histórico de avaliações finalizadas e interpõe recursos técnicos contra notas específicas.
- **Comissão CAPD Araucária (Administrador/Auditor)**: Modifica pesos ponderados dos ciclos, acompanha painéis estatísticos com gráficos de radar e julga em definitivo os recursos dos servidores 
(atualizando as notas reajustadas de forma síncrona).

---

## 3. Estrutura do Banco de Dados (Esquema de Tabelas Relacionais)

Implemente ou simule os modelos de dados baseados no seguinte esquema:

### Tabela: `employees` (Cadastro de Servidores)
- `id` (VARCHAR(36), PK): UUID.
- `registration` (VARCHAR(30), UNIQUE): Matrícula funcional (ex: `25441-2`).
- `name` (VARCHAR(255)): Nome do servidor públicos.
- `role` (VARCHAR(150)): Cargo (ex: "Guarda Municipal", "Educador Infantil").
- `category` (VARCHAR(50)): Categoria do estatuto (`saude`, `guarda`, `quadro_geral`, `educacao`).
- `department` (VARCHAR(150)): Setor (ex: "UPA Centro").
- `admission_date` (DATE): Data de nomeação.
- `chefia_id` (VARCHAR(36)): ID do superior imediato.

### Tabela: `evaluation_cycles` (Ciclos de Avaliacao)
- `id` (VARCHAR(36), PK): ex: `cycle_2026_1`.
- `name` (VARCHAR(250)): Nome fantasia (ex: "Ciclo Primeiro Semestre").
- `start_date` / `end_date` (DATE): Datas do ciclo.
- `status` (VARCHAR(30)): `'rascunho'`, `'ativo'`, `'encerrado'`.
- `weights` (JSON): Pesos de cada critério de `'a'` até `'h'` (Mapeamento fático, ex: `{"a": 2, "b": 1, ...}`).
- `cutoff_score` (DECIMAL(5,2)): Nota de corte para mérito de progressão (Default: `70.00`).

### Tabela: `evaluation_responses` (Resumos e Notas)
- `id` (VARCHAR(36), PK): ID da avaliação preenchida.
- `employee_id` / `cycle_id` (FK): Chaves estrangeiras.
- `answers` (JSON): Mapas de `question_id` para nota inteira de 1 a 5 (ex: `{"saude_a_1": 4}`).
- `justifications` (JSON): Textos de justificativa fática para as notas dadas (obrigatório para extremos).
- `attachments` (JSON): Metadados de provas documentais de incidentes críticos em formato anexo.
- `final_score` (DECIMAL(5,2)): Nota ponderada final resultante de $0.00$ a $100.00$.
- `status` (VARCHAR(30)): `'rascunho'` ou `'finalizada'`.
- `ponto_faltas_injustas` / `ponto_atrasos_no_ciclo` / `ponto_horas_extras` / `formacao_horas` (INT): Dados consolidados de ponto externo e RH.
- `pad_ativo` (BOOLEAN): Aponta se há processo administrativo ativo no período.

### Tabela: `resource_appeals` (Recursos Administrativos)
- `id` (VARCHAR(36), PK): ID do recurso.
- `evaluation_id` / `employee_id` (FK): Chaves referenciadas.
- `question_id` (VARCHAR(50)): Pergunta contestada faticamente.
- `assigned_score` (INT): Nota dada originalmente.
- `requested_score` (INT): Nota que o servidor busca obter.
- `justification` (TEXT): Justificativa do recurso.
- `attachment_path` (VARCHAR(255)): Arquivo de prova do recurso.
- `status` (VARCHAR(30)): `'pendente'`, `'deferido'`, `'indeferido'`.
- `capd_notes` (TEXT): Acórdão fundamentado da CAPD Araucária.

---

## 4. Regras de Negócio e Cálculos de Escore

1. **Cálculo da Nota Final ponderada ($NF$)**:
   - Cada critério tem sua média calculada na escala $1$ a $5$ e então convertida para percentual de $100$:
     $$S_{crit} = \left(\frac{\text{Média do critério de 5 perguntas}}{5}\right) \times 100$$
   - Aplica-se a fórmula da média ponderada com os pesos do ciclo ($W_{crit}$):
     $$NF = \frac{\sum (S_{crit} \times W_{crit})}{\sum W_{crit}}$$
   - Se um critério opcional não contiver respostas (ex: cargo sem contato com usuário externo), adota-se o escore neutro e básico de $60.00$ na fórmula para evitar perdas funcionais.

2. **Compliance Legal de Notas Extremas e Incidentes Críticos**:
   - Sempre que a chefia atribuir as notas **1, 2 ou 5**, os seguintes mecanismos de validação previnem erros materiais no backend e frontend:
     - **Justificativa Fática Obrigatória**: Mínimo de **30 caracteres**, descrevendo o acontecido concreto (evitando adjetivos vazios).
     - **Prova Documental Integrada**: Upload obrigatório em anexo para justificar a anomalia funcional.
     - **Bloqueio Síncrono**: O backend recusa salvar a avaliação como `'finalizada'` (retornando erro `HTTP 420`) se essas regras de incidentes fáticos forem omitidas.

3. **Gatilho de Deferimento CAPD**:
   - Ao julgar um recurso como `'deferido'`, o sistema atualiza a nota na tabela `evaluation_responses` correspondente e recalcula a média ponderada do servidor público de forma imediata e indolor
   para a progressão funcional.

---

## 5. Módulo de Inteligência Artificial (Validação com Gemini API)

Sempre que redigida uma justificativa de notas extremas ($1, 2$ ou $5$), o sistema deve usar a inteligência artificial da Google para fazer a auditoria jurídica legal preemptiva e emitir o parecer.

Você deve implementar uma rota proxy no backend invocando o modelo `gemini-3.5-flash` com as seguintes configurações:
- **System Instruction**: *"Você é um auditor sênior da CAPD do Município de Araucária. Exija detalhamento fático nas notas 1, 2 ou 5 para evitar judicialização de avaliações de desempenho. Responda 
em formato estruturado JSON."*
- **responseMimeType**: `"application/json"`
- **Response Schema Exigido**:
  - `aprovada` (BOOLEAN): avalia se a justificativa tem força jurídica fática descritiva.
  - `parecer` (STRING): análise técnica concisa da motivação fática.
  - `sugestao` (STRING): sugestão literal de reescrita profissional focada no evento.

```json
// Resposta JSON estruturada esperada
{
  "aprovada": true,
  "parecer": "...",
  "sugestao": "..."
}