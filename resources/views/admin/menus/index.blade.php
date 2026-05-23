@extends('layouts.app')

@section('title', 'Gerenciador de Menus')

@section('content')
<style>
    .sortable-list:empty::after {
        content: 'ARRASTE ITENS AQUI';
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100px;
        border: 2px dashed #e2e8f0;
        border-radius: 0.75rem;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 0.1em;
        width: 100%;
    }
</style>
<div class="menu-manager space-y-8 animate-reveal-up">
    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">ESTRUTURA DE NAVEGAÇÃO</h2>
            <p class="text-primary-light font-medium mt-1">Arraste e solte para organizar categorias e itens.</p>
        </div>
        <div class="flex gap-4">
            <button onclick="openModal('categoryModal')" class="btn-neo bg-slate-800 text-white hover:bg-slate-900">
                <i class="fas fa-folder-plus"></i>
                <span>Nova Categoria</span>
            </button>
            <button onclick="openModal('itemModal')" class="btn-neo btn-primary">
                <i class="fas fa-plus"></i>
                <span>Novo Item</span>
            </button>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 categories-grid">
        @foreach($categories as $category)
            <div class="card-neo rounded-xl border border-slate-200 !p-0 overflow-hidden" data-category-id="{{ $category->id }}">
                <div class="bg-slate-50 border-b border-slate-200 p-5 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="category-handle cursor-grab active:cursor-grabbing w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-accent hover:bg-accent hover:text-white transition-all">
                            <i class="fas fa-layer-group text-xs"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-wider text-sm">{{ $category->name }}</h3>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Numeric Order Input -->
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ordem</span>
                            <input type="number" 
                                   value="{{ $category->order }}" 
                                   onchange="updateCategoryOrderByNumber({{ $category->id }}, this.value)"
                                   class="w-12 bg-transparent text-center text-xs font-black text-primary focus:outline-none"
                            >
                        </div>

                        <div class="flex gap-1 border-l border-slate-200 pl-3">
                            <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->order }})" class="p-2 hover:bg-white rounded-lg transition-colors text-slate-400 hover:text-blue-500">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <ul class="sortable-list min-h-[120px] space-y-3 p-2 rounded-xl bg-slate-50/50" data-category-id="{{ $category->id }}">
                        @foreach($category->items as $item)
                            <li class="group bg-white border border-slate-200 p-4 rounded-xl flex items-center justify-between shadow-sm hover:shadow-md hover:border-accent/30 transition-all duration-300 cursor-default {{ !$item->is_active ? 'opacity-50 grayscale bg-slate-50' : '' }}" data-id="{{ $item->id }}">
                                <div class="flex items-center gap-4">
                                    <div class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 group-hover:text-accent transition-colors p-1">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="w-10 h-10 rounded-lg {{ $item->is_active ? 'bg-slate-50 text-slate-800 group-hover:bg-accent group-hover:text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center transition-all duration-500">
                                        <i class="{{ $item->icon ?: 'fas fa-link' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm tracking-tight flex items-center gap-2">
                                            {{ $item->title }}
                                            @if(!$item->is_active)
                                                <span class="text-[8px] bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded uppercase font-black tracking-widest">Inativo</span>
                                            @endif
                                        </p>
                                        <p class="text-[10px] font-bold text-primary-light uppercase tracking-widest opacity-60">{{ $item->url }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form action="{{ route('admin.menus.toggle', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 hover:bg-slate-50 rounded-lg {{ $item->is_active ? 'text-slate-400 hover:text-amber-500' : 'text-amber-500 hover:text-emerald-500' }} transition-colors" title="{{ $item->is_active ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas {{ $item->is_active ? 'fa-eye-slash' : 'fa-eye' }} text-[10px]"></i>
                                        </button>
                                    </form>
                                    <button onclick="editItem({{ json_encode($item) }})" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-blue-500 transition-colors">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.menus.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $item->id }})" class="p-2 hover:bg-rose-50 rounded-lg text-slate-400 hover:text-rose-500 transition-colors">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('modals')
<!-- Modal Novo Item -->
<div id="itemModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg m-auto overflow-hidden animate-reveal-up border border-slate-200 flex flex-col">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <h3 id="itemModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Novo Item de Menu</h3>
            <button onclick="closeModal('itemModal')" class="text-slate-400 hover:text-slate-800"><i class="fas fa-times"></i></button>
        </div>
        <form id="itemForm" action="{{ route('admin.menus.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="itemMethod" value="POST">
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Categoria Pai</label>
                <select name="category_id" id="itemCategory" class="input-neo">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Título do Link</label>
                <input type="text" name="title" id="itemTitle" required class="input-neo" placeholder="Ex: Membros">
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">URL / Rota</label>
                    <input type="text" name="url" id="itemUrl" required class="input-neo" placeholder="/members">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Ícone (FontAwesome)</label>
                    <input type="text" name="icon" id="itemIcon" class="input-neo" placeholder="fas fa-users">
                </div>
            </div>
            
            <!-- Sugestões de Ícones -->
            <div class="bg-slate-50 p-4 rounded-xl">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Sugestões de Ícones</label>
                <div class="grid grid-cols-6 gap-2">
                    @php
                        $suggestedIcons = [
                            'fas fa-home', 'fas fa-users', 'fas fa-chart-line', 'fas fa-cog', 'fas fa-calendar', 'fas fa-file-invoice-dollar',
                            'fas fa-church', 'fas fa-hand-holding-heart', 'fas fa-user-tie', 'fas fa-pray', 'fas fa-book-open', 'fas fa-envelope'
                        ];
                    @endphp
                    @foreach($suggestedIcons as $icon)
                        <button type="button" onclick="selectIcon('{{ $icon }}')" class="w-10 h-10 rounded-lg bg-white border border-slate-100 text-slate-400 hover:text-accent hover:border-accent hover:shadow-sm transition-all flex items-center justify-center">
                            <i class="{{ $icon }} text-xs"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" id="itemSubmitBtn" class="btn-neo btn-primary w-full py-4 text-sm">CRIAR ITEM AGORA</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nova/Editar Categoria -->
<div id="categoryModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg m-auto overflow-hidden animate-reveal-up border border-slate-200 flex flex-col">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <h3 id="categoryModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Nova Categoria</h3>
            <button onclick="closeModal('categoryModal')" class="text-slate-400 hover:text-slate-800"><i class="fas fa-times"></i></button>
        </div>
        <form id="categoryForm" action="{{ route('admin.menus.categories.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="categoryMethod" value="POST">
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Nome da Categoria</label>
                <input type="text" name="name" id="categoryName" required class="input-neo" placeholder="Ex: Gestão Financeira">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2 px-1">Ordem de Exibição</label>
                <input type="number" name="order" id="categoryOrder" required class="input-neo" placeholder="Ex: 1">
            </div>
            <div class="pt-4">
                <button type="submit" id="categorySubmitBtn" class="btn-neo bg-slate-800 text-white w-full py-4 text-sm shadow-md transition-colors">CRIAR CATEGORIA</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script src="{{ asset('vendor/sortablejs/Sortable.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    function initSortable() {
        if (typeof Sortable === 'undefined') return;

        // Inicializar Sortable para o Grid de Categorias (Reordenar Categorias)
        const categoriesGrid = document.querySelector('.categories-grid');
        if (categoriesGrid) {
            new Sortable(categoriesGrid, {
                animation: 300,
                handle: '.category-handle',
                ghostClass: 'opacity-20',
                onEnd: async (evt) => {
                    const categoryIds = Array.from(categoriesGrid.children)
                                             .map(el => el.dataset.categoryId)
                                             .filter(id => id);
                    
                    try {
                        await window.axios.post('{{ route('admin.menus.categories.reorder') }}', {
                            order: categoryIds
                        });
                        
                        showToast('Ordem das categorias atualizada!');
                    } catch (error) {
                        showError('Erro ao salvar ordem das categorias.');
                    }
                }
            });
        }

        // Inicializar Sortable para cada lista de itens
        document.querySelectorAll('.sortable-list').forEach(el => {
            new Sortable(el, {
                group: 'menu-items',
                animation: 300,
                handle: '.drag-handle',
                ghostClass: 'opacity-20',
                dragClass: 'shadow-2xl',
                onEnd: async (evt) => {
                    const itemId = evt.item.dataset.id;
                    const newCategoryId = evt.to.dataset.categoryId;
                    const itemIds = Array.from(evt.to.children)
                                         .map(li => li.dataset.id)
                                         .filter(id => id);

                    if (!window.axios) return;

                    try {
                        const response = await window.axios.post('{{ route('admin.menus.reorder') }}', {
                            item_id: itemId,
                            category_id: newCategoryId,
                            order: itemIds
                        });
                        
                        showToast(response.data.message);
                    } catch (error) {
                        showError('Não foi possível salvar a nova ordem.');
                    }
                }
            });
        });
    }

    function showToast(message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            background: '#1e293b',
            color: '#fff'
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: message,
            confirmButtonColor: '#2563eb'
        });
    }

    async function updateCategoryOrderByNumber(id, newOrder) {
        try {
            await window.axios.post('{{ route('admin.menus.categories.reorder-single') }}', {
                id: id,
                order: newOrder
            });
            showToast('Ordem atualizada! Recarregando...');
            setTimeout(() => location.reload(), 1000);
        } catch (error) {
            showError('Erro ao atualizar ordem.');
        }
    }

    document.addEventListener('DOMContentLoaded', initSortable);
    document.addEventListener('htmx:afterSettle', initSortable);


    function editCategory(id, name, order) {
        const form = document.getElementById('categoryForm');
        form.action = `/admin/menus/categories/${id}`;
        document.getElementById('categoryMethod').value = 'PUT';
        document.getElementById('categoryName').value = name;
        document.getElementById('categoryOrder').value = order || 0;
        document.getElementById('categoryModalTitle').innerText = 'Editar Categoria';
        document.getElementById('categorySubmitBtn').innerText = 'SALVAR ALTERAÇÕES';
        openModal('categoryModal');
    }

    function editItem(item) {
        const form = document.getElementById('itemForm');
        form.action = `/admin/menus/${item.id}`;
        document.getElementById('itemMethod').value = 'PUT';
        document.getElementById('itemTitle').value = item.title;
        document.getElementById('itemUrl').value = item.url;
        document.getElementById('itemIcon').value = item.icon || '';
        document.getElementById('itemCategory').value = item.category_id;
        document.getElementById('itemModalTitle').innerText = 'Editar Item de Menu';
        document.getElementById('itemSubmitBtn').innerText = 'SALVAR ALTERAÇÕES';
        openModal('itemModal');
    }

    function selectIcon(icon) {
        document.getElementById('itemIcon').value = icon;
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Reset forms when closing if they were in "Edit" mode
            if (id === 'categoryModal') {
                document.getElementById('categoryForm').action = '{{ route('admin.menus.categories.store') }}';
                document.getElementById('categoryMethod').value = 'POST';
                document.getElementById('categoryName').value = '';
                document.getElementById('categoryModalTitle').innerText = 'Nova Categoria';
                document.getElementById('categorySubmitBtn').innerText = 'CRIAR CATEGORIA';
            }
            if (id === 'itemModal') {
                document.getElementById('itemForm').action = '{{ route('admin.menus.store') }}';
                document.getElementById('itemMethod').value = 'POST';
                document.getElementById('itemForm').reset();
                document.getElementById('itemModalTitle').innerText = 'Novo Item de Menu';
                document.getElementById('itemSubmitBtn').innerText = 'CRIAR ITEM AGORA';
            }
        }
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Excluir este item?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'SIM, EXCLUIR!',
            cancelButtonText: 'CANCELAR',
            background: '#ffffff',
            customClass: {
                title: 'text-slate-800 font-black uppercase tracking-tight',
                popup: 'rounded-xl border border-slate-200 shadow-2xl',
                confirmButton: 'btn-neo bg-rose-500 text-white hover:bg-rose-600 px-6 py-3 rounded-lg font-black uppercase tracking-widest text-[10px] mx-2 shadow-md shadow-rose-500/10',
                cancelButton: 'btn-neo bg-slate-100 text-slate-500 hover:bg-slate-200 px-6 py-3 rounded-lg font-black uppercase tracking-widest text-[10px] mx-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endpush
