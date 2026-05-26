@extends('layouts.app')
@section('title', $equipment->name)

@push('styles')
<style nonce="{{ $cspNonce }}">
.eq-hero { background: linear-gradient(135deg, #0A192F 0%, #112240 60%, #1E3A5F 100%); border-radius: 16px; padding: 1.75rem; position: relative; overflow: hidden; margin-bottom: 1.5rem; }
.eq-hero::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: rgba(56,189,248,0.07); }
.eq-hero::after { content: ''; position: absolute; bottom: -30px; left: 40%; width: 120px; height: 120px; border-radius: 50%; background: rgba(56,189,248,0.04); }
.detail-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 0.73rem; font-weight: 700; }
.info-row { display: flex; align-items: flex-start; gap: 10px; padding: 0.6rem 0; border-bottom: 1px solid var(--border); }
.info-row:last-child { border-bottom: none; }
.info-row .ir-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); min-width: 130px; padding-top: 2px; }
.info-row .ir-value { font-size: 0.875rem; color: var(--text-primary); font-weight: 500; }
.history-item { display: flex; gap: 12px; padding: 0.875rem 0; border-bottom: 1px solid var(--border); }
.history-item:last-child { border-bottom: none; }
.hist-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.kpi-mini { background: var(--surface); border-radius: 12px; padding: 1rem; text-align: center; }
.kpi-mini .val { font-size: 1.6rem; font-weight: 800; color: var(--navy); line-height: 1; }
.kpi-mini .lbl { font-size: 0.68rem; color: var(--text-muted); margin-top: 4px; }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="eq-hero animate-in">
    <div style="position:relative;z-index:1;">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
            <div>
                <div style="font-family:monospace;font-size:0.78rem;color:#38BDF8;font-weight:700;margin-bottom:4px;">
                    {{ $equipment->code }}
                    @if($equipment->serial_number)
                    &nbsp;·&nbsp; S/N: {{ $equipment->serial_number }}
                    @endif
                </div>
                <h1 style="color:#fff;font-size:1.5rem;font-weight:800;margin-bottom:0.25rem;">{{ $equipment->name }}</h1>
                <p style="color:#94A3B8;font-size:0.85rem;margin-bottom:0;">
                    <i class="bi bi-geo-alt me-1"></i>{{ $equipment->location ?? __('Sin ubicación') }}
                    @if($equipment->brand || $equipment->model)
                    &nbsp;·&nbsp; {{ $equipment->brand }} {{ $equipment->model }}
                    @endif
                </p>
            </div>
            @if(auth()->user()->role === 'Admin')
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-sm d-flex align-items-center gap-2"
                   style="background:rgba(56,189,248,0.15);color:#38BDF8;border:1px solid rgba(56,189,248,0.3);border-radius:10px;font-weight:600;font-size:0.82rem;">
                    <i class="bi bi-pencil"></i> {{ __('Editar') }}
                </a>
            </div>
            @endif
        </div>

        <div class="d-flex flex-wrap gap-2">
            {{-- Status --}}
            @php
                $stColor = $equipment->status_color;
                $stBg = match($equipment->status) {
                    'Operational' => 'rgba(16,185,129,0.2)', 'In Repair' => 'rgba(245,158,11,0.2)',
                    'Out of Service' => 'rgba(239,68,68,0.2)', default => 'rgba(148,163,184,0.2)'
                };
            @endphp
            <span style="background:{{ $stBg }};color:{{ $stColor }};border:1px solid {{ $stColor }}40;padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:700;">
                <i class="bi bi-circle-fill me-1" style="font-size:0.45rem;vertical-align:middle;"></i>
                {{ $equipment->status_label }}
            </span>
            {{-- Criticality --}}
            <span style="background:{{ $equipment->criticality_bg }};color:{{ $equipment->criticality_color }};padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:700;">
                <i class="bi bi-shield-exclamation me-1"></i>
                {{ __($equipment->criticality) }}
            </span>
            {{-- Category --}}
            @if($equipment->category)
            <span style="background:rgba(255,255,255,0.08);color:#CBD5E1;padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                <i class="bi bi-tag me-1"></i> {{ __($equipment->category) }}
            </span>
            @endif
            {{-- Warranty --}}
            @if($equipment->warranty_expiry)
            <span style="background:{{ $equipment->is_warranty_active ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }};color:{{ $equipment->is_warranty_active ? '#10B981' : '#EF4444' }};padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                <i class="bi bi-shield-check me-1"></i>
                {{ $equipment->is_warranty_active ? __('Garantía activa') : __('Garantía vencida') }}
            </span>
            @endif
            {{-- Next maintenance --}}
            @if($equipment->next_maintenance_date)
            @php $days = $equipment->days_to_next_maintenance; @endphp
            <span style="background:{{ $days <= 7 ? 'rgba(245,158,11,0.2)' : 'rgba(56,189,248,0.1)' }};color:{{ $days <= 7 ? '#F59E0B' : '#38BDF8' }};padding:5px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                <i class="bi bi-calendar-event me-1"></i>
                @if($days < 0) {{ __('Mantenimiento vencido') }}
                @elseif($days === 0) {{ __('Mantenimiento hoy') }}
                @elseif($days <= 7) {{ __('Mantenimiento en') }} {{ $days }} {{ __('días') }}
                @else {{ $equipment->next_maintenance_date->format('d/m/Y') }}
                @endif
            </span>
            @endif
        </div>
    </div>
