<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Equipment;
use App\Models\User;
use App\Models\InventoryItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * MaintenanceController — Órdenes de Trabajo (OT)
 *
 * Reglas de acceso:
 *   - Admin: acceso total (CRUD + eliminar)
 *   - Técnico: solo puede ver/modificar sus propias OTs asignadas
 *
 * La restricción de creación/edición/eliminación se delega al router.
 * El acceso a show, updateStatus, updateNotes, addPart y exportPdf
 * se valida con el helper privado authorizeOTAccess().
 */
class MaintenanceController extends Controller
{
    // ─── Helper de autorización ───────────────────────────────────────────────

    /**
     * Lanza 403 si el técnico intenta acceder a una OT que no le pertenece.
     * Admin tiene acceso irrestricto.
     */
    private function authorizeOTAccess(Maintenance $maintenance): void
    {
        $user = auth()->user();
        if ($user->role === 'Technician' && $maintenance->technician_id !== $user->id) {
            abort(403, __('No tienes permiso para acceder a esta Orden de Trabajo.'));
        }
    }

    // ─── Listado ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Maintenance::with(['equipment', 'technician']);

        // Búsqueda de texto
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'LIKE', "%{$s}%")
                  ->orWhere('id', 'LIKE', "%{$s}%")
                  ->orWhereHas('equipment', fn ($qe) => $qe->where('name', 'LIKE', "%{$s}%")->orWhere('code', 'LIKE', "%{$s}%"));
            });
        }

        // Filtros
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Técnico solo ve sus OTs
        if ($user->role !== 'Admin') {
            $query->where('technician_id', $user->id);
        }

        // Orden: críticas primero, luego por estado y fecha
        $query->orderByRaw("FIELD(priority,'Critical','High','Normal','Low')")
              ->orderByRaw("FIELD(status,'Pending','In Progress','Completed')")
              ->orderBy('date');

        $maintenances = $query->paginate(12)->withQueryString();

        // Contadores de KPI (reutiliza el query base ya filtrado)
        $base         = $user->role === 'Admin' ? Maintenance::query() : Maintenance::where('technician_id', $user->id);
        $totalCount   = (clone $base)->count();
        $pendingCount = (clone $base)->where('status', 'Pending')->count();
        $progressCount= (clone $base)->where('status', 'In Progress')->count();
        $criticalCount= $user->role === 'Admin'
            ? Maintenance::where('priority', 'Critical')->whereIn('status', ['Pending', 'In Progress'])->count()
            : 0;

        return view('maintenances.index', compact(
            'maintenances', 'totalCount', 'pendingCount', 'progressCount', 'criticalCount'
        ));
    }

    // ─── Crear / Guardar ──────────────────────────────────────────────────────

    public function create(Request $request)
    {
        $equipments        = Equipment::orderBy('name')->get();
        $technicians       = User::where('role', 'Technician')->orderBy('name')->get();
        $selectedEquipment = $request->equipment ? Equipment::find($request->equipment) : null;
        return view('maintenances.create', compact('equipments', 'technicians', 'selectedEquipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id'    => 'required|exists:equipment,id',
            'technician_id'   => 'required|exists:users,id',
            'type'            => 'required|in:Preventive,Corrective',
            'priority'        => 'required|in:Critical,High,Normal,Low',
            'status'          => 'required|in:Pending,In Progress,Completed',
            'date'            => 'required|date',
            'estimated_hours' => 'nullable|numeric|min:0.5|max:999',
            'description'     => 'nullable|string|max:2000',
        ]);

        if ($validated['status'] === 'Completed') {
            $validated['completion_date'] = Carbon::now();
        }

        Maintenance::create($validated);
        return redirect()->route('maintenances.index')->with('success', __('Orden de trabajo creada correctamente.'));
    }

    // ─── Detalle ──────────────────────────────────────────────────────────────

    public function show(Maintenance $maintenance)
    {
        $this->authorizeOTAccess($maintenance);
        $maintenance->load(['equipment', 'technician', 'partsUsed']);
        $inventoryItems = InventoryItem::where('stock', '>', 0)->orderBy('name')->get();
        return view('maintenances.show', compact('maintenance', 'inventoryItems'));
    }

    // ─── Editar / Actualizar ──────────────────────────────────────────────────

    public function edit(Maintenance $maintenance)
    {
        $equipments  = Equipment::orderBy('name')->get();
        $technicians = User::where('role', 'Technician')->orderBy('name')->get();
        return view('maintenances.edit', compact('maintenance', 'equipments', 'technicians'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'equipment_id'    => 'required|exists:equipment,id',
            'technician_id'   => 'required|exists:users,id',
            'type'            => 'required|in:Preventive,Corrective',
            'priority'        => 'required|in:Critical,High,Normal,Low',
            'status'          => 'required|in:Pending,In Progress,Completed',
            'date'            => 'required|date',
            'estimated_hours' => 'nullable|numeric|min:0.5|max:999',
            'actual_hours'    => 'nullable|numeric|min:0.5|max:999',
            'description'     => 'nullable|string|max:2000',
        ]);

        if ($validated['status'] === 'Completed' && !$maintenance->completion_date) {
            $validated['completion_date'] = Carbon::now();
        }

        $maintenance->update($validated);
        return redirect()->route('maintenances.show', $maintenance)->with('success', __('OT actualizada correctamente.'));
    }

    // ─── Eliminar (solo Admin — protegido por router) ─────────────────────────

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', __('Orden de trabajo eliminada.'));
    }

    // ─── Acciones de técnico ──────────────────────────────────────────────────

    public function updateStatus(Request $request, Maintenance $maintenance)
    {
        $this->authorizeOTAccess($maintenance);
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'next_maintenance_date' => 'nullable|date|after:today',
        ]);

        $data = ['status' => $validated['status']];
        if ($validated['status'] === 'Completed') {
            if (!$maintenance->completion_date) {
                $data['completion_date'] = Carbon::now();
            }

            $equipment = $maintenance->equipment;
            if ($equipment) {
                $eqData = ['status' => 'Operational'];

                $nextDate = $request->filled('next_maintenance_date')
                    ? Carbon::parse($validated['next_maintenance_date'])
                    : Carbon::now()->addMonths(3);

                $eqData['next_maintenance_date'] = $nextDate;
                $equipment->update($eqData);
            }
        }

        $maintenance->update($data);
        return back()->with('success', __('Estado actualizado correctamente y equipo configurado como Operativo.'));
    }

    public function updateNotes(Request $request, Maintenance $maintenance)
    {
        $this->authorizeOTAccess($maintenance);
        $request->validate(['tech_notes' => 'nullable|string|max:2000']);
        $maintenance->update(['tech_notes' => $request->tech_notes]);
        return back()->with('success', __('Notas técnicas guardadas correctamente.'));
    }

    public function addPart(Request $request, Maintenance $maintenance)
    {
        $this->authorizeOTAccess($maintenance);

        if ($maintenance->status === 'Completed') {
            return back()->with('error', __('No se pueden agregar repuestos a una OT completada.'));
        }

        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity_used'     => 'required|integer|min:1',
        ]);

        $item = InventoryItem::findOrFail($validated['inventory_item_id']);

        if ($item->stock < $validated['quantity_used']) {
            return back()->with('error', __('Stock insuficiente para el repuesto seleccionado.'));
        }

        // Descuenta stock
        $item->decrement('stock', $validated['quantity_used']);

        // Acumula si ya existe el repuesto en la OT
        $existing = $maintenance->partsUsed()->where('inventory_item_id', $item->id)->first();
        if ($existing) {
            $maintenance->partsUsed()->updateExistingPivot($item->id, [
                'quantity_used' => $existing->pivot->quantity_used + $validated['quantity_used'],
            ]);
        } else {
            $maintenance->partsUsed()->attach($item->id, ['quantity_used' => $validated['quantity_used']]);
        }

        return back()->with('success', __('Repuesto agregado y descontado del inventario exitosamente.'));
    }

    // ─── PDF ──────────────────────────────────────────────────────────────────

    public function exportPdf(Maintenance $maintenance)
    {
        $this->authorizeOTAccess($maintenance);
        $maintenance->load(['equipment', 'technician']);
        $pdf = Pdf::loadView('maintenances.pdf', compact('maintenance'))->setPaper('a4', 'portrait');
        return $pdf->download('OT-' . str_pad($maintenance->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
