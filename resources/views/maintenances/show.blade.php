@extends('layouts.app')
@section('title', 'OT #' . str_pad($maintenance->id, 4, '0', STR_PAD_LEFT))

@push('styles')
<style nonce="{{ $cspNonce }}">
.ot-hero{background:linear-gradient(135deg,#0A192F,#1E3A5F);border-radius:14px;padding:1.5rem;margin-bottom:1.25rem;position:relative;overflow:hidden;}
.ot-hero::before{content:'';position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(56,189,248,0.06);}
.ot-hero .ot-num{font-family:monospace;font-size:.82rem;color:#38BDF8;font-weight:700;margin-bottom:.35rem;}
.ot-hero h2{color:#fff;font-weight:800;font-size:1.2rem;margin-bottom:.25rem;}
.ot-hero p{color:#94A3B8;font-size:.82rem;margin-bottom:0;}
.detail-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-muted);margin-bottom:4px;}
.detail-value{font-size:.9rem;font-weight:600;color:var(--text-primary);}
.status-flow{display:flex;align-items:center;gap:0;background:var(--surface);border-radius:12px;padding:.4rem;overflow:hidden;}
.sf-step{flex:1;text-align:center;padding:.5rem .2rem;border-radius:8px;font-size:.7rem;font-weight:600;color:var(--text-muted);}
.sf-step.done{background:#D1FAE5;color:#065F46;}
.sf-step.active{background:#FEF3C7;color:#78350F;}
.sf-step.next{background:#DBEAFE;color:#1E40AF;}
.sf-step i{display:block;font-size:.95rem;margin-bottom:2px;}
.notes-box{background:var(--surface);border-radius:12px;padding:.875rem 1rem;border:1.5px solid var(--border);}
.notes-box textarea{width:100%;border:none;background:transparent;resize:vertical;min-height:80px;font-size:.875rem;color:var(--text-primary);outline:none;font-family:inherit;}
@media(max-width:575.98px){
    .ot-hero{border-radius:10px;padding:1.1rem;}
    .ot-hero h2{font-size:1rem;}
}
</style>
@endpush

@section('content')
<div class="content-area" style="max-width:860px;margin:0 auto;">

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-3 animate-in" style="border-radius:10px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3 animate-in" style="border-radius:10px;">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Hero --}}
    <div class="ot-hero animate-in">
        <div class="ot-num">OT #{{ str_pad($maintenance->id,4,'0',STR_PAD_LEFT) }}</div>
        <h2><i class="bi bi-clipboard2-data-fill me-2"></i>{{ $maintenance->equipment->name ?? __('Sin equipo') }}</h2>
        <p>{{ $maintenance->equipment->code ?? '' }} &nbsp;·&nbsp; {{ $maintenance->equipment->location ?? '' }}</p>
        <div class="d-flex gap-3 mt-3 flex-wrap" style="position:relative;z-index:1;">
            @php
                $typeColor = $maintenance->type === 'Preventive' ? '#DBEAFE;color:#1E40AF' : '#FEE2E2;color:#991B1B';
                $statusColors = ['Pending'=>'#DBEAFE;color:#1E40AF','In Progress'=>'#FEF3C7;color:#78350F','Completed'=>'#D1FAE5;color:#065F46'];
                $sc = $statusColors[$maintenance->status] ?? '#F1F5F9;color:#475569';
                $pColor = $maintenance->priority_color ?? '#3B82F6';
                $pBg    = $maintenance->priority_bg ?? '#DBEAFE';
            @endphp
            <span style="background:{{ $typeColor }};padding:4px 14px;border-radius:20px;font-size:.78rem;font-weight:700;">
                <i class="bi bi-{{ $maintenance->type==='Preventive'?'shield-check':'exclamation-triangle-fill' }} me-1"></i>
                {{ $maintenance->type === 'Preventive' ? __('Preventivo') : __('Correctivo') }}
            </span>
            <span style="background:{{ $sc }};padding:4px 14px;border-radius:20px;font-size:.78rem;font-weight:700;">
                {{ $maintenance->status_label }}
            </span>
            @if($maintenance->priority)
            <span style="background:{{ $pBg }};color:{{ $pColor }};padding:4px 14px;border-radius:20px;font-size:.78rem;font-weight:700;">
                <i class="bi bi-flag-fill me-1"></i>{{ $maintenance->priority_label }}
            </span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Details Card --}}
            <div class="card no-hover mb-4 animate-in delay-1" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1.25rem;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-info-circle-fill" style="color:var(--accent);"></i> {{ __('Detalles de la Orden') }}
                    </h6>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="detail-label">{{ __('Equipo') }}</div>
                            <div class="detail-value">{{ $maintenance->equipment->name ?? '—' }}</div>
                            @if($maintenance->equipment)<small style="color:var(--text-muted);font-size:.8rem;">{{ $maintenance->equipment->brand }} {{ $maintenance->equipment->model }}</small>@endif
                        </div>
                        <div class="col-sm-6">
                            <div class="detail-label">{{ __('Técnico Asignado') }}</div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#38BDF8,#0EA5E9);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.78rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($maintenance->technician->name ?? 'T', 0, 1)) }}
                                </div>
                                <div class="detail-value">{{ $maintenance->technician->name ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="detail-label">{{ __('Fecha Programada') }}</div>
                            <div class="detail-value"><i class="bi bi-calendar3 me-1" style="color:var(--accent);"></i>{{ \Carbon\Carbon::parse($maintenance->date)->format('d \d\e F, Y') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="detail-label">{{ __('Tipo') }}</div>
                            <div class="detail-value">{{ $maintenance->type === 'Preventive' ? __('Mantenimiento Preventivo') : __('Mantenimiento Correctivo') }}</div>
                        </div>
                        @if($maintenance->estimated_hours || $maintenance->actual_hours)
                        <div class="col-sm-6">
                            <div class="detail-label">{{ __('Horas') }}</div>
                            <div class="detail-value">
                                @if($maintenance->estimated_hours)<span style="color:var(--text-muted);">{{ __('Est:') }}</span> {{ $maintenance->estimated_hours }}h @endif
                                @if($maintenance->actual_hours) &nbsp;·&nbsp; <span style="color:#10B981;">{{ __('Real:') }}</span> {{ $maintenance->actual_hours }}h @endif
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="detail-label">{{ __('Descripción / Instrucciones') }}</div>
                            <div style="background:var(--surface);border-radius:10px;padding:.875rem 1rem;font-size:.875rem;color:var(--text-primary);line-height:1.7;">
                                {{ $maintenance->description ?: __('Sin descripción.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 STATUS FLOW — Only for assigned Technician
                 (visible regardless of completed status)
                 ══════════════════════════════════════════════ --}}
            @if(auth()->user()->role === 'Technician' && $maintenance->technician_id === auth()->id())
            <div class="card no-hover mb-4 animate-in delay-2" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-arrow-right-circle-fill" style="color:var(--accent);"></i> {{ __('Actualizar Estado') }}
                    </h6>

                    {{-- Status Flow Visual --}}
                    <div class="status-flow mb-3">
                        <div class="sf-step {{ $maintenance->status==='Pending' ? 'active' : 'done' }}">
                            <i class="bi bi-hourglass-split"></i>{{ __('Pendiente') }}
                        </div>
                        <div style="width:20px;text-align:center;color:var(--text-light);font-size:.6rem;">▶</div>
                        <div class="sf-step {{ $maintenance->status==='In Progress' ? 'active' : ($maintenance->status==='Completed' ? 'done' : 'next') }}">
                            <i class="bi bi-arrow-repeat"></i>{{ __('En Progreso') }}
                        </div>
                        <div style="width:20px;text-align:center;color:var(--text-light);font-size:.6rem;">▶</div>
                        <div class="sf-step {{ $maintenance->status==='Completed' ? 'done' : 'next' }}">
                            <i class="bi bi-check-circle"></i>{{ __('Completada') }}
                        </div>
                    </div>

                    @if($maintenance->status !== 'Completed')
                    {{-- Status buttons --}}
                    <div class="d-flex gap-2 flex-wrap">
                        @if($maintenance->status === 'Pending')
                        <form method="POST" action="{{ route('maintenances.status', $maintenance->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="status" value="In Progress">
                            <button type="submit" class="btn btn-sm fw-semibold"
                                    style="background:#FEF3C7;color:#78350F;border:none;border-radius:8px;padding:8px 16px;">
                                <i class="bi bi-play-circle me-1"></i>{{ __('Marcar En Progreso') }}
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('maintenances.status', $maintenance->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="status" value="Completed">
                            <button type="submit" class="btn btn-sm fw-semibold"
                                    style="background:#D1FAE5;color:#065F46;border:none;border-radius:8px;padding:8px 16px;">
                                <i class="bi bi-check-circle me-1"></i>{{ __('Marcar Completada') }}
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="d-flex align-items-center gap-2" style="color:#10B981;font-weight:600;font-size:.875rem;">
                        <i class="bi bi-check-circle-fill"></i> {{ __('Esta orden de trabajo está completada') }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tech Notes — Always visible to assigned tech --}}
            <div class="card no-hover mb-4 animate-in delay-3" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-pencil-fill" style="color:var(--accent);"></i> {{ __('Mis Notas Técnicas') }}
                    </h6>
                    <form method="POST" action="{{ route('maintenances.notes', $maintenance->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="notes-box mb-3">
                            <textarea name="tech_notes"
                                placeholder="{{ __('Anota observaciones, piezas reemplazadas, lecturas de sensores, hallazgos...') }}">{{ old('tech_notes', $maintenance->tech_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-sm fw-semibold"
                                style="background:linear-gradient(135deg,#0A192F,#1E3A5F);color:#fff;border:none;border-radius:8px;padding:8px 18px;">
                            <i class="bi bi-save me-1"></i>{{ __('Guardar Notas') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Consumo de Inventario --}}
            <div class="card no-hover mb-4 animate-in delay-3" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-box-seam-fill" style="color:var(--accent);"></i> {{ __('Repuestos Utilizados') }}
                    </h6>

                    @if($maintenance->partsUsed->isNotEmpty())
                    <div class="table-responsive mb-3">
                        <table class="table table-sm mb-0" style="font-size:0.8rem;">
                            <thead style="background:var(--surface);">
                                <tr>
                                    <th>{{ __('Repuesto') }}</th>
                                    <th>{{ __('Cantidad') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenance->partsUsed as $part)
                                <tr>
                                    <td class="fw-bold" style="color:var(--navy);">{{ $part->name }} <span class="text-muted" style="font-weight:normal;font-family:monospace;font-size:0.7rem;">{{ $part->sku }}</span></td>
                                    <td>{{ $part->pivot->quantity_used }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted" style="font-size:0.8rem;">{{ __('No se han registrado repuestos utilizados en esta OT.') }}</p>
                    @endif

                    @if($maintenance->status !== 'Completed')
                    <div style="background:var(--surface);border-radius:10px;padding:1rem;margin-top:1rem;border:1px dashed var(--border);">
                        <h6 style="font-size:0.75rem;font-weight:800;text-transform:uppercase;color:var(--navy);margin-bottom:0.75rem;">{{ __('Añadir Repuesto a la OT') }}</h6>
                        <form method="POST" action="{{ route('maintenances.addPart', $maintenance->id) }}" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-7">
                                <label class="form-label" style="font-size:0.7rem;font-weight:700;color:var(--text-muted);">{{ __('Seleccionar Item') }}</label>
                                <select name="inventory_item_id" class="form-select form-select-sm" required style="border-color:var(--border);">
                                    <option value="" disabled selected>{{ __('Seleccionar repuesto en stock...') }}</option>
                                    @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ __('Stock:') }} {{ $item->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" style="font-size:0.7rem;font-weight:700;color:var(--text-muted);">{{ __('Cantidad') }}</label>
                                <input type="number" name="quantity_used" class="form-control form-control-sm" value="1" min="1" required style="border-color:var(--border);">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm w-100 fw-semibold" style="background:#10B981;color:#fff;border:none;">
                                    <i class="bi bi-plus"></i> {{ __('Añadir') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Admin: show tech notes + status changer --}}
            @if(auth()->user()->role === 'Admin')

            @if($maintenance->tech_notes)
            <div class="card no-hover mb-4 animate-in delay-2" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-journal-text" style="color:var(--accent);"></i> {{ __('Notas del Técnico') }}
                    </h6>
                    <div style="background:var(--surface);border-radius:10px;padding:.875rem 1rem;font-size:.875rem;line-height:1.7;color:var(--text-primary);">
                        {{ $maintenance->tech_notes }}
                    </div>
                </div>
            </div>
            @endif

            @if($maintenance->status !== 'Completed')
            <div class="card no-hover mb-4 animate-in delay-2" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-arrow-right-circle-fill" style="color:var(--accent);"></i> {{ __('Cambiar Estado') }}
                    </h6>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach(['Pending'=>['⏳','#DBEAFE','#1E40AF'],'In Progress'=>['▶','#FEF3C7','#78350F'],'Completed'=>['✅','#D1FAE5','#065F46']] as $sv=>[$emoji,$bg,$col])
                        @if($sv !== $maintenance->status)
                        <form method="POST" action="{{ route('maintenances.status', $maintenance->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="status" value="{{ $sv }}">
                            <button type="submit" class="btn btn-sm fw-semibold"
                                    style="background:{{ $bg }};color:{{ $col }};border:none;border-radius:8px;padding:8px 14px;">
                                {{ $emoji }} {{ $sv === 'Pending' ? __('Marcar Pendiente') : ($sv === 'In Progress' ? __('Marcar En Progreso') : __('Marcar Completada')) }}
                            </button>
                        </form>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @endif {{-- end Admin --}}

        </div>

        <div class="col-lg-4">
            {{-- Equipment Card --}}
            @if($maintenance->equipment)
            <div class="card no-hover mb-4 animate-in delay-1" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;"><i class="bi bi-cpu me-2" style="color:var(--accent);"></i>{{ __('Equipo') }}</h6>
                    @php
                        $eqStatus = $maintenance->equipment->status;
                        $eqColor  = match($eqStatus){'Operational'=>'#D1FAE5;color:#065F46','In Repair'=>'#FEF3C7;color:#78350F','Out of Service'=>'#FEE2E2;color:#991B1B',default=>'#F1F5F9;color:#475569'};
                        $eqLabel  = match($eqStatus){'Operational'=>__('Operativo'),'In Repair'=>__('En Reparación'),'Out of Service'=>__('Fuera de Servicio'),default=>$eqStatus};
                    @endphp
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:44px;height:44px;background:linear-gradient(135deg,var(--accent),#0EA5E9);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;flex-shrink:0;">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:var(--text-primary);">{{ $maintenance->equipment->name }}</div>
                            <div style="font-size:.76rem;color:var(--text-muted);">{{ $maintenance->equipment->code }}</div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2" style="font-size:.82rem;">
                        @if($maintenance->equipment->brand)<div><span style="color:var(--text-muted);">{{ __('Marca:') }}</span> <strong>{{ $maintenance->equipment->brand }}</strong></div>@endif
                        @if($maintenance->equipment->model)<div><span style="color:var(--text-muted);">{{ __('Modelo:') }}</span> <strong>{{ $maintenance->equipment->model }}</strong></div>@endif
                        @if($maintenance->equipment->location)<div><span style="color:var(--text-muted);"><i class="bi bi-geo-alt me-1"></i></span>{{ $maintenance->equipment->location }}</div>@endif
                    </div>
                    <div class="mt-3">
                        <span style="background:{{ $eqColor }};padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;">{{ $eqLabel }}</span>
                    </div>
                    <a href="{{ route('equipment.show', $maintenance->equipment->id) }}" class="btn-ghost btn btn-sm w-100 mt-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i>{{ __('Ver Activo') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="card no-hover animate-in delay-2" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;"><i class="bi bi-lightning-fill me-2" style="color:var(--accent);"></i>{{ __('Acciones') }}</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('maintenances.pdf', $maintenance) }}" class="btn btn-sm d-flex align-items-center gap-2 fw-semibold"
                           style="background:#FEE2E2;color:#991B1B;border:none;border-radius:10px;padding:10px 14px;">
                            <i class="bi bi-file-earmark-pdf-fill"></i> {{ __('Descargar PDF') }}
                        </a>
                        @if(auth()->user()->role === 'Admin')
                        <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-sm d-flex align-items-center gap-2 fw-semibold"
                           style="background:#DBEAFE;color:#1E40AF;border:none;border-radius:10px;padding:10px 14px;">
                            <i class="bi bi-pencil-fill"></i> {{ __('Editar OT') }}
                        </a>
                        @endif
                        <a href="{{ route('maintenances.index') }}" class="btn-ghost btn btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-left"></i> {{ __('Volver al listado') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
