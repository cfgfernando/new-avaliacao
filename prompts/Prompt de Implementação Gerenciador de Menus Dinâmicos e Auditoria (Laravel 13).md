# Prompt de Implementação: Gerenciador de Menus Dinâmicos e Auditoria (Laravel 13)

Este documento contém a especificação técnica completa para a criação de um módulo de gerenciamento de menus com arrastar-e-soltar e um sistema de auditoria robusto.

---

## 1. Objetivo do Módulo
Implementar uma interface administrativa para controle total da sidebar do sistema, permitindo que o administrador crie categorias, adicione itens de menu e reorganize a ordem e a estrutura
 (mover itens entre categorias) visualmente. Todas as alterações e ações críticas no sistema devem ser registradas em um log de auditoria.

## 2. Requisitos Tecnológicos
- **Framework:** Laravel 13.7+
- **Frontend:** HTML5, Tailwind CSS (Vite)
- **Scripting:** JavaScript, JQuery, SortableJS
- **Banco de Dados:** MySQL
- **Feedback Visual: **Use Toastr ou SweetAlert2 para confirmar quando a ordem for salva com sucesso via AJAX

---

## 3. Estrutura de Dados (Migrations)

### `menu_categories`
| Campo | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | Identificador único |
| `name` | String | Nome da categoria (ex: "Relatórios") |
| `order` | Integer | Ordem de exibição da categoria |
| `is_active` | Boolean | Status de ativação |
| `timestamps` | Timestamps | data_criacao e data_atualizacao |

### `menus`
| Campo | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | Identificador único |
| `category_id` | BigInt (FK) | Relacionamento com categories |
| `title` | String | Rótulo do menu |
| `url` | String | Link de destino (relativo ou absoluto) |
| `icon` | String | Classe FontAwesome (ex: `fas fa-chart-line`) |
| `page_key` | String | Chave para identificar a página ativa no CSS |
| `order` | Integer | Posição dentro da categoria |
| `is_admin_only` | Boolean | Restrição de nível de acesso |
| `is_active` | Boolean | Status de ativação |
| `timestamps` | Timestamps | - |

### `audit_logs`
| Campo | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | Identificador único |
| `user_id` | BigInt (FK) | Usuário que realizou a ação |
| `action` | String | Nome da ação (ex: `UPDATE_MENU_ORDER`) |
| `description` | Text/JSON | Detalhes (Antes vs Depois, ou dados salvos) |
| `ip_address` | String | IP do autor |
| `user_agent` | String | Navegador/Dispositivo |
| `created_at` | Timestamp | Data exata da ocorrência |

---

## 4. Lógica de Backend (Laravel)

### Models & Relacionamentos
```php
// MenuCategory.php
public function items() {
    return $this->hasMany(Menu::class, 'category_id')->orderBy('order');
}

// Menu.php
public function category() {
    return $this->belongsTo(MenuCategory::class);
}
```

### Registro de Auditoria (Service)
Crie um serviço centralizado para disparar os logs:
```php
public static function log($action, $description = null) {
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'description' => is_array($description) ? json_encode($description) : $description,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}
```

---

## 5. Interface Frontend (Drag-and-Drop)

### Estrutura Blade (Resumida)
```html
<div class="menu-manager space-y-6">
    @foreach($categories as $category)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-lg mb-4 flex justify-between items-center">
                {{ $category->name }}
                <button onclick="editCategory({{ $category->id }})" class="text-blue-500 text-sm">Editar</button>
            </h3>
            
            <ul class="sortable-list min-h-[50px] space-y-2" data-category-id="{{ $category->id }}">
                @foreach($category->items as $item)
                    <li class="bg-gray-50 dark:bg-gray-900 p-3 rounded border flex items-center justify-between group" data-id="{{ $item->id }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-grip-vertical text-gray-400 drag-handle cursor-grab"></i>
                            <i class="{{ $item->icon }}"></i>
                            <span>{{ $item->title }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editItem({{ $item->id }})" class="p-1 hover:text-blue-500"><i class="fas fa-edit"></i></button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
```

### Implementação JS (SortableJS)
```javascript
import Sortable from 'sortablejs';

document.querySelectorAll('.sortable-list').forEach(el => {
    new Sortable(el, {
        group: 'menu-items',
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'opacity-50',
        onEnd: async (evt) => {
            const itemId = evt.item.dataset.id;
            const newCategoryId = evt.to.dataset.categoryId;
            const itemIds = Array.from(evt.to.children).map(li => li.dataset.id);

            try {
                await axios.post('/admin/menus/reorder', {
                    item_id: itemId,
                    category_id: newCategoryId,
                    order: itemIds
                });
                // Log de Auditoria disparado no Controller
            } catch (error) {
                console.error('Erro ao salvar nova ordem');
            }
        }
    });
});
```

---

## 6. Auditoria de Sistema (Visualização)
- Criar uma view com Tailwind para listar os registros de `audit_logs`.
- Utilizar cores para diferentes ações (Verde para criação, Amarelo para edição, Vermelho para exclusão).
- Exibir detalhes em um modal ou linha expansível com `JSON.stringify` formatado para facilitar a leitura técnica.

---

## 7. Diretrizes de Design Premium
- **Interatividade:** Adicione transições suaves (`transition-all duration-300`) ao arrastar itens.
- **Micro-interações:** Ícone de grip (`drag-handle`) deve mudar de cor ao passar o mouse.
- **Empty States:** Se uma categoria não tiver itens, mostrar uma mensagem "Arraste um item aqui" com borda tracejada.
- **Modo Escuro:** Garantir contraste perfeito usando a paleta `slate` ou `zinc` do Tailwind.


Gere os códigos completos baseando-se nesta descrição, garantindo que as rotas estejam protegidas por middleware de autenticação e que o frontend utilize Vite para compilar os assets."