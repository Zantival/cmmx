@extends('layouts.app')
@section('title', __('Dashboard'))

@push('styles')
<style nonce="{{ $cspNonce }}">
@media(max-width:575.98px){
    .dash-actions { width: 100%; }
    .dash-actions .btn-navy, .dash-actions .btn-ghost { flex: 1; justify-content: center; }
    .recent-table th:nth-child(3), .recent-table td:nth-child(3) { display: none; }
}
.progress-bar-inner { transition: width 1s ease; }
.upcoming-item { display: flex; align-items: center; gap: 10px; padding: 0.625rem 0; border-bottom: 1px solid var(--border); }
.upcoming-item:last-child { border-bottom: none; }
.upcoming-days { width: 44px; height: 44px; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1><i class="bi bi-grid-1x2-fill me-2" style="color:var(--accent);"></i>{{ __('Panel de Control') }}</h1>
        <div class="page-breadcrumb">
            <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
            &nbsp;·&nbsp; {{ __('Planta de Ensamblaje A1') }}
        </div>
    </div>
    @if(auth()->user()->role === 'Admin')
    <div class="page-actions dash-actions">
        <a href="{{ route('equipment.create') }}" class="btn-navy btn">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('Nuevo Activo') }}</span>
        </a>
        <a href="{{ route('maintenances.create') }}" class="btn-ghost btn">
            <i class="bi bi-clipboard-plus"></i>
            <span class="d-none d-sm-inline">{{ __('Nueva OT') }}</span>
        </a>
    </div>
    @endif
</div>

