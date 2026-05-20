# Prompt: Módulo de Relatórios Semanais de Célula — Frequência, Financeiro, Atividades e Consolidação Analítica + PWA (Vanilla JS)

## Contexto
Projeto: Sistema MDA (ERP Eclesiástico). Este documento é um prompt/especificação para implementar ou migrar o módulo completo de **relatórios semanais de célula** em outro projeto já em andamento. 
Cobre a submissão de relatórios por líderes de célula, armazenamento de dados operacionais, sincronização com transações financeiras, consolidação hierárquica e relatórios analíticos avançados 
(DRE, BP, DMPL).

**Requisito Arquitetural Adicional:** Este módulo específico (`/reports` e suas sub-rotas) deve funcionar como um **PWA (Progressive Web App) isolado**, permitindo que líderes de célula instalem 
apenas esta ferramenta em seus celulares. A implementação do PWA deve ser feita **estritamente com JavaScript Vanilla** (sem bibliotecas externas para o Service Worker), utilizando um `manifest.json` com escopo restrito.

Use este arquivo para gerar migrations, modelos, controllers, validações, componentes frontend, relatórios consolidados, integração com o motor financeiro do projeto alvo e a estrutura nativa do PWA.

---

## Objetivo

Descrever de forma completa e independente o fluxo de submissão, visualização e análise de relatórios semanais de célula, incluindo: rotas REST, modelo de dados, regras de negócio, validações, 
UX do formulário wizard, consolidação hierárquica em tempo real, e critérios de aceite para funcionalidade total. Essa tela em específico deverá ser totalmente responsiva 
(Mobile First, Tablet, Desktop) e instalável via navegador.
**Stack Frontend:** Laravel com HTML5 (Blade Templates), Tailwind CSS, JavaScript (ES6+ Vanilla) e jQuery para interações e chamadas AJAX. **NÃO utilize frameworks reativos (como Vue ou React)**. 
Toda a lógica de interface, wizard e PWA deve ser feita com JS Puro e DOM Manipulation.

---

## Implementação PWA (JavaScript Vanilla Isolado)

O aplicativo deve permitir que o líder abra a rota `/reports/create` offline ou instale o módulo na tela inicial do celular. 

### 1. Web App Manifest Scoped (`public/manifest-reports.json`)
O manifesto deve ser configurado para restringir o app apenas ao módulo de relatórios:

```

```text
/mnt/data/prompt-modulo-relatorios-mda-pwa-vanilla-blade.md


```


json
{
"name": "Relatório de Célula - MDA",
"short_name": "Relatório Célula",
"start_url": "/reports",
"scope": "/reports/",
"display": "standalone",
"background_color": "#f3f4f6",
"theme_color": "#2563eb",
"icons": [
{ "src": "/icons/icon-192.png", "sizes": "192x192", "type": "image/png" },
{ "src": "/icons/icon-512.png", "sizes": "512x512", "type": "image/png" }
]
}

```

### 2. Injeção Condicional no Blade (`@stack` / `@push`)
O `manifest-reports.json` deve ser injetado **apenas** nas views do módulo de relatórios. Utilize diretivas do Blade para inserir a tag `<link rel="manifest">` no `<head>` do layout principal.

### 3. Service Worker Vanilla (`public/sw-reports.js`)
O Service Worker deve ser escrito em ES6+ puro.
**Requisitos do SW:**
- Interceptar eventos de `fetch` restritos ao escopo `/reports/`.
- Fazer o cache (Cache API) da página principal e assets estáticos (CSS/JS do Tailwind e scripts Vanilla) para garantir a abertura da interface offline.
- Estratégia recomendada para arquivos estáticos: *Cache First, then Network*.
- Estratégia para chamadas AJAX/Navegação: *Network First, fallback to Cache*.

**Registro no Frontend (JS Puro no final da view Blade):**
```javascript
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw-reports.js', { scope: '/reports/' })
            .then(reg => console.log('Reports PWA registrado no escopo:', reg.scope))
            .catch(err => console.error('Erro ao registrar Reports PWA:', err));
    });
}

```

---

## Entidades Principais e Propósito

### 1. Weekly Reports (`weekly_reports`)

Registro semanal de atividade de uma célula: frequência, visitantes, atividades pastorais, financeiro arrecadado, notas e metadados.

**Relacionamentos:**

* `cell_id` (FK) → Célula que enviou o relatório
* `present_member_ids` (JSON) → IDs dos membros presentes (para auditoria de presenças)
* `visitor_names` (JSON) → Nomes dos visitantes (para rastreamento de novo público)

**Campos de Frequência:**

* `committed_members` (int): Número total de membros comprometidos/inscritos
* `present_members` (int): Membros que compareceram
* `visitors` (int): Visitantes adultos novos
* `children` (int): Crianças que compareceram
* `other_cell_visitors` (int): Visitantes de outras células
* **Acessor (computed):** `total_presentes` = present_members + visitors + children

**Campos de Atividades:**

* `word_theme` (string): Tema da mensagem/ensinamento
* `meeting_location` (string): Local da endereço ou referência)
* `house_of_peace` (int): Casas de Paz abertas/ativas
* `mdas_done` (int): Acompanhamentos (MDA) realizados
* `kg_of_love` (decimal): Total em kg de alimentos/itens sociais coletados
* `reconciliations` (int): Reconciliações ou restaurações de relacionamentos

**Campos Financeiros:**

* `offer_cash` (decimal): Oferta em dinheiro/espécie
* `offer_pix` (decimal): Oferta via PIX/transferência digital
* **Acessor (computed):** `total_oferta` = offer_pix + offer_cash

