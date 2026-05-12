# Gestão de Usuários, Perfis e Permissões — Prompt Completo

> Arquivo unificado contendo o prompt, especificações e todos os arquivos de frontend (Blade + Tailwind + jQuery + SortableJS) sugeridos para um projeto Laravel 13.7+.

---

## Objetivo

Fornecer um prompt completo e autossuficiente para implementar um módulo de Gestão de Usuários, Perfis (Roles) e Permissões em Laravel 13.7+, com frontend em Blade + Tailwind (Vite), 
JavaScript/jQuery e interações com SortableJS. O conteúdo abaixo inclui requisitos, modelagem, contratos de API, além dos arquivos de frontend prontos para copiar/colar.

---

## Instruções gerais (contexto)

- Framework: Laravel 13.7+
- Frontend: Blade + Tailwind CSS (Vite)
- Scripting: JavaScript, jQuery, SortableJS
- Banco de Dados: MySQL
- Feedback visual: Toastr ou SweetAlert2 para confirmações via AJAX

### Design System — Fase 3 (resumo)
- Sidebar: Deep Navy (`#1c2434`) com textos e ícones.
- Accent: Laranja/Âmbar vibrante `#f59e0b` (botões, itens ativos).
- Background: Off-white/very light gray `#f1f5f9`.
- Cards: Brancos, cantos arredondados, sombra sutil.
- Cores semânticas: blue `#3b82f6`, green `#10b981`, purple `#8b5cf6`.

---

# Conteúdo do prompt (use este texto como "prompt" para desenvolvedores ou IA)

## Resumo do que implementar
- CRUD completo: Usuários, Perfis (roles) e Permissões.
- Atribuição de múltiplos perfis a usuários e múltiplas permissões a perfis.
- Ordenação de perfis via drag & drop com SortableJS e salvamento via AJAX.
- Auditoria de alterações (antes/depois em JSON).
- Políticas e checks de autorização (policies/gates).
- Frontend em Blade + Tailwind + jQuery seguindo o Design System.

## Arquitetura recomendada
- Models: `User`, `Role`, `Permission`, `AuditLog` (ou usar `spatie/laravel-permission`).
- Controllers: `UserController`, `RoleController`, `PermissionController`, `RoleOrderController`.
- Policies: `UserPolicy`, `RolePolicy`, `PermissionPolicy`.
- Requests: `StoreUserRequest`, `UpdateUserRequest`, etc.
- Migrations: `roles` com campo `order` para manter posição; pivot tables `role_user`, `permission_role`.
- Routes sob prefixo `/admin` com middleware `auth` e `can`.

---

# Arquivos e snippets (copiar/colar)

## 1) `tailwind.config.js`
```js
// tailwind.config.js
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#1c2434',
          dark: '#111827',
          light: '#8a99af',
        },
        accent: {
          DEFAULT: '#f59e0b',
          hover: '#d97706',
        },
        background: {
          DEFAULT: '#f1f5f9',
        },
        semantic: {
          blue: '#3b82f6',
          green: '#10b981',
          purple: '#8b5cf6',
        }
      }
    },
  },
  plugins: [],
}
```

---

## 2) Layout Mestre — `resources/views/layouts/app.blade.php`
```blade
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name') }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
  <!-- Toastr / SweetAlert2 CDN (ou instale via npm) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-minimal/minimal.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>
<body class="min-h-screen flex bg-[#f1f5f9]">
  <div id="app" class="flex w-full">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 min-h-screen bg-[#1c2434] text-white hidden md:block">
      <div class="p-4 border-b border-[#111827]">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
          <div class="text-2xl font-bold text-white">ERP</div>
        </a>
      </div>

      <nav class="p-4">
        <ul class="space-y-1">
          <li>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md bg-[#f59e0b] text-white font-semibold">
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-[#8a99af] hover:text-white">
              <span class="text-[#8a99af]">Usuários</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-[#8a99af] hover:text-white">
              <span>Perfis</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.permissions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-[#8a99af] hover:text-white">
              <span>Permissões</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 min-h-screen">
      <!-- Topbar -->
      <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
          <div class="flex items-center gap-4">
            <button id="toggleSidebar" class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100">
              <svg class="w-6 h-6" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
          </div>

          <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
              <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}" alt="avatar" class="w-8 h-8 rounded-full object-cover">
              <span class="text-gray-700">{{ auth()->user()->name ?? 'Usuário' }}</span>
            </div>
          </div>
        </div>
      </header>

      <main class="p-6">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
    window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    $(function(){
      // Toggle sidebar on mobile
      $('#toggleSidebar').on('click', function(){
        $('#sidebar').toggleClass('hidden');
      });

      // Toastr default
      toastr.options = { "positionClass": "toast-top-right", "timeOut": "3000" };
    });
  </script>
  @stack('scripts')
</body>
</html>
```

---