<div class="content-area">

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 animate-in">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 animate-in">
            <div class="kpi-card kpi-success">
                <div class="kpi-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="kpi-value">{{ $activeEquipment }}</div>
                <div class="kpi-label">{{ __('Equipos Operativos') }}</div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate-in delay-1">
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon"><i class="bi bi-tools"></i></div>
                <div class="kpi-value">{{ $repairEquipment }}</div>
                <div class="kpi-label">{{ __('En Reparación') }}</div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate-in delay-2">
            <div class="kpi-card kpi-danger">
                <div class="kpi-icon"><i class="bi bi-x-circle-fill"></i></div>
                <div class="kpi-value">{{ $outOfService }}</div>
                <div class="kpi-label">{{ __('Fuera de Servicio') }}</div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate-in delay-3">
            <div class="kpi-card kpi-info">
                <div class="kpi-icon"><i class="bi bi-calendar-check-fill"></i></div>
                <div class="kpi-value">{{ $pendingMaintenance }}</div>
                <div class="kpi-label">{{ __('OT Pendientes') }}</div>
                <div class="kpi-decoration"></div>
            </div>
        </div>
    </div>

    {{-- Secondary KPIs: MTTR + Critical OTs + Upcoming --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 animate-in">
            <div class="kpi-card" style="border-color:rgba(239,68,68,0.3);">
                <div class="kpi-icon" style="background:rgba(239,68,68,0.1);"><i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;"></i></div>
                <div class="kpi-value" style="color:#EF4444;">{{ $criticalOTs }}</div>
                <div class="kpi-label">{{ __('OTs Críticas Activas') }}</div>
                <div class="kpi-decoration" style="background:rgba(239,68,68,0.1);"></div>
            </div>
        </div>
        <div class="col-6 col-md-4 animate-in delay-1">
            <div class="kpi-card" style="border-color:rgba(99,102,241,0.3);">
                <div class="kpi-icon" style="background:rgba(99,102,241,0.1);"><i class="bi bi-stopwatch-fill" style="color:#6366F1;"></i></div>
                <div class="kpi-value" style="color:#6366F1;">{{ $mttr ? round($mttr, 1) . 'd' : '—' }}</div>
                <div class="kpi-label">MTTR {{ __('(días promedio)') }}</div>
                <div class="kpi-decoration" style="background:rgba(99,102,241,0.1);"></div>
            </div>
        </div>
        <div class="col-6 col-md-4 animate-in delay-2">
            <div class="kpi-card" style="border-color:rgba(16,185,129,0.3);">
                <div class="kpi-icon" style="background:rgba(16,185,129,0.1);"><i class="bi bi-people-fill" style="color:#10B981;"></i></div>
                <div class="kpi-value" style="color:#10B981;">{{ $totalTechnicians }}</div>
                <div class="kpi-label">{{ __('Técnicos Registrados') }}</div>
                <div class="kpi-decoration" style="background:rgba(16,185,129,0.1);"></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">

        {{-- Chart --}}
        <div class="col-12 col-md-7 animate-in delay-1">
            <div class="card no-hover h-100" style="border:none;">
                <div class="card-body p-0">
                    <div class="p-3 pb-2">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h6 class="fw-700 mb-1" style="font-size:0.9rem; font-weight:700; color:var(--navy);">
                                    {{ __('Intervenciones por Mes') }}
                                </h6>
                                <p class="mb-0" style="font-size:0.75rem; color:var(--text-muted);">{{ __('Últimos 6 meses') }}</p>
                            </div>
                            <div class="d-flex gap-3" style="font-size:0.72rem;">
                                <span class="d-flex align-items-center gap-1">
                                    <span style="width:10px;height:10px;border-radius:3px;background:var(--navy);display:inline-block;"></span>
                                    {{ __('Preventivas') }} <strong class="ms-1">{{ $totalPreventive }}</strong>
                                </span>
                                <span class="d-flex align-items-center gap-1">
                                    <span style="width:10px;height:10px;border-radius:3px;background:var(--danger);display:inline-block;"></span>
                                    {{ __('Correctivas') }} <strong class="ms-1">{{ $totalCorrective }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 pb-3">
                        <canvas id="interventionChart" height="190"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Equipment Status Summary --}}
        <div class="col-12 col-md-5 animate-in delay-2">
            <div class="card no-hover h-100" style="border:none;">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-1" style="font-size:0.9rem; color:var(--navy);">
                        {{ __('Estado del Parque Industrial') }}
                    </h6>
                    <p class="mb-3" style="font-size:0.75rem; color:var(--text-muted);">{{ __('Distribución por condición') }}</p>

                    @php
                        $total = $activeEquipment + $repairEquipment + $outOfService;
                        $pOp  = $total > 0 ? round(($activeEquipment / $total) * 100) : 0;
                        $pRep = $total > 0 ? round(($repairEquipment / $total) * 100) : 0;
                        $pOos = $total > 0 ? round(($outOfService / $total) * 100) : 0;
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.8rem;">
                            <span style="color:var(--text-primary);"><i class="bi bi-circle-fill me-1" style="color:#10B981; font-size:0.5rem;"></i>{{ __('Operativos') }}</span>
                            <span class="fw-600" style="color:var(--navy);">{{ $activeEquipment }} ({{ $pOp }}%)</span>
                        </div>
                        <div style="height:7px; background:#F1F5F9; border-radius:8px; overflow:hidden;">
                            <div class="progress-bar-inner" style="width:{{ $pOp }}%; height:100%; background:linear-gradient(90deg,#10B981,#059669); border-radius:8px;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.8rem;">
                            <span style="color:var(--text-primary);"><i class="bi bi-circle-fill me-1" style="color:#F59E0B; font-size:0.5rem;"></i>{{ __('En Reparación') }}</span>
                            <span class="fw-600" style="color:var(--navy);">{{ $repairEquipment }} ({{ $pRep }}%)</span>
                        </div>
                        <div style="height:7px; background:#F1F5F9; border-radius:8px; overflow:hidden;">
                            <div class="progress-bar-inner" style="width:{{ $pRep }}%; height:100%; background:linear-gradient(90deg,#F59E0B,#D97706); border-radius:8px;"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.8rem;">
                            <span style="color:var(--text-primary);"><i class="bi bi-circle-fill me-1" style="color:#EF4444; font-size:0.5rem;"></i>{{ __('Fuera de Servicio') }}</span>
                            <span class="fw-600" style="color:var(--navy);">{{ $outOfService }} ({{ $pOos }}%)</span>
                        </div>
                        <div style="height:7px; background:#F1F5F9; border-radius:8px; overflow:hidden;">
                            <div class="progress-bar-inner" style="width:{{ $pOos }}%; height:100%; background:linear-gradient(90deg,#EF4444,#DC2626); border-radius:8px;"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-2 border-top" style="border-color:var(--border)!important;">
                        <a href="{{ route('equipment.index') }}" class="btn-ghost btn btn-sm flex-fill text-center" style="font-size:0.78rem;">
                            <i class="bi bi-cpu me-1"></i>{{ __('Ver Activos') }}
                        </a>
                        @if(auth()->user()->role === 'Admin')
                        <a href="{{ route('equipment.create') }}" class="btn-navy btn btn-sm flex-fill text-center" style="font-size:0.78rem;">
                            <i class="bi bi-plus me-1"></i>{{ __('Añadir') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Maintenances (next 7 days) --}}
    @if($upcomingMaintenances->isNotEmpty())
    <div class="card no-hover animate-in delay-2 mb-4" style="border:none;">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between px-3 py-3" style="border-bottom:1px solid var(--border);">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-calendar-week-fill" style="color:#F59E0B;font-size:0.9rem;"></i>
                    </div>
                    <h6 class="mb-0 fw-bold" style="font-size:0.9rem;color:var(--navy);">{{ __('Próximos 7 Días') }}</h6>
                    <span style="background:#FEF3C7;color:#78350F;padding:2px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;">{{ $upcomingMaintenances->count() }}</span>
                </div>
                <a href="{{ route('maintenances.index') }}" class="btn-ghost btn btn-sm">
                    {{ __('Ver todas') }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="px-3">
                @foreach($upcomingMaintenances as $um)
                @php
                    $days = now()->startOfDay()->diffInDays($um->date->startOfDay(), false);
                    $pColor = $um->priority_color;
                    $isToday = $days === 0;
                    $isOverdue = $days < 0;
                @endphp
                <div class="upcoming-item">
                    <div class="upcoming-days" style="background:{{ $isOverdue ? '#FEE2E2' : ($isToday ? '#FEF3C7' : '#DBEAFE') }};">
                        <span style="font-size:1.1rem;font-weight:800;color:{{ $isOverdue ? '#EF4444' : ($isToday ? '#F59E0B' : '#3B82F6') }};line-height:1;">
                            {{ $isOverdue ? '!' : abs($days) }}
                        </span>
                        <span style="font-size:0.55rem;color:{{ $isOverdue ? '#EF4444' : ($isToday ? '#F59E0B' : '#3B82F6') }};font-weight:600;text-transform:uppercase;">
                            {{ $isOverdue ? __('Venc.') : ($isToday ? __('Hoy') : __('días')) }}
                        </span>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:700;font-size:0.875rem;color:var(--navy);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $um->equipment->name ?? '—' }}
                        </div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">
                            {{ $um->date->format('d/m/Y') }}
                            @if($um->technician) &nbsp;·&nbsp; {{ $um->technician->name }} @endif
                            @if($um->estimated_hours) &nbsp;·&nbsp; <i class="bi bi-clock"></i> {{ $um->estimated_hours }}h @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <span style="padding:2px 9px;border-radius:20px;font-size:0.68rem;font-weight:700;background:{{ $um->priority_bg }};color:{{ $pColor }};">
                            {{ $um->priority_label }}
                        </span>
                        <a href="{{ route('maintenances.show', $um) }}" class="btn-ghost btn btn-sm py-1 px-2">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Maintenances --}}
    <div class="card no-hover animate-in delay-2" style="border:none;">
        <div class="card-body p-0">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom" style="border-color:var(--border)!important;">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size:0.9rem; color:var(--navy);">{{ __('Últimas Intervenciones') }}</h6>
                    <p class="mb-0 mt-1" style="font-size:0.73rem; color:var(--text-muted);">{{ __('Historial reciente') }}</p>
                </div>
                <a href="{{ route('maintenances.index') }}" class="btn-ghost btn btn-sm d-flex align-items-center gap-1">
                    {{ __('Ver todas') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0 recent-table">
                    <thead>
                        <tr>
                            <th>{{ __('Equipo') }}</th>
                            <th>{{ __('Tipo') }}</th>
                            <th>{{ __('Técnico') }}</th>
                            <th class="d-none d-sm-table-cell">{{ __('Fecha') }}</th>
                            <th class="text-end">{{ __('Acción') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMaintenances as $m)
                        <tr>
                            <td>
                                <div class="fw-600" style="font-weight:600; color:var(--navy); font-size:0.85rem;">{{ $m->equipment->name ?? 'N/A' }}</div>
                                <div class="d-none d-sm-block" style="font-size:0.72rem; color:var(--text-muted); font-family:monospace;">{{ $m->equipment->code ?? '' }}</div>
                            </td>
                            <td>
                                @if($m->type === 'Preventive')
                                    <span class="badge-status badge-prev"><i class="bi bi-calendar-check"></i><span class="d-none d-sm-inline">{{ __('Preventivo') }}</span><span class="d-sm-none">P</span></span>
                                @else
                                    <span class="badge-status badge-corr"><i class="bi bi-exclamation-triangle"></i><span class="d-none d-sm-inline">{{ __('Correctivo') }}</span><span class="d-sm-none">C</span></span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:26px;height:26px;border-radius:50%;background:var(--surface);border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:var(--navy);flex-shrink:0;">
                                        {{ strtoupper(substr($m->technician->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <span style="font-size:0.82rem;" class="d-none d-md-inline">{{ $m->technician->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="d-none d-sm-table-cell" style="font-size:0.8rem; color:var(--text-muted);">
                                {{ \Carbon\Carbon::parse($m->date)->format('d M Y') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('maintenances.pdf', $m) }}"
                                   class="btn btn-sm btn-ghost d-inline-flex align-items-center gap-1"
                                   title="{{ __('Descargar PDF') }}">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                    <span class="d-none d-md-inline" style="font-size:0.75rem;">PDF</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-clipboard-x" style="font-size:2.5rem; color:var(--text-light); display:block; margin-bottom:0.75rem;"></i>
                                <p style="color:var(--text-muted); font-size:0.875rem;">{{ __('Sin intervenciones registradas') }}</p>
                                @if(auth()->user()->role === 'Admin')
                                <a href="{{ route('maintenances.create') }}" class="btn-navy btn btn-sm">
                                    <i class="bi bi-plus me-1"></i>{{ __('Registrar OT') }}
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Technician List (Admin only) --}}
    @if(auth()->user()->role === 'Admin')
    <div class="card no-hover mt-4 animate-in" style="border:none;">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between px-3 py-3" style="border-bottom:1px solid var(--border);">
                <h6 class="mb-0 fw-bold" style="font-size:0.9rem; color:var(--navy);">{{ __('Técnicos Registrados') }}</h6>
                <span class="badge" style="background:var(--accent-glow);color:var(--accent);font-size:0.72rem;">{{ $totalTechnicians }} {{ __('técnicos') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr>
                        <th>{{ __('Nombre') }}</th>
                        <th class="d-none d-sm-table-cell">{{ __('Email') }}</th>
                        <th>{{ __('OTs Asignadas') }}</th>
                    </tr></thead>
                    <tbody>
                        @forelse($technicianList as $tech)
                        <tr>
                            <td class="fw-semibold">{{ $tech->name }}</td>
                            <td class="d-none d-sm-table-cell" style="color:var(--text-muted);">{{ $tech->email }}</td>
                            <td><span class="badge-status badge-prev">{{ $tech->maintenances_count }} OTs</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">{{ __('Sin técnicos registrados.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Stock Alert Widget --}}
    <div class="card no-hover mt-4 animate-in" style="border:none;">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between px-3 py-3" style="border-bottom:1px solid var(--border);">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:8px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:#F59E0B;font-size:0.9rem;"></i>
                    </div>
                    <h6 class="mb-0 fw-bold" style="font-size:0.9rem; color:var(--navy);">{{ __('Alertas de Stock') }}</h6>
                </div>
                <a href="{{ route('inventory.index') }}" class="btn-ghost btn btn-sm d-flex align-items-center gap-1" style="font-size:0.78rem;">
                    {{ __('Ver inventario') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if($lowStockItems->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-check-circle-fill" style="font-size:2rem;color:#10B981;display:block;margin-bottom:0.5rem;"></i>
                <p class="mb-0" style="color:var(--text-muted);font-size:0.85rem;">{{ __('Sin alertas de stock') }}</p>
                <p class="mb-0" style="color:var(--text-light);font-size:0.75rem;">{{ __('Todo el inventario está bien') }}</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        @foreach($lowStockItems as $stockItem)
                        @php
                            $isOut = $stockItem->stock <= 0;
                            $badgeClass = $isOut ? 'badge-oos' : 'badge-rep';
                            $badgeIcon  = $isOut ? 'bi-x-circle-fill' : 'bi-exclamation-triangle-fill';
                            $stockLabel = $isOut ? __('Out of Stock') : __('Low Stock');
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:600;color:var(--navy);font-size:0.85rem;">{{ $stockItem->name }}</div>
                                <div style="font-size:0.7rem;color:var(--text-muted);font-family:monospace;">{{ $stockItem->sku ?: '—' }}</div>
                            </td>
                            <td class="d-none d-sm-table-cell" style="color:var(--text-muted);font-size:0.8rem;">
                                {{ __('Stock mínimo:') }} <strong>{{ $stockItem->min_stock }}</strong>
                            </td>
                            <td class="text-end">
                                <span class="badge-status {{ $badgeClass }}">
                                    <i class="bi {{ $badgeIcon }}"></i>
                                    {{ $stockItem->stock }} {{ __('unid.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="/vendor/chartjs/chart.umd.min.js"></script>
<script nonce="{{ $cspNonce }}">
const ctx = document.getElementById('interventionChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [
            {
                label: '{{ __("Preventivas") }}',
                data: {!! json_encode($chartPreventive) !!},
                backgroundColor: '#0A192F',
                borderRadius: 5,
                borderSkipped: false,
                barPercentage: 0.65,
            },
            {
                label: '{{ __("Correctivas") }}',
                data: {!! json_encode($chartCorrective) !!},
                backgroundColor: '#EF4444',
                borderRadius: 5,
                borderSkipped: false,
                barPercentage: 0.65,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#0F172A',
                bodyColor: '#64748B',
                borderColor: '#E2E8F0',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 10, family: 'Inter' }, color: '#94A3B8' }
            },
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { size: 10, family: 'Inter' }, color: '#94A3B8' },
                grid: { color: '#F1F5F9', lineWidth: 1 },
                border: { dash: [3,3] }
            }
        }
    }
});
</script>
@endpush