</div>

<div class="content-area">
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 animate-in">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- KPIs del equipo --}}
            <div class="row g-3 mb-4 animate-in">
                <div class="col-4">
                    <div class="kpi-mini">
                        <div class="val">{{ $history->count() }}</div>
                        <div class="lbl">{{ __('Total OTs') }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="kpi-mini">
                        <div class="val" style="color:#10B981;">{{ $history->where('status','Completed')->count() }}</div>
                        <div class="lbl">{{ __('Completadas') }}</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="kpi-mini">
                        <div class="val" style="color:#F59E0B;">{{ $mttr ? round($mttr, 1) . 'd' : '—' }}</div>
                        <div class="lbl">MTTR</div>
                    </div>
                </div>
            </div>

            {{-- Historial de Mantenimiento --}}
            <div class="card no-hover animate-in delay-1" style="border:none;">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid var(--border);">
                        <h6 style="font-size:0.9rem;font-weight:700;color:var(--navy);margin:0;">
                            <i class="bi bi-clock-history me-2" style="color:var(--accent);"></i>{{ __('Historial de Mantenimiento') }}
                        </h6>
                        <a href="{{ route('maintenances.index') }}?equipment={{ $equipment->id }}" class="btn-ghost btn btn-sm">
                            {{ __('Ver todas') }} <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="px-4">
                        @forelse($history as $h)
                        @php
                            $hColor = match($h->status) {
                                'Completed' => '#10B981', 'In Progress' => '#F59E0B', default => '#3B82F6'
                            };
                            $pColor = $h->priority_color;
                        @endphp
                        <div class="history-item">
                            <div class="hist-dot" style="background:{{ $hColor }};"></div>
                            <div style="flex:1;min-width:0;">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                    <div style="font-weight:700;font-size:0.875rem;color:var(--navy);">
                                        {{ $h->type === 'Preventive' ? __('Preventivo') : __('Correctivo') }}
                                        <span style="font-family:monospace;font-size:0.7rem;color:var(--text-muted);font-weight:400;">OT #{{ str_pad($h->id,4,'0',STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <span style="background:{{ $h->priority_bg }};color:{{ $pColor }};padding:2px 10px;border-radius:20px;font-size:0.68rem;font-weight:700;">
                                            {{ $h->priority_label }}
                                        </span>
                                        <span style="background:{{ $hColor }}20;color:{{ $hColor }};padding:2px 10px;border-radius:20px;font-size:0.68rem;font-weight:700;">
                                            {{ $h->status_label }}
                                        </span>
                                    </div>
                                </div>
                                <div style="font-size:0.8rem;color:var(--text-muted);">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $h->date->format('d M Y') }}
                                    @if($h->technician) &nbsp;·&nbsp; <i class="bi bi-person me-1"></i>{{ $h->technician->name }} @endif
                                    @if($h->actual_hours) &nbsp;·&nbsp; <i class="bi bi-clock me-1"></i>{{ $h->actual_hours }}h @endif
                                </div>
                                @if($h->description)
                                <div style="font-size:0.78rem;color:var(--text-muted);margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $h->description }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5" style="color:var(--text-muted);">
                            <i class="bi bi-clipboard-x" style="font-size:2.5rem;display:block;margin-bottom:0.75rem;opacity:0.3;"></i>
                            <p>{{ __('Sin intervenciones registradas') }}</p>
                            @if(auth()->user()->role === 'Admin')
                            <a href="{{ route('maintenances.create') }}" class="btn-navy btn btn-sm">
                                <i class="bi bi-plus me-1"></i>{{ __('Crear OT') }}
                            </a>
                            @endif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Ficha Técnica --}}
            <div class="card no-hover mb-4 animate-in delay-1" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:0.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;">
                        <i class="bi bi-file-text-fill me-2" style="color:var(--accent);"></i>{{ __('Ficha Técnica') }}
                    </h6>

                    @if($equipment->brand)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Marca') }}</div>
                        <div class="ir-value">{{ $equipment->brand }}</div>
                    </div>
                    @endif
                    @if($equipment->model)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Modelo') }}</div>
                        <div class="ir-value">{{ $equipment->model }}</div>
                    </div>
                    @endif
                    @if($equipment->serial_number)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Nº Serie') }}</div>
                        <div class="ir-value" style="font-family:monospace;font-size:0.82rem;">{{ $equipment->serial_number }}</div>
                    </div>
                    @endif
                    @if($equipment->category)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Categoría') }}</div>
                        <div class="ir-value">{{ __($equipment->category) }}</div>
                    </div>
                    @endif
                    @if($equipment->location)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Ubicación') }}</div>
                        <div class="ir-value">{{ $equipment->location }}</div>
                    </div>
                    @endif
                    @if($equipment->installation_date)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Instalado') }}</div>
                        <div class="ir-value">{{ $equipment->installation_date->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    @if($equipment->warranty_expiry)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Garantía') }}</div>
                        <div class="ir-value" style="color:{{ $equipment->is_warranty_active ? '#10B981' : '#EF4444' }};">
                            {{ $equipment->warranty_expiry->format('d/m/Y') }}
                            {{ $equipment->is_warranty_active ? '✅' : '❌' }}
                        </div>
                    </div>
                    @endif
                    @if($equipment->next_maintenance_date)
                    <div class="info-row">
                        <div class="ir-label">{{ __('Próx. Mant.') }}</div>
                        <div class="ir-value" style="color:{{ $equipment->days_to_next_maintenance <= 7 ? '#F59E0B' : 'inherit' }};">
                            {{ $equipment->next_maintenance_date->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($equipment->notes)
            <div class="card no-hover mb-4 animate-in delay-2" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:0.85rem;font-weight:700;color:var(--navy);margin-bottom:0.75rem;">
                        <i class="bi bi-sticky-fill me-2" style="color:#F59E0B;"></i>{{ __('Notas') }}
                    </h6>
                    <p style="font-size:0.85rem;color:var(--text-muted);line-height:1.7;margin:0;">{{ $equipment->notes }}</p>
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="card no-hover animate-in delay-2" style="border:none;">
                <div class="card-body p-4">
                    <h6 style="font-size:0.85rem;font-weight:700;color:var(--navy);margin-bottom:1rem;">
                        <i class="bi bi-lightning-fill me-2" style="color:var(--accent);"></i>{{ __('Acciones') }}
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        @if(auth()->user()->role === 'Admin')
                        <a href="{{ route('maintenances.create') }}?equipment={{ $equipment->id }}" class="btn btn-sm d-flex align-items-center gap-2 fw-semibold"
                           style="background:#DBEAFE;color:#1E40AF;border:none;border-radius:10px;padding:10px 14px;">
                            <i class="bi bi-clipboard-plus"></i> {{ __('Crear OT') }}
                        </a>
                        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-sm d-flex align-items-center gap-2 fw-semibold"
                           style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:10px 14px;color:var(--text-primary);">
                            <i class="bi bi-pencil"></i> {{ __('Editar Activo') }}
                        </a>
                        @endif
                        <a href="{{ route('equipment.index') }}" class="btn-ghost btn btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-left"></i> {{ __('Volver al listado') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
