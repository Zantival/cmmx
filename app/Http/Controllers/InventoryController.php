<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

/**
 * InventoryController
 *
 * La autorización por rol se maneja en routes/web.php mediante middleware:
 *   - CRUD (create/store/edit/update/destroy): solo Admin  → has.role:Admin
 *   - consume: Admin y Técnico                             → has.role:Admin,Technician
 *   - index / show: cualquier usuario autenticado          → auth
 *
 * Este controller es responsable únicamente de la lógica de datos.
 */
class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $items = $query->latest()->paginate(12)->withQueryString();
        return view('inventory.index', compact('items'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function show(InventoryItem $inventory)
    {
        return view('inventory.show', ['item' => $inventory]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'nullable|string|max:50|unique:inventory_items',
            'description' => 'nullable|string',
            'stock'       => 'required|integer|min:0',
            'unit_price'  => 'required|numeric|min:0',
            'category'    => 'required|string',
            'min_stock'   => 'required|integer|min:0',
        ]);

        InventoryItem::create($validated);
        return redirect()->route('inventory.index')->with('success', __('Repuesto agregado al inventario correctamente.'));
    }

    public function edit(InventoryItem $inventory)
    {
        $item = $inventory;
        return view('inventory.edit', compact('item'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'nullable|string|max:50|unique:inventory_items,sku,' . $inventory->id,
            'description' => 'nullable|string',
            'stock'       => 'required|integer|min:0',
            'unit_price'  => 'required|numeric|min:0',
            'category'    => 'required|string',
            'min_stock'   => 'required|integer|min:0',
        ]);

        $inventory->update($validated);
        return redirect()->route('inventory.index')->with('success', __('Inventario actualizado correctamente.'));
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', __('Repuesto eliminado del inventario.'));
    }

    public function consume(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($inventory->stock < $validated['quantity']) {
            return back()->with('error', __('Stock insuficiente para registrar el consumo.'));
        }

        $inventory->decrement('stock', $validated['quantity']);
        return redirect()->route('inventory.index')->with('success', __('Consumo registrado correctamente.'));
    }
}
