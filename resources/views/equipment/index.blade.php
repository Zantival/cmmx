@extends('layouts.app')
@section('title', __('Activos'))

@push('styles')
<style nonce="{{ $cspNonce }}">
.eq-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.125rem; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); position: relative; display: flex; flex-direction: column; gap: 0.625rem; }
.eq-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: rgba(56,189,248,0.3); }
.eq-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
.eq-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.eq-code { font-family: monospace; font-size: 0.68rem; color: var(--text-muted); font-weight: 600; margin-bottom: 2px; }
.eq-name { font-size: 0.92rem; font-weight: 700; color: var(--navy); line-height: 1.3; }
.eq-location { font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 4px; }
.eq-badges { display: flex; flex-wrap: wrap; gap: 4px; }
.eq-badge { padding: 2px 9px; border-radius: 20px; font-size: 0.67rem; font-weight: 700; }
.eq-actions { display: flex; gap: 6px; margin-top: auto; padding-top: 0.625rem; border-top: 1px dashed var(--border); }
.eq-actions a { flex: 1; text-align: center; padding: 6px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.eq-actions a:hover { filter: brightness(0.92); }
.eq-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 1rem; }
@media(max-width:575.98px) { .eq-grid { grid-template-columns: repeat(1,1fr); } }

.filter-bar { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
.filter-chip { display: inline-flex; align-items: center; gap: 5px; padding: 5px 13px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text-muted); text-decoration: none; transition: all 0.2s; white-space: nowrap; }
.filter-chip:hover { border-color: var(--accent); color: var(--accent); text-decoration: none; }
.filter-chip.active { background: var(--navy); color: #fff; border-color: var(--navy); }

.next-maint-alert { display: inline-flex; align-items: center; gap: 4px; font-size: 0.68rem; font-weight: 600; padding: 2px 8px; border-radius: 20px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1><i class="bi bi-cpu-fill me-2" style="color:var(--accent);"></i>{{ __('Activos de Planta') }}</h1>
        <div class="page-breadcrumb">
            {{ __('Total registrados:') }} <strong>{{ $totalCount }}</strong>
            &nbsp;·&nbsp; <span style="color:#EF4444;">{{ $criticalCount }}</span> {{ __('críticos') }}
            @if($upcomingCount > 0)
            &nbsp;·&nbsp; <span style="color:#F59E0B;">{{ $upcomingCount }}</span> {{ __('con mant. próximo') }}
            @endif
        </div>
    </div>
    <div class="page-actions">
        <form id="searchForm" action="{{ route('equipment.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-color:var(--border);">
                    <i class="bi bi-search text-muted" id="searchIcon"></i>
                </span>
                <input type="text" name="search" id="searchInput" class="form-control border-start-0"
                       style="border-color:var(--border);min-width:140px;"
                       placeholder="{{ __('Buscar...') }}" value="{{ request('search') }}">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('criticality')) <input type="hidden" name="criticality" value="{{ request('criticality') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            </div>
        </form>
        @if(auth()->user()->role === 'Admin')
        <a href="{{ route('equipment.create') }}" class="btn-navy btn">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('Nuevo Activo') }}</span>
        </a>
        @endif
    </div>
</div>