**Campos Adicionais:**

* `conversions` (int): Decisões de conversão/batismo
* `notes` (text): Anotações livres do líder
* `meeting_date` (date): Data da reunião (UNIQUE por célula)
* `created_at`, `updated_at` (timestamps)

---

## Fluxo de Submissão (Wizard 5 Etapas - Vanilla JS)

Toda a lógica de transição entre as etapas deve ser feita manipulando o DOM com JavaScript Vanilla (adicionando/removendo classes `.hidden` do Tailwind).

### Etapa 1: Informações Gerais

* **Campos**: `word_theme`, `meeting_location`
* **Ação**: Exibir dica de preenchimento, validar campos via JS antes de permitir avançar.

### Etapa 2: Presença (Seleção de Membros)

* **Dados**: Lista de membros em checkboxes.
* **Interação**: JS nativo ou jQuery para escutar eventos de `change` e atualizar contador dinâmico.

### Etapa 3: Visitantes e Público

* **Campos**: `visitors`, `children`, `other_cell_visitors`
* **Interação adicional**: Manipulação de DOM para adicionar novas linhas na tabela de visitantes.
* **Acessor display**: Atualização em tempo real de "Total de Presentes" via JS.

### Etapa 4: Atividades e Financeiro

* **Atividades**: `house_of_peace`, `mdas_done`, `kg_of_love`, `reconciliations`
* **Financeiro**: `offer_cash`, `offer_pix`
* **Display**: Inputs com formatação de moeda (JS/jQuery mask) e totalizações em tempo real.

### Etapa 5: Resumo e Confirmação

* **Review**: Exibição dos dados do form populados via JS em tempo real.
* **Ação**: Submissão via AJAX (`fetch` API ou `$.ajax`) para evitar recarregar a página, com spinner de loading.

---

## Endpoints / Rotas REST

* `GET /reports` — Lista com paginação e filtros.
* `POST /reports` — Endpoint que recebe o AJAX do wizard, valida e salva.
* `GET /reports/create` — View Blade contendo o HTML e os scripts do wizard.
* `GET /reports/{id}` — Detalhes (pode retornar JSON para abrir em um modal populado via JS).
* `PUT /reports/{id}` — Editar via AJAX.
* `DELETE /reports/{id}` — Deletar via AJAX.
* `GET /reports/consolidation` — View de dashboard hierárquico com gráficos (usar Chart.js ou similar inicializado com Vanilla JS).

---

## Regras de Negócio Detalhadas

### Integração com Financeiro:

1. **Sincronização de Transações**:
* Ao salvar relatório, sincronizar com tabela `transactions`.
* `offer_cash > 0` cria transação `payment_method='cash'`.
* `offer_pix > 0` cria transação `payment_method='pix'`.



### Integração com Visitantes:

* Chamar rotina PHP `MemberService::syncVisitorsFromReport()` no backend após salvar.

---

## Validações (FormRequest PHP)

```php
[
    'cell_id' => 'required|exists:cells,id',
    'meeting_date' => 'required|date',
    'word_theme' => 'required|string|max:255',
    'present_members' => 'required|integer|min:0',
    'offer_pix' => 'nullable|numeric|min:0',
    'offer_cash' => 'nullable|numeric|min:0',
    // ... demais campos ...
]

```

---

## UX / Interações Essenciais (Frontend)

* **Modais e Drawers**: Controlados via Vanilla JS (alterando classes do Tailwind).
* **Filtros e Busca**: Input de busca acionando requisição AJAX (com debounce implementado em JS puro).
* **Wizard**: Barra de progresso atualizada dinamicamente com manipulação de atributos `style="width: %"`.

---

## Critérios de Aceitação

* ✅ CRUD funcional.
* ✅ PWA instalável com `manifest-reports.json`.
* ✅ Service Worker nativo interceptando requisições em `/reports/`.
* ✅ Interface Wizard responsiva, manipulada **100% com Vanilla JS e jQuery**, sem frameworks reativos.
* ✅ Sincronização automática com tabela financeira.
* ✅ Consolidação hierárquica por rede/setor.

---

## Integração Passo a Passo

1. Migrations e Models.
2. Controller com métodos CRUD que respondam tanto a views Blade normais quanto a requisições AJAX (retornando JSON).
3. Criação do layout Blade (`/reports/create`) com o HTML do Wizard.
4. Escrita do script Vanilla JS para controlar os passos do wizard, validação inline e máscara de campos.
5. Implementação do Manifesto e SW (`sw-reports.js`) em `public/`.
6. Registro do SW via Vanilla JS no Blade `@push('scripts')`.
"""

file_path = "/mnt/data/prompt-modulo-relatorios-mda-pwa-vanilla-blade.md"
with open(file_path, "w", encoding="utf-8") as f:
f.write(markdown_content)

print(file_path)

```
Peco desculpa pelo deslize! Deixei passar os vestígios do Vue e Inertia no primeiro ficheiro.

Acabei de reescrever o ficheiro, **removendo totalmente qualquer menção a Vue.js ou Inertia**. Agora o prompt está 100% alinhado com a sua stack: **Laravel (Blade) + Tailwind CSS + Vanilla JS 
(ES6+) + jQuery**.

A lógica do Wizard (as 5 etapas), os modais e o PWA (Service Worker) estão especificados para serem feitos exclusivamente com manipulação de DOM nativa e chamadas AJAX.

Aqui tem o novo ficheiro consolidado:
[file-tag: code-generated-file-0-1778866554528973012]

```