## 3) Dashboard View — `resources/views/dashboard.blade.php`
```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
      <div class="w-16 h-16 flex items-center justify-center rounded-xl bg-blue-100 text-blue-500 mr-4">
        <svg class="w-7 h-7" fill="currentColor"><path d="..."/></svg>
      </div>
      <div>
        <div class="text-sm font-medium text-gray-500">Total de Usuários</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['users'] ?? 0 }}</div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
      <div class="w-16 h-16 flex items-center justify-center rounded-xl bg-green-100 text-green-500 mr-4">
        <svg class="w-7 h-7" fill="currentColor"><path d="..."/></svg>
      </div>
      <div>
        <div class="text-sm font-medium text-gray-500">Ativos nos últimos 7 dias</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['active_7'] ?? 0 }}</div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
      <div class="w-16 h-16 flex items-center justify-center rounded-xl bg-purple-100 text-purple-500 mr-4">
        <svg class="w-7 h-7" fill="currentColor"><path d="..."/></svg>
      </div>
      <div>
        <div class="text-sm font-medium text-gray-500">Perfis</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['roles'] ?? 0 }}</div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
      <div class="w-16 h-16 flex items-center justify-center rounded-xl bg-yellow-100 text-[#f59e0b] mr-4">
        <svg class="w-7 h-7" fill="currentColor"><path d="..."/></svg>
      </div>
      <div>
        <div class="text-sm font-medium text-gray-500">Permissões</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['permissions'] ?? 0 }}</div>
      </div>
    </div>
  </div>

  <!-- Recent Records Table -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
      <h2 class="text-lg font-semibold text-gray-800">Registros Recentes</h2>
      <div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-[#f59e0b] text-white rounded-lg shadow-sm hover:bg-[#d97706]">
          Novo Usuário
        </a>
      </div>
    </div>

    <div class="p-4">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gray-50">
            <tr class="uppercase text-xs font-semibold text-gray-500">
              <th class="p-3">Nome</th>
              <th class="p-3">Email</th>
              <th class="p-3">Perfis</th>
              <th class="p-3">Status</th>
              <th class="p-3">Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recent as $user)
              <tr class="border-b border-gray-100">
                <td class="p-3">{{ $user->name }}</td>
                <td class="p-3">{{ $user->email }}</td>
                <td class="p-3">
                  @foreach($user->roles as $role)
                    <span class="inline-block bg-green-50 text-green-600 font-medium text-xs px-2.5 py-0.5 rounded-full border border-green-200">{{ $role->label }}</span>
                  @endforeach
                </td>
                <td class="p-3">
                  @if($user->active)
                    <span class="bg-green-50 text-green-600 font-medium text-xs px-2.5 py-0.5 rounded-full border border-green-200">ATIVO</span>
                  @else
                    <span class="bg-gray-50 text-gray-500 font-medium text-xs px-2.5 py-0.5 rounded-full border border-gray-100">INATIVO</span>
                  @endif
                </td>
                <td class="p-3">
                  <a href="{{ route('admin.users.edit', $user->id) }}" class="text-[#f59e0b] font-medium mr-3">Editar</a>
                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmar exclusão?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 font-medium">Excluir</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $recent->links() }}
      </div>
    </div>
  </div>

  <!-- Roles Order (Drag & Drop) -->
  <div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Ordenar Perfis</h3>
      <button id="saveRoleOrder" class="px-4 py-2 bg-[#f59e0b] text-white rounded-md">Salvar ordem</button>
    </div>

    <ul id="rolesList" class="space-y-2">
      @foreach($roles as $role)
        <li data-id="{{ $role->id }}" class="p-3 border rounded-md flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div>
              <div class="font-medium text-gray-800">{{ $role->label }}</div>
              <div class="text-xs text-gray-500">{{ $role->name }}</div>
            </div>
          </div>
          <div class="text-sm text-gray-400 cursor-move">⠿</div>
        </li>
      @endforeach
    </ul>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(function(){
    // Sortable for roles
    const rolesList = document.getElementById('rolesList');
    const sortable = Sortable.create(rolesList, { animation: 150, handle: '.cursor-move, .text-gray-400' });

    $('#saveRoleOrder').on('click', function(){
      const order = $('#rolesList').children().map(function(){ return $(this).data('id'); }).get();
      $.ajax({
        url: '{{ route("admin.roles.order") }}',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { order },
        success: function(res){
          toastr.success(res.message || 'Ordem salva com sucesso');
        },
        error: function(xhr){
          Swal.fire({ icon: 'error', title: 'Erro', text: xhr.responseJSON?.message || 'Falha ao salvar ordem' });
        }
      });
    });
  });
</script>
@endpush
```

---

## 4) Routes e Controller (exemplos)

### `routes/web.php`
```php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function(){
    Route::post('roles/order', [App\Http\Controllers\Admin\RoleController::class, 'order'])->name('roles.order');
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
});
```

### `app/Http/Controllers/Admin/RoleController.php` (método `order`)
```php
public function order(Request $request)
{
    $this->authorize('manage_roles');

    $order = $request->input('order', []);
    foreach ($order as $index => $id) {
        \DB::table('roles')->where('id', $id)->update(['order' => $index + 1]);
    }

    return response()->json(['success' => true, 'message' => 'Ordem salva com sucesso']);
}
```

---

## 5) Dependências (composer / npm)

Composer (opcional):
```bash
composer require spatie/laravel-permission
```

NPM:
```bash
npm install -D tailwindcss postcss autoprefixer
npm install jquery sortablejs toastr sweetalert2
```

---

## 6) Notas de integração e boas práticas rápidas
- Use `@csrf` em formulários e `@method('PUT')` / `@method('DELETE')` onde necessário.
- Padronize componentes Blade (`resources/views/components`) como `x-card`, `x-badge`, `x-button`.
- Internacionalize strings com `__('Texto')`.
- Crie Factories e Seeders para perfis e permissões iniciais.
- Registre auditoria no `AuditLog` em `store`, `update`, `destroy` (dados_antigos/dados_novos em JSON).
- Adicione testes `Feature` cobrindo criação/edição/exclusão e ordenação via AJAX.

---

## Fim
Arquivo gerado automaticamente contendo todo o prompt e os códigos solicitados.

Se quiser, eu posso:
- Criar fisicamente os arquivos `resources/views/layouts/app.blade.php` e `resources/views/dashboard.blade.php` no seu projeto agora.
- Gerar componentes Blade reutilizáveis (`x-card`, `x-badge`).

Caminho do arquivo criado: `docs/prompt_gestao_usuarios_perfis_permissoes.md`