<div class="content-area" id="resultsArea">

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 animate-in">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 animate-in">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Filter Bar --}}
    <div class="d-flex flex-column gap-2 mb-4 animate-in">
        {{-- Status filters --}}
        <div class="filter-bar">
            <span style="font-size:0.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ __('Estado:') }}</span>
            @foreach([
                ['all', __('Todos'), 'bi-grid'],
                ['Operational', __('Operativo'), 'bi-check-circle-fill'],
                ['In Repair', __('En Reparación'), 'bi-tools'],
                ['Out of Service', __('Fuera de Servicio'), 'bi-x-circle-fill'],
            ] as [$val, $lbl, $icon])
            @php $active = request('status', 'all') === $val; @endphp
            <a href="{{ route('equipment.index', array_merge(request()->except('status','page'), $val !== 'all' ? ['status' => $val] : [])) }}"
               class="filter-chip {{ $active ? 'active' : '' }}">
                <i class="bi {{ $icon }}"></i> {{ $lbl }}
            </a>
            @endforeach
        </div>
        {{-- Criticality filters --}}
        <div class="filter-bar">
            <span style="font-size:0.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ __('Criticidad:') }}</span>
            @foreach([
                ['all', __('Todas'), '#64748B'],
                ['Critical', __('Crítica'), '#EF4444'],
                ['High', __('Alta'), '#F59E0B'],
                ['Medium', __('Media'), '#3B82F6'],
                ['Low', __('Baja'), '#10B981'],
            ] as [$val, $lbl, $color])
            @php $active = request('criticality', 'all') === $val; @endphp
            <a href="{{ route('equipment.index', array_merge(request()->except('criticality','page'), $val !== 'all' ? ['criticality' => $val] : [])) }}"
               class="filter-chip {{ $active ? 'active' : '' }}" style="{{ !$active ? 'color:'.$color.';border-color:'.$color.'40;' : '' }}">
                {{ $lbl }}
            </a>
            @endforeach
        </div>
        {{-- Category filters --}}
        @if($categories->isNotEmpty())
        <div class="filter-bar">
            <span style="font-size:0.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ __('Categoría:') }}</span>
            <a href="{{ route('equipment.index', request()->except('category','page')) }}"
               class="filter-chip {{ !request('category') ? 'active' : '' }}">{{ __('Todas') }}</a>
            @foreach($categories as $cat)
            <a href="{{ route('equipment.index', array_merge(request()->except('category','page'), ['category' => $cat])) }}"
               class="filter-chip {{ request('category') === $cat ? 'active' : '' }}">{{ __($cat) }}</a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Equipment Grid --}}
    <div class="eq-grid animate-in delay-1" id="equipGrid">
        @forelse($equipments as $eq)
        @php
            $iconMap = [
                'Eléctrico' => ['bi-plug-fill','#FEF3C7','#F59E0B'],
                'Maquinaria Industrial' => ['bi-gear-wide-connected','#DBEAFE','#3B82F6'],
                'HVAC' => ['bi-wind','#ECFDF5','#10B981'],
                'Hidráulico' => ['bi-droplet-fill','#EDE9FE','#8B5CF6'],
                'Neumático' => ['bi-tornado','#F0F9FF','#0EA5E9'],
                'Combustión' => ['bi-fire','#FFF7ED','#EA580C'],
                'Vehículos' => ['bi-truck','#F1F5F9','#64748B'],
                'Instrumentación' => ['bi-speedometer2','#FDF2F8','#EC4899'],
            ];
            $iconData = $iconMap[$eq->category] ?? ['bi-cpu-fill','#F1F5F9','#64748B'];
            $isOverdue = $eq->next_maintenance_date && $eq->days_to_next_maintenance < 0;
            $isUrgent  = $eq->next_maintenance_date && $eq->days_to_next_maintenance >= 0 && $eq->days_to_next_maintenance <= 7;
        @endphp
        <div class="eq-card">
            {{-- Admin actions --}}
            @if(auth()->user()->role === 'Admin')
            <div style="position:absolute;top:10px;right:10px;display:flex;gap:4px;z-index:2;">
                <a href="{{ route('equipment.edit', $eq) }}" class="btn btn-sm btn-light shadow-sm"
                   style="width:26px;height:26px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                    <i class="bi bi-pencil" style="font-size:0.65rem;color:var(--info);"></i>
                </a>
                @if($eq->maintenances()->count() === 0)
                <form action="{{ route('equipment.destroy', $eq) }}" method="POST" class="m-0" onsubmit="return confirm('{{ __('¿Seguro que desea eliminar?') }}')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-light shadow-sm" style="width:26px;height:26px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                        <i class="bi bi-trash" style="font-size:0.65rem;color:var(--danger);"></i>
                    </button>
                </form>
                @endif
            </div>
            @endif

            <div class="eq-card-header">
                <div class="eq-icon" style="background:{{ $iconData[1] }};">
                    <i class="bi {{ $iconData[0] }}" style="color:{{ $iconData[2] }};"></i>
                </div>
                <div style="flex:1;min-width:0;padding-right:{{ auth()->user()->role === 'Admin' ? '50px' : '0' }};">
                    <div class="eq-code">{{ $eq->code }}</div>
                    <div class="eq-name">{{ $eq->name }}</div>
                </div>
            </div>

            @if($eq->location)
            <div class="eq-location">
                <i class="bi bi-geo-alt"></i>
                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $eq->location }}</span>
            </div>
            @endif

            <div class="eq-badges">
                {{-- Status --}}
                <span class="eq-badge" style="background:{{ $eq->status_color }}20;color:{{ $eq->status_color }};">
                    {{ $eq->status_label }}
                </span>
                {{-- Criticality --}}
                <span class="eq-badge" style="background:{{ $eq->criticality_bg }};color:{{ $eq->criticality_color }};">
                    <i class="bi bi-shield-exclamation"></i> {{ __($eq->criticality) }}
                </span>
                @if($eq->brand)
                <span class="eq-badge" style="background:var(--surface);color:var(--text-muted);border:1px solid var(--border);">
                    {{ $eq->brand }}
                </span>
                @endif
            </div>

            {{-- Next maintenance alert --}}
            @if($isOverdue)
            <div class="next-maint-alert" style="background:#FEE2E2;color:#991B1B;">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ __('Mantenimiento vencido') }}
            </div>
            @elseif($isUrgent)
            <div class="next-maint-alert" style="background:#FEF3C7;color:#78350F;">
                <i class="bi bi-calendar-event-fill"></i> {{ __('Mant. en') }} {{ $eq->days_to_next_maintenance }} {{ __('días') }}
            </div>
            @endif

            <div class="eq-actions">
                <a href="{{ route('equipment.show', $eq) }}"
                   style="background:#DBEAFE;color:#1E40AF;">
                    <i class="bi bi-eye me-1"></i>{{ __('Ver') }}
                </a>
                @if(auth()->user()->role === 'Admin')
                <a href="{{ route('maintenances.create') }}?equipment={{ $eq->id }}"
                   style="background:#D1FAE5;color:#065F46;">
                    <i class="bi bi-clipboard-plus me-1"></i>{{ __('OT') }}
                </a>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;" class="text-center py-5 animate-in">
            <i class="bi bi-cpu" style="font-size:3.5rem;color:var(--text-light);opacity:0.35;display:block;margin-bottom:1rem;"></i>
            <h4 class="text-muted fw-bold">{{ __('No hay activos registrados') }}</h4>
            <p class="text-muted">{{ __('Comienza registrando el primer equipo de la planta') }}</p>
            @if(auth()->user()->role === 'Admin')
            <a href="{{ route('equipment.create') }}" class="btn-navy btn mt-2">
                <i class="bi bi-plus me-1"></i>{{ __('Registrar Activo') }}
            </a>
            @endif
        </div>
        @endforelse
    </div>

    @if($equipments->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $equipments->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    let searchTimer;
    const input = document.getElementById('searchInput');
    const form  = document.getElementById('searchForm');
    const icon  = document.getElementById('searchIcon');
    const area  = document.getElementById('resultsArea');

    if (input && form && area) {
        input.addEventListener('input', function() {
            clearTimeout(searchTimer);
            if(icon) icon.className = 'spinner-border spinner-border-sm text-muted';
            searchTimer = setTimeout(() => {
                const url = new URL(form.action);
                url.searchParams.set('search', this.value);
                // Also carry over active filters
                const formData = new FormData(form);
                for(let [key, val] of formData.entries()) {
                    if(val && val !== 'all' && key !== 'search') url.searchParams.set(key, val);
                }
                
                fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newArea = doc.getElementById('resultsArea');
                        if (newArea) area.innerHTML = newArea.innerHTML;
                        if(icon) icon.className = 'bi bi-search text-muted';
                    })
                    .catch(() => { if(icon) icon.className = 'bi bi-search text-muted'; });
            }, 400);
        });
    }
});
</script>
@endpush
