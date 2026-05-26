@extends('layouts.app')
@section('title', __('Mi Panel') . ' — ' . __('Técnico'))

@push('styles')
<style nonce="{{ $cspNonce }}">
.hero-welcome{background:linear-gradient(135deg,#0A192F 0%,#112240 50%,#1E3A5F 100%);padding:1.5rem 1.25rem;position:relative;overflow:hidden;}
.hero-welcome::before{content:'';position:absolute;top:-60px;right:-60px;width:200px;height:200px;border-radius:50%;background:rgba(56,189,248,0.07);}
.hero-welcome h2{color:#fff;font-weight:800;font-size:1.3rem;margin-bottom:.2rem;}
.hero-welcome p{color:#94A3B8;font-size:.85rem;margin-bottom:0;}
.stat-mini{background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:.75rem .9rem;text-align:center;flex:1;min-width:60px;}
.stat-mini .val{font-size:1.5rem;font-weight:800;color:#fff;line-height:1;}
.stat-mini .lbl{font-size:.62rem;color:#94A3B8;margin-top:3px;text-transform:uppercase;letter-spacing:.4px;}
.ot-card{border-radius:12px;border:1px solid var(--border);background:#fff;padding:.875rem 1rem;display:flex;align-items:flex-start;gap:.875rem;transition:box-shadow .2s,transform .2s;}
.ot-card:hover{box-shadow:var(--shadow-md);transform:translateY(-1px);}
.ot-card.urgent{border-left:3px solid var(--danger);}
.ot-card.in-progress{border-left:3px solid var(--warning);}
.ot-card.completed{border-left:3px solid var(--success);opacity:.85;}
.ot-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
.ot-icon.urgent{background:#FEE2E2;color:var(--danger);}
.ot-icon.progress{background:#FEF3C7;color:var(--warning);}
.ot-icon.pending{background:#DBEAFE;color:#3B82F6;}
.ot-icon.completed{background:#D1FAE5;color:var(--success);}
.ot-title{font-size:.88rem;font-weight:700;color:var(--text-primary);margin-bottom:2px;}
.ot-meta{font-size:.73rem;color:var(--text-muted);}
.tab-pill{display:inline-flex;gap:3px;background:var(--surface);border-radius:9px;padding:3px;}
.tab-pill button{border:none;background:transparent;border-radius:7px;padding:5px 14px;font-size:.78rem;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all .2s;}
.tab-pill button.active{background:#fff;color:var(--navy);box-shadow:0 1px 4px rgba(0,0,0,.1);}
.equip-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;background:var(--surface);border:1px solid var(--border);font-size:.76rem;font-weight:600;color:var(--text-primary);text-decoration:none;transition:all .2s;}
.equip-chip:hover{background:#fff;box-shadow:var(--shadow-sm);color:var(--navy);}
.equip-chip .dot{width:6px;height:6px;border-radius:50%;}
.section-title{font-size:.95rem;font-weight:700;color:var(--navy);display:flex;align-items:center;gap:7px;margin-bottom:.875rem;}
.section-title i{font-size:1rem;color:var(--accent);}
.empty-state{text-align:center;padding:2rem 1rem;color:var(--text-muted);}
.empty-state i{font-size:2rem;opacity:.3;display:block;margin-bottom:.6rem;}
.badge-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.7rem;font-weight:700;}
.bp-urgent{background:#FEE2E2;color:#991B1B;}.bp-progress{background:#FEF3C7;color:#78350F;}
.bp-pending{background:#DBEAFE;color:#1E40AF;}.bp-done{background:#D1FAE5;color:#065F46;}
.progress-ring{width:76px;height:76px;}
.ring-track{fill:none;stroke:#E2E8F0;stroke-width:7;}
.ring-fill{fill:none;stroke-width:7;stroke-linecap:round;transform:rotate(-90deg);transform-origin:50% 50%;transition:stroke-dashoffset .6s ease;}
@media(max-width:575.98px){
    .hero-welcome{padding:1.1rem 1rem;}
    .hero-welcome h2{font-size:1.1rem;}
    .stat-mini .val{font-size:1.25rem;}
    .stat-mini .lbl{font-size:.58rem;}
    .ot-card{gap:.6rem; padding:.75rem .875rem;}
    .tab-pill button{padding:5px 10px; font-size:.72rem;}
}
</style>
@endpush

@section('content')

<div class="hero-welcome">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:1;">
        <div>
            <h2><i class="bi bi-person-badge-fill me-2"></i>{{ __('Hola,') }} {{ auth()->user()->name }} 👋</h2>
            <p><i class="bi bi-calendar3 me-1"></i>{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} &nbsp;·&nbsp; {{ __('Panel Técnico') }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('maintenances.index') }}" class="btn btn-sm d-flex align-items-center gap-2"
               style="background:rgba(56,189,248,.15);color:#38BDF8;border:1px solid rgba(56,189,248,.3);border-radius:10px;font-weight:600;font-size:.82rem;">
                <i class="bi bi-clipboard2-data-fill"></i> {{ __('Ver todas mis OTs') }}
            </a>
            <a href="{{ route('equipment.index') }}" class="btn btn-sm d-flex align-items-center gap-2"
               style="background:rgba(255,255,255,.06);color:#fff;border:1px solid rgba(255,255,255,.12);border-radius:10px;font-weight:600;font-size:.82rem;">
                <i class="bi bi-cpu"></i> {{ __('Equipos') }}
            </a>
        </div>
    </div>
    <div class="d-flex gap-3 mt-4 flex-wrap" style="position:relative;z-index:1;">
        <div class="stat-mini"><div class="val">{{ $myTotal }}</div><div class="lbl">{{ __('Total OTs') }}</div></div>
        <div class="stat-mini"><div class="val" style="color:#F59E0B;">{{ $myPending }}</div><div class="lbl">{{ __('Pendientes') }}</div></div>
        <div class="stat-mini"><div class="val" style="color:#38BDF8;">{{ $myInProgress }}</div><div class="lbl">{{ __('En Curso') }}</div></div>
        <div class="stat-mini"><div class="val" style="color:#10B981;">{{ $myCompleted }}</div><div class="lbl">{{ __('Completadas') }}</div></div>
        <div class="stat-mini"><div class="val">{{ $myEquipment->count() }}</div><div class="lbl">{{ __('Equipos') }}</div></div>
    </div>
</div>

<div class="content-area">
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 animate-in" style="border-radius:10px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if(isset($upcomingMaintenanceEquipments) && $upcomingMaintenanceEquipments->isNotEmpty())
    <div class="alert alert-warning mb-4 animate-in border-0 shadow-sm" style="border-radius:12px; background:#FFFBEB; border-left:4px solid #F59E0B!important; padding:1.25rem;">
        <div class="d-flex align-items-start gap-3">
            <div style="background:#FEF3C7; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#D97706; font-size:1.1rem;"></i>
            </div>
            <div style="flex:1; min-width:0;">
                <h6 class="fw-bold mb-1" style="color:#78350F; font-size:0.9rem;">
                    {{ __('Alertas de Mantenimiento Próximo') }}
                </h6>
                <div class="row g-2 mt-2">
                    @foreach($upcomingMaintenanceEquipments as $eq)
                        @php
                            $days = $eq->days_to_next_maintenance;
                            $badgeText = '';
                            $badgeStyle = '';
                            if ($days === null) {
                                continue;
                            } elseif ($days < 0) {
                                $badgeText = __('Atrasado por') . ' ' . abs($days) . ' ' . __('días');
                                $badgeStyle = 'background:#FEE2E2; color:#991B1B;';
                            } elseif ($days === 0) {
                                $badgeText = __('Hoy');
                                $badgeStyle = 'background:#FEF3C7; color:#78350F;';
                            } else {
                                $badgeText = __('En') . ' ' . $days . ' ' . __('días');
                                $badgeStyle = 'background:#E0F2FE; color:#0369A1;';
                            }
                        @endphp
                        <div class="col-sm-6 col-md-4">
                            <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border border-light" style="font-size:0.8rem;">
                                <a href="{{ route('equipment.show', $eq->id) }}" class="fw-semibold text-decoration-none" style="color:#1E293B; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:60%;">
                                    {{ $eq->name }}
                                </a>
                                <span class="badge-status py-1 px-2 font-monospace" style="font-size:0.7rem; font-weight:700; border-radius:20px; {{ $badgeStyle }}">
                                    {{ $badgeText }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Urgent / In-Progress --}}
            @if($urgentOTs->count() > 0 || $inProgressOTs->count() > 0)
            <div class="mb-4 animate-in">
                <div class="section-title">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#EF4444;"></i>
                    {{ __('Atención Inmediata') }}
                    <span class="badge-pill bp-urgent ms-1">{{ $urgentOTs->count() + $inProgressOTs->count() }}</span>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($inProgressOTs as $ot)
                    <div class="ot-card in-progress">
                        <div class="ot-icon progress"><i class="bi bi-arrow-repeat"></i></div>
                        <div style="flex:1;min-width:0;">
                            <div class="ot-title">{{ $ot->equipment->name ?? '—' }}</div>
                            <div class="ot-meta"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($ot->date)->format('d/m/Y') }} &nbsp;·&nbsp; OT #{{ str_pad($ot->id,4,'0',STR_PAD_LEFT) }} &nbsp;·&nbsp; <span class="text-warning fw-semibold">{{ __('En Progreso') }}</span></div>
                        </div>
                        <div class="d-flex gap-2 flex-shrink-0">
                            <a href="{{ route('maintenances.show', $ot->id) }}" class="btn btn-sm" style="background:#FEF3C7;color:#78350F;border:none;border-radius:8px;font-size:.76rem;font-weight:600;"><i class="bi bi-eye me-1"></i>{{ __('Ver') }}</a>
                            <a href="{{ route('maintenances.show', $ot->id) }}" class="btn btn-sm" style="background:#D1FAE5;color:#065F46;border:none;border-radius:8px;font-size:.76rem;font-weight:600;"><i class="bi bi-check-circle me-1"></i>{{ __('Completar') }}</a>
                        </div>
                    </div>
                    @endforeach
                    @foreach($urgentOTs->whereNotIn('id', $inProgressOTs->pluck('id')) as $ot)
                    <div class="ot-card urgent">
                        <div class="ot-icon urgent"><i class="bi bi-alarm-fill"></i></div>
                        <div style="flex:1;min-width:0;">
                            <div class="ot-title">{{ $ot->equipment->name ?? '—' }}</div>
                            <div class="ot-meta"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($ot->date)->format('d/m/Y') }} &nbsp;·&nbsp; OT #{{ str_pad($ot->id,4,'0',STR_PAD_LEFT) }} &nbsp;·&nbsp; <span class="text-danger fw-semibold">{{ __('Vence pronto') }}</span></div>
                        </div>
                        <div class="d-flex gap-2 flex-shrink-0">
                            <a href="{{ route('maintenances.show', $ot->id) }}" class="btn btn-sm" style="background:#FEE2E2;color:#991B1B;border:none;border-radius:8px;font-size:.76rem;font-weight:600;"><i class="bi bi-eye me-1"></i>{{ __('Ver') }}</a>
                            <form method="POST" action="{{ route('maintenances.status', $ot->id) }}" class="d-inline">
                                @csrf <input type="hidden" name="_method" value="PATCH"><input type="hidden" name="status" value="In Progress">
                                <button class="btn btn-sm" style="background:#FEF3C7;color:#78350F;border:none;border-radius:8px;font-size:.76rem;font-weight:600;"><i class="bi bi-play-circle me-1"></i>{{ __('Iniciar') }}</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- OT Tab Panel --}}
            <div class="animate-in delay-1">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div class="section-title mb-0"><i class="bi bi-clipboard2-data-fill"></i> {{ __('Mis Órdenes de Trabajo') }}</div>
                    <div class="tab-pill" id="otTabs">
                        <button class="active" onclick="showTab('upcoming',this)">{{ __('Próximas') }}</button>
                        <button onclick="showTab('completed',this)">{{ __('Recientes') }}</button>
                        <button onclick="showTab('all',this)">{{ __('Todas') }}</button>
                    </div>
                </div>

                <div id="tab-upcoming">
                    @if($upcomingOTs->isEmpty())
                        <div class="empty-state"><i class="bi bi-calendar-check"></i>{{ __('Sin OTs pendientes próximas.') }}</div>
                    @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($upcomingOTs as $ot)
                        <div class="ot-card">
                            <div class="ot-icon pending"><i class="bi bi-{{ $ot->type === 'Preventive' ? 'shield-check' : 'tools' }}"></i></div>
                            <div style="flex:1;min-width:0;">
                                <div class="ot-title">{{ $ot->equipment->name ?? '—' }}</div>
                                <div class="ot-meta"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($ot->date)->format('d M Y') }} &nbsp;·&nbsp; {{ $ot->type === 'Preventive' ? __('Preventivo') : __('Correctivo') }} &nbsp;·&nbsp; OT #{{ str_pad($ot->id,4,'0',STR_PAD_LEFT) }}</div>
                                @if($ot->description)<div class="ot-meta mt-1" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:340px;">{{ $ot->description }}</div>@endif
                            </div>
                            <div class="d-flex gap-2 flex-shrink-0">
                                <a href="{{ route('maintenances.show', $ot->id) }}" class="btn-ghost btn btn-sm py-1 px-2"><i class="bi bi-eye"></i></a>
                                <form method="POST" action="{{ route('maintenances.status', $ot->id) }}" class="d-inline">
                                    @csrf <input type="hidden" name="_method" value="PATCH"><input type="hidden" name="status" value="In Progress">
                                    <button class="btn btn-sm py-1" style="background:rgba(56,189,248,.1);color:#0EA5E9;border:none;border-radius:8px;font-size:.76rem;font-weight:600;"><i class="bi bi-play-circle me-1"></i>{{ __('Iniciar') }}</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div id="tab-completed" style="display:none;">
                    @if($recentCompleted->isEmpty())
                        <div class="empty-state"><i class="bi bi-check-circle"></i>{{ __('Aún no has completado ninguna OT.') }}</div>
                    @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($recentCompleted as $ot)
                        <div class="ot-card completed">
                            <div class="ot-icon completed"><i class="bi bi-check-circle-fill"></i></div>
                            <div style="flex:1;min-width:0;">
                                <div class="ot-title">{{ $ot->equipment->name ?? '—' }}</div>
                                <div class="ot-meta"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($ot->date)->format('d M Y') }} &nbsp;·&nbsp; {{ $ot->type === 'Preventive' ? __('Preventivo') : __('Correctivo') }}</div>
                                @if($ot->tech_notes)<div class="ot-meta mt-1" style="font-style:italic;">"{{ \Illuminate\Support\Str::limit($ot->tech_notes,80) }}"</div>@endif
                            </div>
                            <a href="{{ route('maintenances.show', $ot->id) }}" class="btn-ghost btn btn-sm py-1 px-2 flex-shrink-0"><i class="bi bi-eye"></i></a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div id="tab-all" style="display:none;">
                    <div class="card no-hover" style="border:none;overflow:hidden;">
                        <div class="table-responsive">
                            <table class="table mb-0" style="font-size:.85rem;">
                                <thead><tr><th>OT #</th><th>{{ __('Equipo') }}</th><th>{{ __('Tipo') }}</th><th>{{ __('Fecha') }}</th><th>{{ __('Estado') }}</th><th></th></tr></thead>
                                <tbody>
                                    @forelse($myMaintenances as $ot)
                                    @php
                                        $sc = match($ot->status){'Completed'=>'bp-done','In Progress'=>'bp-progress','Pending'=>'bp-pending',default=>'bp-urgent'};
                                        $sl = match($ot->status){'Completed'=>__('Completada'),'In Progress'=>__('En Curso'),'Pending'=>__('Pendiente'),default=>$ot->status};
                                    @endphp
                                    <tr>
                                        <td><span style="font-family:monospace;font-weight:700;color:var(--text-muted);">#{{ str_pad($ot->id,4,'0',STR_PAD_LEFT) }}</span></td>
                                        <td class="fw-semibold">{{ $ot->equipment->name ?? '—' }}</td>
                                        <td><span class="badge-pill {{ $ot->type==='Preventive'?'bp-pending':'bp-urgent' }}"><i class="bi bi-{{ $ot->type==='Preventive'?'shield-check':'tools' }}"></i> {{ $ot->type==='Preventive'?__('Prev.'):__('Corr.') }}</span></td>
                                        <td style="color:var(--text-muted);">{{ \Carbon\Carbon::parse($ot->date)->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="badge-pill {{ $sc }} border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; font-size: 0.72rem;">
                                                    {{ $sl }}
                                                </button>
                                                <ul class="dropdown-menu shadow-sm border-0" style="font-size: 0.75rem;">
                                                    <li>
                                                        <form action="{{ route('maintenances.status', $ot->id) }}" method="POST">
                                                            @csrf <input type="hidden" name="_method" value="PATCH">
                                                            <input type="hidden" name="status" value="Pending">
                                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                                                <span class="badge-pill bp-pending p-1 px-2">⏳</span> {{ __('Pendiente') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('maintenances.status', $ot->id) }}" method="POST">
                                                            @csrf <input type="hidden" name="_method" value="PATCH">
                                                            <input type="hidden" name="status" value="In Progress">
                                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                                                <span class="badge-pill bp-progress p-1 px-2">▶</span> {{ __('En Curso') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('maintenances.show', $ot->id) }}" class="dropdown-item d-flex align-items-center gap-2">
                                                            <span class="badge-pill bp-done p-1 px-2">✅</span> {{ __('Completar en Detalle') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="text-end"><a href="{{ route('maintenances.show', $ot->id) }}" class="btn-ghost btn btn-sm py-1 px-2"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">{{ __('Sin órdenes asignadas.') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($myMaintenances->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--border);">{{ $myMaintenances->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Performance Ring --}}
            <div class="card no-hover mb-4 animate-in delay-1">
                <div class="card-body text-center p-4">
                    <h6 class="section-title justify-content-center" style="margin-bottom:.75rem;"><i class="bi bi-graph-up-arrow"></i> {{ __('Mi Rendimiento') }}</h6>
                    @php $r=34; $circ=round(2*M_PI*$r,2); $offset=round($circ-($completionRate/100)*$circ,2); $rc=$completionRate>=70?'#10B981':($completionRate>=40?'#F59E0B':'#EF4444'); @endphp
                    <div class="d-flex justify-content-center mb-2">
                        <svg class="progress-ring" viewBox="0 0 80 80">
                            <circle class="ring-track" cx="40" cy="40" r="{{ $r }}"/>
                            <circle class="ring-fill" cx="40" cy="40" r="{{ $r }}" stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $offset }}" style="stroke:{{ $rc }};"/>
                            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="font-size:14px;font-weight:800;fill:var(--navy);">{{ $completionRate }}%</text>
                        </svg>
                    </div>
                    <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:1.2rem;">{{ __('Tasa de completación') }}</p>
                    <div class="d-flex flex-column gap-2">
                        @foreach([[__('Pendientes'),$myPending,'#3B82F6','bp-pending'],[__('En Progreso'),$myInProgress,'#F59E0B','bp-progress'],[__('Completadas'),$myCompleted,'#10B981','bp-done']] as [$lbl,$val,$col,$cls])
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:.8rem;color:var(--text-muted);"><span style="width:8px;height:8px;border-radius:50%;background:{{ $col }};display:inline-block;margin-right:6px;"></span>{{ $lbl }}</span>
                            <span class="badge-pill {{ $cls }}">{{ $val }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Monthly Chart --}}
            <div class="card no-hover mb-4 animate-in delay-2">
                <div class="card-body p-4">
                    <h6 class="section-title"><i class="bi bi-bar-chart-fill"></i> {{ __('Actividad Mensual') }}</h6>
                    <canvas id="techBarChart" height="160"></canvas>
                </div>
            </div>

            {{-- Type Doughnut --}}
            <div class="card no-hover mb-4 animate-in delay-2">
                <div class="card-body p-4">
                    <h6 class="section-title"><i class="bi bi-pie-chart-fill"></i> {{ __('Por Tipo') }}</h6>
                    @if($myTotal > 0)
                    <canvas id="techDoughnut" height="150"></canvas>
                    <div class="d-flex justify-content-around mt-3">
                        <div class="text-center"><div style="font-size:1.4rem;font-weight:800;color:#6366F1;">{{ $preventiveCount }}</div><div style="font-size:.72rem;color:var(--text-muted);">{{ __('Preventivos') }}</div></div>
                        <div style="width:1px;background:var(--border);"></div>
                        <div class="text-center"><div style="font-size:1.4rem;font-weight:800;color:#EF4444;">{{ $correctiveCount }}</div><div style="font-size:.72rem;color:var(--text-muted);">{{ __('Correctivos') }}</div></div>
                    </div>
                    @else
                    <div class="empty-state"><i class="bi bi-pie-chart"></i>{{ __('Sin datos aún.') }}</div>
                    @endif
                </div>
            </div>

            {{-- Equipos a Mantener --}}
            <div class="card no-hover animate-in delay-3" style="border:none; overflow:hidden;">
                <div class="card-body p-0">
                    <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="border-bottom:1px solid var(--border); background:linear-gradient(135deg,#F0F9FF,#EFF6FF);">
                        <h6 class="section-title mb-0">
                            <i class="bi bi-cpu-fill" style="color:#6366F1;"></i>
                            {{ __('Mis Equipos a Mantener') }}
                        </h6>
                        <span class="badge rounded-pill" style="background:#6366F1;color:#fff;font-size:0.7rem;padding:4px 10px;">{{ $myEquipment->count() }}</span>
                    </div>

                    @if($myEquipment->isEmpty())
                    <div class="empty-state"><i class="bi bi-cpu"></i>{{ __('Sin equipos asignados.') }}</div>
                    @else
                    <div class="d-flex flex-column">
                        @foreach($myEquipment as $eq)
                        @php
                            $statusColor = match($eq->status) {
                                'Operational'   => '#10B981',
                                'In Repair'     => '#F59E0B',
                                'Out of Service'=> '#EF4444',
                                default         => '#94A3B8'
                            };
                            $statusLabel = match($eq->status) {
                                'Operational'   => __('Operativo'),
                                'In Repair'     => __('En Reparación'),
                                'Out of Service'=> __('Fuera de Servicio'),
                                default         => $eq->status
                            };
                            $statusBg = match($eq->status) {
                                'Operational'   => '#D1FAE5',
                                'In Repair'     => '#FEF3C7',
                                'Out of Service'=> '#FEE2E2',
                                default         => '#F1F5F9'
                            };
                            // OT activa de este técnico sobre este equipo
                            $activeOT = $allMyOTs->where('equipment_id', $eq->id)
                                ->whereIn('status', ['Pending','In Progress'])
                                ->sortByDesc('id')->first();
                        @endphp
                        <div style="padding:0.875rem 1rem; border-bottom:1px solid var(--border); transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <a href="{{ route('equipment.show', $eq->id) }}" style="font-weight:700; font-size:0.875rem; color:var(--navy); text-decoration:none;">
                                    {{ $eq->name }}
                                </a>
                                <span style="background:{{ $statusBg }};color:{{ $statusColor }};font-size:0.68rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.4rem;vertical-align:middle;"></i>{{ $statusLabel }}
                                </span>
                            </div>
                            <div style="font-size:0.73rem;color:var(--text-muted); margin-bottom:0.5rem;">
                                <i class="bi bi-geo-alt me-1"></i>{{ $eq->location ?? '—' }}
                                @if($eq->code) &nbsp;·&nbsp; <span style="font-family:monospace;">{{ $eq->code }}</span>@endif
                            </div>
                            @if($activeOT)
                            <div class="d-flex align-items-center justify-content-between" style="background:{{ $activeOT->status === 'In Progress' ? '#FFFBEB' : '#EFF6FF' }};border-radius:8px;padding:6px 10px;">
                                <div style="font-size:0.75rem; color:{{ $activeOT->status === 'In Progress' ? '#78350F' : '#1E40AF' }}; font-weight:600;">
                                    <i class="bi bi-{{ $activeOT->status === 'In Progress' ? 'arrow-repeat' : 'clock' }} me-1"></i>
                                    OT #{{ str_pad($activeOT->id,4,'0',STR_PAD_LEFT) }} &nbsp;·&nbsp;
                                    {{ $activeOT->type === 'Preventive' ? __('Preventivo') : __('Correctivo') }}
                                    &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($activeOT->date)->format('d/m/Y') }}
                                </div>
                                <a href="{{ route('maintenances.show', $activeOT->id) }}"
                                   style="background:{{ $activeOT->status === 'In Progress' ? '#F59E0B' : '#3B82F6' }};color:#fff;font-size:0.7rem;font-weight:700;padding:4px 10px;border-radius:8px;text-decoration:none;">
                                    <i class="bi bi-arrow-right-circle me-1"></i>{{ __('Ver OT') }}
                                </a>
                            </div>
                            @else
                            <div style="font-size:0.73rem;color:#10B981;font-weight:600;">
                                <i class="bi bi-check-circle me-1"></i>{{ __('Sin OTs pendientes') }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/vendor/chartjs/chart.umd.min.js"></script>
<script nonce="{{ $cspNonce }}">
new Chart(document.getElementById('techBarChart'),{type:'bar',data:{labels:@json($chartLabels),datasets:[{data:@json($chartData),backgroundColor:'rgba(56,189,248,0.15)',borderColor:'#38BDF8',borderWidth:2,borderRadius:6,borderSkipped:false}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(0,0,0,0.04)'},ticks:{stepSize:1,font:{size:11}},beginAtZero:true},x:{grid:{display:false},ticks:{font:{size:11}}}}}});
@if($myTotal > 0)
new Chart(document.getElementById('techDoughnut'),{type:'doughnut',data:{labels:['{{ __("Preventivo") }}','{{ __("Correctivo") }}'],datasets:[{data:[{{ $preventiveCount }},{{ $correctiveCount }}],backgroundColor:['#6366F1','#EF4444'],borderWidth:0,hoverOffset:4}]},options:{responsive:true,cutout:'68%',plugins:{legend:{display:false}}}});
@endif
function showTab(n,b){['upcoming','completed','all'].forEach(t=>{document.getElementById('tab-'+t).style.display=t===n?'':'none';});document.querySelectorAll('#otTabs button').forEach(x=>x.classList.remove('active'));b.classList.add('active');}
</script>
@endpush
