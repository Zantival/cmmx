<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\InventoryItem;
use Carbon\Carbon;

/**
 * DashboardService
 *
 * Centraliza todas las consultas de métricas del sistema.
 * El DashboardController solo llama a estos métodos — no tiene lógica de datos.
 *
 * Separar lógica de negocio del controller permite:
 *   - Testear las métricas sin HTTP
 *   - Reutilizar las mismas métricas desde otras partes (ej: API)
 *   - Leer el controller de un vistazo
 */
class DashboardService
{
    // ─── Panel Administrador ──────────────────────────────────────────────────

    /**
     * KPIs de equipos: activos, en reparación, fuera de servicio.
     */
    public function getEquipmentStats(): array
    {
        return [
            'active'       => Equipment::where('status', 'Operational')->count(),
            'in_repair'    => Equipment::where('status', 'In Repair')->count(),
            'out_service'  => Equipment::where('status', 'Out of Service')->count(),
            'total'        => Equipment::count(),
        ];
    }

    /**
     * KPIs de Órdenes de Trabajo.
     */
    public function getMaintenanceStats(): array
    {
        return [
            'pending'     => Maintenance::where('status', 'Pending')->count(),
            'in_progress' => Maintenance::where('status', 'In Progress')->count(),
            'completed'   => Maintenance::where('status', 'Completed')->count(),
            'total'       => Maintenance::count(),
            'critical'    => Maintenance::where('priority', 'Critical')
                                ->whereIn('status', ['Pending', 'In Progress'])->count(),
        ];
    }

    /**
     * MTTR — Mean Time To Repair en días.
     */
    public function getMttr(): ?float
    {
        return Maintenance::where('status', 'Completed')
            ->whereNotNull('completion_date')
            ->get()
            ->avg('resolution_days');
    }

    /**
     * Últimas 5 OTs para el feed de actividad reciente.
     */
    public function getRecentMaintenances(int $limit = 5)
    {
        return Maintenance::with(['equipment', 'technician'])->latest()->take($limit)->get();
    }

    /**
     * OTs en los próximos 7 días (pendientes o en progreso).
     */
    public function getUpcomingMaintenances(int $days = 7, int $limit = 6)
    {
        return Maintenance::with(['equipment', 'technician'])
            ->whereIn('status', ['Pending', 'In Progress'])
            ->whereNotNull('date')
            ->whereBetween('date', [now()->startOfDay(), now()->addDays($days)->endOfDay()])
            ->orderBy('date')
            ->take($limit)
            ->get();
    }

    /**
     * Repuestos con stock bajo (stock <= min_stock).
     */
    public function getLowStockItems(int $limit = 5)
    {
        return InventoryItem::whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->take($limit)->get();
    }

    /**
     * Datos para el gráfico de barras: preventivas vs correctivas (últimos 6 meses).
     */
    public function getChartData(): array
    {
        $labels      = [];
        $preventive  = [];
        $corrective  = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[]    = $month->format('M');
            $preventive[]= Maintenance::where('type', 'Preventive')
                ->whereYear('date', $month->year)->whereMonth('date', $month->month)->count();
            $corrective[]= Maintenance::where('type', 'Corrective')
                ->whereYear('date', $month->year)->whereMonth('date', $month->month)->count();
        }

        return compact('labels', 'preventive', 'corrective');
    }

    /**
     * Lista de técnicos con su conteo de OTs asignadas.
     */
    public function getTechnicianList()
    {
        return User::where('role', 'Technician')->withCount('maintenances')->get();
    }

    // ─── Panel Técnico ────────────────────────────────────────────────────────

    /**
     * Estadísticas completas del técnico autenticado.
     * Recibe el User para no hacer auth() dentro del servicio.
     */
    public function getTechnicianDashboard(\App\Models\User $user): array
    {
        $allMyOTs = Maintenance::where('technician_id', $user->id)->with('equipment')->latest()->get();

        $myPending     = $allMyOTs->where('status', 'Pending')->count();
        $myInProgress  = $allMyOTs->where('status', 'In Progress')->count();
        $myCompleted   = $allMyOTs->where('status', 'Completed')->count();
        $myTotal       = $allMyOTs->count();
        $completionRate= $myTotal > 0 ? round(($myCompleted / $myTotal) * 100) : 0;

        $urgentOTs = Maintenance::where('technician_id', $user->id)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->whereDate('date', '<=', Carbon::today()->addDays(2))
            ->with('equipment')->latest()->get();

        $inProgressOTs = Maintenance::where('technician_id', $user->id)
            ->where('status', 'In Progress')
            ->with('equipment')->latest()->get();

        $upcomingOTs = Maintenance::where('technician_id', $user->id)
            ->where('status', 'Pending')
            ->whereDate('date', '>=', Carbon::today())
            ->with('equipment')->orderBy('date')->take(5)->get();

        $recentCompleted = Maintenance::where('technician_id', $user->id)
            ->where('status', 'Completed')
            ->with('equipment')->latest()->take(5)->get();

        $myMaintenances = Maintenance::where('technician_id', $user->id)
            ->with('equipment')->latest()->paginate(8);

        // Gráfico mensual del técnico
        $chartLabels = $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M');
            $chartData[]   = Maintenance::where('technician_id', $user->id)
                ->whereYear('date', $month->year)->whereMonth('date', $month->month)->count();
        }

        $myEquipmentIds = $allMyOTs->pluck('equipment_id')->unique();
        $myEquipment    = Equipment::whereIn('id', $myEquipmentIds)->get();

        return compact(
            'allMyOTs', 'myPending', 'myInProgress', 'myCompleted', 'myTotal',
            'urgentOTs', 'inProgressOTs', 'upcomingOTs', 'recentCompleted',
            'myMaintenances', 'chartLabels', 'chartData',
            'myEquipment', 'completionRate',
        );
    }
}
