@extends('layouts.app')
@section('title', __('Órdenes de Trabajo'))

@push('styles')
<style nonce="{{ $cspNonce }}">
.ot-row { transition: background 0.15s; }
.ot-row:hover { background: rgba(56,189,248,0.04); }
.priority-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.filter-chip { display: inline-flex; align-items: center; gap: 5px; padding: 5px 13px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; border: 1.5px solid var(--border); background: var(--surface); color: var(--text-muted); text-decoration: none; transition: all 0.2s; white-space: nowrap; }
.filter-chip:hover { border-color: var(--accent); color: var(--accent); text-decoration: none; }
.filter-chip.active { background: var(--navy); color: #fff; border-color: var(--navy); }
.ot-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 0.75rem; transition: box-shadow 0.2s; position: relative; }
.ot-card:hover { box-shadow: var(--shadow-md); }
.ot-card.priority-critical { border-left: 3px solid #EF4444; }
.ot-card.priority-high     { border-left: 3px solid #F59E0B; }
.ot-card.priority-normal   { border-left: 3px solid #3B82F6; }
.ot-card.priority-low      { border-left: 3px solid #94A3B8; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1><i class="bi bi-clipboard2-data-fill me-2" style="color:var(--accent);"></i>{{ __('Órdenes de Trabajo') }}</h1>
        <div class="page-breadcrumb d-flex flex-wrap gap-2 mt-1">
            <span>{{ __('Total:') }} <strong>{{ $totalCount }}</strong></span>
            @if($criticalCount > 0)
            <span style="color:#EF4444;">⚠️ {{ $criticalCount }} {{ __('críticas') }}</span>
            @endif
            <span style="color:#F59E0B;">{{ $pendingCount }} {{ __('pendientes') }}</span>
            <span style="color:#38BDF8;">{{ $progressCount }} {{ __('en curso') }}</span>
        </div>
    </div>
    <div class="page-actions">
        <form id="searchForm" action="{{ route('maintenances.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-color:var(--border);">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                       style="border-color:var(--border);min-width:140px;"
                       placeholder="{{ __('Buscar...') }}" value="{{ request('search') }}">
                @foreach(['status','priority','type'] as $f)
                    @if(request($f)) <input type="hidden" name="{{ $f }}" value="{{ request($f) }}"> @endif
                @endforeach
            </div>
        </form>
        @if(auth()->user()->role === 'Admin')
        <a href="{{ route('maintenances.create') }}" class="btn-navy btn">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('Nueva OT') }}</span>
        </a>
        @endif
    </div>
</div>

<div class="content-area" id="resultsArea">
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-3 animate-in">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif


    {{-- Filters --}}
    <div class="d-flex flex-column gap-2 mb-4 animate-in">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span style="font-size:0.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ __('Estado:') }}</span>
            @foreach([
                ['all',__('Todos'),'bi-grid'],
                ['Pending',__('Pendiente'),'bi-hourglass-split'],
                ['In Progress',__('En Progreso'),'bi-arrow-repeat'],
                ['Completed',__('Completadas'),'bi-check-circle-fill'],
            ] as [$v,$l,$i])
            <a href="{{ route('maintenances.index', array_merge(request()->except('status','page'), $v !== 'all' ? ['status'=>$v] : [])) }}"
               class="filter-chip {{ request('status','all') === $v ? 'active' : '' }}">
                <i class="bi {{ $i }}"></i> {{ $l }}
            </a>
            @endforeach
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span style="font-size:0.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ __('Prioridad:') }}</span>
            @foreach([
                ['all',__('Todas'),'#64748B'],
                ['Critical',__('Crítica'),'#EF4444'],
                ['High',__('Alta'),'#F59E0B'],
                ['Normal',__('Normal'),'#3B82F6'],
                ['Low',__('Baja'),'#94A3B8'],
            ] as [$v,$l,$c])
            @php $active = request('priority','all') === $v; @endphp
            <a href="{{ route('maintenances.index', array_merge(request()->except('priority','page'), $v !== 'all' ? ['priority'=>$v] : [])) }}"
               class="filter-chip {{ $active ? 'active' : '' }}" style="{{ !$active ? 'color:'.$c.';border-color:'.$c.'40;' : '' }}">
                {{ $l }}
            </a>
            @endforeach
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span style="font-size:0.7rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ __('Tipo:') }}</span>
            @foreach([
                ['all',__('Todos')],
                ['Preventive',__('Preventivo')],
                ['Corrective',__('Correctivo')],
            ] as [$v,$l])
            <a href="{{ route('maintenances.index', array_merge(request()->except('type','page'), $v !== 'all' ? ['type'=>$v] : [])) }}"
               class="filter-chip {{ request('type','all') === $v ? 'active' : '' }}">{{ $l }}</a>
            @endforeach
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="card no-hover animate-in d-none d-md-block" style="border:none;overflow:hidden;">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:70px;">{{ __('OT #') }}</th>
                        <th>{{ __('Equipo') }}</th>
                        <th>{{ __('Tipo') }}</th>
                        <th>{{ __('Prioridad') }}</th>
                        <th class="d-none d-xl-table-cell">{{ __('Técnico') }}</th>
                        <th class="d-none d-lg-table-cell">{{ __('Fecha') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-end">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $m)
                    @php
                        $pColor = $m->priority_color;
                        $pBg    = $m->priority_bg;
                        $sColor = match($m->status){ 'Completed'=>'#10B981','In Progress'=>'#F59E0B',default=>'#3B82F6' };
                        $sBg    = match($m->status){ 'Completed'=>'#D1FAE5','In Progress'=>'#FEF3C7',default=>'#DBEAFE' };
                    @endphp
                    <tr class="ot-row {{ $m->is_overdue ? 'table-warning' : '' }}">
                        <td>
                            <span style="font-family:monospace;font-weight:700;color:var(--text-muted);font-size:0.82rem;">#{{ str_pad($m->id,4,'0',STR_PAD_LEFT) }}</span>
                            @if($m->is_overdue)
                            <br><span style="font-size:0.65rem;color:#EF4444;font-weight:700;">{{ __('Vencida') }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:700;color:var(--navy);font-size:0.875rem;">{{ $m->equipment->name ?? '—' }}</div>
                            <div style="font-size:0.72rem;color:var(--text-muted);font-family:monospace;">{{ $m->equipment->code ?? '' }}</div>
                        </td>
                        <td>
                            <span style="padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;background:{{ $m->type==='Preventive' ? '#DBEAFE' : '#FEE2E2' }};color:{{ $m->type==='Preventive' ? '#1E40AF' : '#991B1B' }};">
                                <i class="bi bi-{{ $m->type==='Preventive' ? 'shield-check' : 'tools' }}"></i>
                                {{ $m->type === 'Preventive' ? __('Prev.') : __('Corr.') }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <div class="priority-dot" style="background:{{ $pColor }};"></div>
                                <span style="font-size:0.75rem;font-weight:700;color:{{ $pColor }};">{{ $m->priority_label }}</span>
                            </div>
                        </td>
                        <td class="d-none d-xl-table-cell" style="font-size:0.82rem;">{{ $m->technician->name ?? '—' }}</td>
                        <td class="d-none d-lg-table-cell" style="font-size:0.82rem;color:var(--text-muted);">
                            {{ $m->date->format('d/m/Y') }}
                            @if($m->estimated_hours) <br><span style="font-size:0.7rem;"><i class="bi bi-clock"></i> {{ $m->estimated_hours }}h est.</span> @endif
                        </td>
                        <td>
                            @if(auth()->user()->role === 'Admin')
                            <div class="dropdown">
                                <button class="btn btn-sm border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        style="padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;background:{{ $sBg }};color:{{ $sColor }};">
                                    {{ $m->status_label }}
                                </button>
                                <ul class="dropdown-menu shadow-sm border-0" style="font-size:0.78rem;">
                                    @foreach(['Pending'=>'⏳ '.__('Pendiente'),'In Progress'=>'▶ '.__('En Progreso'),'Completed'=>'✅ '.__('Completada')] as $sv=>$sl)
                                    <li>
                                        <form action="{{ route('maintenances.status', $m) }}" method="POST">
                                            @csrf <input type="hidden" name="_method" value="PATCH">
                                            <input type="hidden" name="status" value="{{ $sv }}">
                                            <button type="submit" class="dropdown-item">{{ $sl }}</button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @else
                            <span style="padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;background:{{ $sBg }};color:{{ $sColor }};">{{ $m->status_label }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('maintenances.show', $m) }}" class="btn-ghost btn btn-sm py-1 px-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'Admin')
                                <a href="{{ route('maintenances.edit', $m) }}" class="btn btn-sm py-1 px-2"
                                   style="background:#DBEAFE;color:#1E40AF;border:none;border-radius:8px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('maintenances.destroy', $m) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('¿Seguro que desea eliminar?') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm py-1 px-2" style="background:#FEE2E2;color:#991B1B;border:none;border-radius:8px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-clipboard-x" style="font-size:3rem;display:block;margin-bottom:0.75rem;opacity:0.3;"></i>
                            {{ __('Sin órdenes de trabajo') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none animate-in">
        @forelse($maintenances as $m)
        @php $pColor = $m->priority_color; @endphp
        <div class="ot-card priority-{{ strtolower($m->priority ?? 'normal') }}">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <div style="font-family:monospace;font-size:0.7rem;color:var(--text-muted);font-weight:700;">
                        OT #{{ str_pad($m->id,4,'0',STR_PAD_LEFT) }}
                        @if($m->is_overdue) <span style="color:#EF4444;">— {{ __('Vencida') }}</span> @endif
                    </div>
                    <div style="font-size:0.95rem;font-weight:700;color:var(--navy);margin:2px 0;">{{ $m->equipment->name ?? '—' }}</div>
                </div>
                <div class="d-flex flex-column align-items-end gap-1">
                    <span style="background:{{ $m->priority_bg }};color:{{ $pColor }};padding:2px 9px;border-radius:20px;font-size:0.68rem;font-weight:700;">
                        {{ $m->priority_label }}
                    </span>
                    <span style="background:{{ $m->status === 'Completed' ? '#D1FAE5' : ($m->status === 'In Progress' ? '#FEF3C7' : '#DBEAFE') }};color:{{ $m->status === 'Completed' ? '#065F46' : ($m->status === 'In Progress' ? '#78350F' : '#1E40AF') }};padding:2px 9px;border-radius:20px;font-size:0.68rem;font-weight:700;">
                        {{ $m->status_label }}
                    </span>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--text-muted);">
                <i class="bi bi-calendar3 me-1"></i>{{ $m->date->format('d/m/Y') }}
                @if($m->technician) &nbsp;·&nbsp; <i class="bi bi-person me-1"></i>{{ $m->technician->name }} @endif
                @if($m->estimated_hours) &nbsp;·&nbsp; <i class="bi bi-clock me-1"></i>{{ $m->estimated_hours }}h @endif
            </div>
            @if($m->description)
            <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $m->description }}
            </div>
            @endif
            <div class="d-flex gap-2 mt-3 pt-2" style="border-top:1px solid var(--border);">
                <a href="{{ route('maintenances.show', $m) }}" class="btn-ghost btn btn-sm flex-fill text-center">
                    <i class="bi bi-eye me-1"></i>{{ __('Ver') }}
                </a>
                @if(auth()->user()->role === 'Admin')
                <a href="{{ route('maintenances.edit', $m) }}" class="btn btn-sm flex-fill text-center"
                   style="background:#DBEAFE;color:#1E40AF;border:none;border-radius:8px;font-size:0.78rem;font-weight:600;">
                    <i class="bi bi-pencil me-1"></i>{{ __('Editar') }}
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5" style="color:var(--text-muted);">
            <i class="bi bi-clipboard-x" style="font-size:3rem;display:block;margin-bottom:0.75rem;opacity:0.3;"></i>
            <p>{{ __('Sin órdenes de trabajo') }}</p>
            @if(auth()->user()->role === 'Admin')
            <a href="{{ route('maintenances.create') }}" class="btn-navy btn btn-sm">
                <i class="bi bi-plus me-1"></i>{{ __('Crear primera OT') }}
            </a>
            @endif
        </div>
        @endforelse
    </div>

    @if($maintenances->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $maintenances->links('pagination::bootstrap-5') }}</div>
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
