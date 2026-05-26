<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;

/**
 * EquipmentController — Gestión de activos industriales
 *
 * Acceso por router:
 *   - index / show : cualquier usuario autenticado (auth)
 *   - create / store / edit / update / destroy : solo Admin (has.role:Admin)
 */
class EquipmentController extends Controller
{
    // ─── Listado con filtros ──────────────────────────────────────────────────

    public function index(\Illuminate\Http\Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('code', 'like', "%$s%")
                  ->orWhere('brand', 'like', "%$s%")
                  ->orWhere('location', 'like', "%$s%");
            });
        }
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->filled('criticality') && $request->criticality !== 'all') {
            $query->where('criticality', $request->criticality);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $equipments = $query
            ->orderByRaw("FIELD(criticality,'Critical','High','Medium','Low')")
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories    = Equipment::distinct()->whereNotNull('category')->pluck('category')->sort()->values();
        $totalCount    = Equipment::count();
        $criticalCount = Equipment::where('criticality', 'Critical')->count();
        $upcomingCount = Equipment::whereNotNull('next_maintenance_date')
                                  ->where('next_maintenance_date', '<=', now()->addDays(7))
                                  ->count();

        return view('equipment.index', compact(
            'equipments', 'categories', 'totalCount', 'criticalCount', 'upcomingCount'
        ));
    }

    // ─── Detalle ──────────────────────────────────────────────────────────────

    public function show(Equipment $equipment)
    {
        $equipment->load(['maintenances.technician']);
        $history = $equipment->maintenances()->with('technician')->take(10)->get();
        $mttr    = $equipment->maintenances()
                             ->where('status', 'Completed')
                             ->whereNotNull('completion_date')
                             ->get()
                             ->avg('resolution_days');

        return view('equipment.show', compact('equipment', 'history', 'mttr'));
    }

    // ─── Crear ────────────────────────────────────────────────────────────────

    public function create()
    {
        return view('equipment.create');
    }

    /**
     * Usa StoreEquipmentRequest para validación y mensajes centralizados.
     */
    public function store(StoreEquipmentRequest $request)
    {
        Equipment::create($request->validated());
        return redirect()->route('equipment.index')
            ->with('success', __('Activo registrado correctamente.'));
    }

    // ─── Editar ───────────────────────────────────────────────────────────────

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    /**
     * Usa UpdateEquipmentRequest para validación centralizada.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());
        return redirect()->route('equipment.show', $equipment)
            ->with('success', __('Activo actualizado correctamente.'));
    }

    // ─── Eliminar ─────────────────────────────────────────────────────────────

    public function destroy(Equipment $equipment)
    {
        if ($equipment->maintenances()->count() > 0) {
            return redirect()->route('equipment.index')
                ->with('error', __('No se puede eliminar un activo con historial de mantenimiento.'));
        }
        $equipment->delete();
        return redirect()->route('equipment.index')
            ->with('success', __('Activo eliminado correctamente.'));
    }
}
