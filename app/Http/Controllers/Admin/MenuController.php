<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Exibe o gerenciador de menus.
     */
    public function index(): View
    {
        $categories = MenuCategory::with(['items' => function($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        return view('admin.menus.index', compact('categories'));
    }

    /**
     * Cria uma nova categoria.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxOrder = MenuCategory::max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        $category = MenuCategory::create($validated);

        AuditService::log('CREATE_MENU_CATEGORY', $category->toArray());

        return back()->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Salva a nova ordem e estrutura dos menus.
     */
    public function reorder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'item_id' => 'required|exists:menus,id',
                'category_id' => 'required|exists:menu_categories,id',
                'order' => 'required|array',
            ]);

            $itemId = $request->item_id;
            $newCategoryId = $request->category_id;
            $order = $request->order;

            foreach ($order as $index => $id) {
                Menu::where('id', $id)->update([
                    'category_id' => $newCategoryId,
                    'order' => $index + 1
                ]);
            }

            AuditService::log('UPDATE_MENU_ORDER', [
                'item_id' => $itemId,
                'new_category_id' => $newCategoryId,
                'new_order' => $order
            ]);

            return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adiciona um novo item de menu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string',
            'icon' => 'nullable|string',
            'is_admin_only' => 'boolean',
        ]);

        $maxOrder = Menu::where('category_id', $request->category_id)->max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        $menu = Menu::create($validated);

        AuditService::log('CREATE_MENU_ITEM', $menu->toArray());

        return back()->with('success', 'Item de menu criado!');
    }

    /**
     * Atualiza uma categoria.
     */
    public function updateCategory(Request $request, MenuCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        AuditService::log('UPDATE_MENU_CATEGORY', $category->toArray());

        return back()->with('success', 'Categoria atualizada!');
    }

    /**
     * Remove um item de menu.
     */
    public function destroy(Menu $menu)
    {
        $menuData = $menu->toArray();
        $menu->delete();

        AuditService::log('DELETE_MENU_ITEM', $menuData);

        return back()->with('success', 'Item de menu removido!');
    }

    /**
     * Alterna o status ativo/inativo de um menu.
     */
    public function toggleStatus(Menu $menu)
    {
        $menu->update(['is_active' => !$menu->is_active]);

        AuditService::log('TOGGLE_MENU_STATUS', [
            'item_id' => $menu->id,
            'new_status' => $menu->is_active
        ]);

        return back()->with('success', 'Status do menu atualizado!');
    }
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string',
            'icon' => 'nullable|string',
            'is_admin_only' => 'boolean',
        ]);

        $menu->update($validated);

        AuditService::log('UPDATE_MENU_ITEM', $menu->toArray());

        return back()->with('success', 'Item de menu atualizado!');
    }
}
