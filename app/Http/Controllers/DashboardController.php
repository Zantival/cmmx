<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

/**
 * DashboardController
 *
 * Solo orquesta: llama al DashboardService y pasa los datos a la vista.
 * Toda la lógica de métricas vive en App\Services\DashboardService.
 */
class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard)
    {
    }

    // ─── Panel Administrador ──────────────────────────────────────────────────

    public function index()
    {
        $equipment   = $this->dashboard->getEquipmentStats();
        $maintenance = $this->dashboard->getMaintenanceStats();
        $chart       = $this->dashboard->getChartData();

        return view('dashboard', [
            // KPIs de equipos
            'activeEquipment'  => $equipment['active'],
            'repairEquipment'  => $equipment['in_repair'],
            'outOfService'     => $equipment['out_service'],
            'totalEquipment'   => $equipment['total'],

            // KPIs de OTs
            'pendingMaintenance' => $maintenance['pending'],
            'criticalOTs'        => $maintenance['critical'],
            'totalOTs'           => $maintenance['total'],
            'completedOTs'       => $maintenance['completed'],
            'totalPreventive'    => array_sum($chart['preventive']),
            'totalCorrective'    => array_sum($chart['corrective']),

            // Métricas de rendimiento
            'mttr'             => $this->dashboard->getMttr(),
            'totalTechnicians' => \App\Models\User::where('role', 'Technician')->count(),
            'technicianList'   => $this->dashboard->getTechnicianList(),

            // Feeds de actividad y alertas
            'recentMaintenances'  => $this->dashboard->getRecentMaintenances(),
            'upcomingMaintenances'=> $this->dashboard->getUpcomingMaintenances(),
            'lowStockItems'       => $this->dashboard->getLowStockItems(),
            'upcomingMaintenanceEquipments' => $this->dashboard->getUpcomingMaintenanceEquipments(),

            // Datos de gráfico
            'chartLabels'      => $chart['labels'],
            'chartPreventive'  => $chart['preventive'],
            'chartCorrective'  => $chart['corrective'],
        ]);
    }

    // ─── Panel Técnico ────────────────────────────────────────────────────────

    public function technicianDashboard()
    {
        $user = auth()->user();
        $data = $this->dashboard->getTechnicianDashboard($user);

        // Agrega datos de tipos para la vista (preventive/corrective counts)
        $data['preventiveCount'] = $data['allMyOTs']->where('type', 'Preventive')->count();
        $data['correctiveCount'] = $data['allMyOTs']->where('type', 'Corrective')->count();

        return view('technician.dashboard', $data);
    }
}
