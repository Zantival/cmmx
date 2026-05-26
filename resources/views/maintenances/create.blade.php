@extends('layouts.app')
@section('title', __('Nueva OT'))

@push('styles')
<style nonce="{{ $cspNonce }}">
.form-section { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.25rem; }
.form-section-title { font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--accent); display: flex; align-items: center; gap: 6px; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border); }
.form-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.4rem; }
.form-control, .form-select { border: 1.5px solid var(--border); border-radius: var(--radius-md); font-size: 0.875rem; padding: 0.6rem 0.875rem; transition: border-color 0.2s; background: var(--card-bg); color: var(--text-primary); }
.form-control:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(56,189,248,0.12); }
.priority-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 0.5rem; }
.prio-option input[type="radio"] { display: none; }
.prio-option label { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 6px; border-radius: var(--radius-md); border: 2px solid var(--border); cursor: pointer; font-size: 0.72rem; font-weight: 700; transition: all 0.2s; }
.prio-option input:checked + label { border-color: currentColor; }
.prio-option.critical label { color:#EF4444; } .prio-option.critical input:checked + label { background:#FEE2E2; }
.prio-option.high    label { color:#F59E0B; } .prio-option.high    input:checked + label { background:#FEF3C7; }
.prio-option.normal  label { color:#3B82F6; } .prio-option.normal  input:checked + label { background:#DBEAFE; }
.prio-option.low     label { color:#94A3B8; } .prio-option.low     input:checked + label { background:#F1F5F9; }
.type-toggle { display: flex; gap: 0; background: var(--surface); border-radius: 10px; padding: 3px; }
.type-toggle input[type="radio"] { display: none; }
.type-toggle label { flex: 1; text-align: center; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); transition: all 0.2s; }
.type-toggle input:checked + label { background: var(--navy); color: #fff; box-shadow: 0 2px 8px rgba(10,25,47,0.15); }
@media(max-width:575.98px){ .priority-grid { grid-template-columns: repeat(2,1fr); } }
</style>
@endpush

@section('content')
<div class="page-header animate-in">
    <div>
        <div class="page-breadcrumb">{{ __('Órdenes de Trabajo') }} / {{ __('Nueva') }}</div>
        <h1><i class="bi bi-clipboard-plus-fill me-2" style="color:var(--accent);"></i>{{ __('Crear Orden de Trabajo') }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('maintenances.index') }}" class="btn-ghost btn">
            <i class="bi bi-arrow-left"></i> {{ __('Volver') }}
        </a>
    </div>
</div>

<div class="content-area">
<form action="{{ route('maintenances.store') }}" method="POST">
    @csrf

    @if($errors->any())
    <div class="alert alert-danger mb-4 animate-in">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <ul class="mb-0 mt-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Asignación --}}
            <div class="form-section animate-in">
                <div class="form-section-title"><i class="bi bi-link-45deg"></i> {{ __('Asignación') }}</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Equipo / Activo') }} *</label>
                        <select name="equipment_id" class="form-select @error('equipment_id') is-invalid @enderror" required>
                            <option value="" disabled {{ !old('equipment_id', $selectedEquipment?->id) ? 'selected' : '' }}>{{ __('Seleccionar equipo...') }}</option>
                            @foreach($equipments as $eq)
                            <option value="{{ $eq->id }}"
                                {{ old('equipment_id', $selectedEquipment?->id) == $eq->id ? 'selected' : '' }}>
                                {{ $eq->code }} — {{ $eq->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('equipment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Técnico Responsable') }} *</label>
                        <select name="technician_id" class="form-select @error('technician_id') is-invalid @enderror" required>
                            <option value="" disabled {{ !old('technician_id') ? 'selected' : '' }}>{{ __('Asignar técnico...') }}</option>
                            @foreach($technicians as $t)
                            <option value="{{ $t->id }}" {{ old('technician_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('technician_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Fecha Programada') }} *</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', now()->format('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Horas Estimadas') }}</label>
                        <div class="input-group">
                            <input type="number" name="estimated_hours" step="0.5" min="0.5"
                                   class="form-control @error('estimated_hours') is-invalid @enderror"
                                   value="{{ old('estimated_hours') }}" placeholder="2.5">
                            <span class="input-group-text" style="border-color:var(--border);">h</span>
                        </div>
                        @error('estimated_hours')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Tipo de Mantenimiento --}}
            <div class="form-section animate-in delay-1">
                <div class="form-section-title"><i class="bi bi-tools"></i> {{ __('Tipo de Intervención') }}</div>
                <div class="type-toggle mb-3">
                    <input type="radio" name="type" id="type_prev" value="Preventive" {{ old('type','Preventive') === 'Preventive' ? 'checked' : '' }}>
                    <label for="type_prev"><i class="bi bi-shield-check me-2"></i>{{ __('Preventivo') }}</label>
                    <input type="radio" name="type" id="type_corr" value="Corrective" {{ old('type') === 'Corrective' ? 'checked' : '' }}>
                    <label for="type_corr"><i class="bi bi-tools me-2"></i>{{ __('Correctivo') }}</label>
                </div>
                <div>
                    <label class="form-label">{{ __('Descripción / Instrucciones') }}</label>
                    <textarea name="description" class="form-control" rows="4"
                        placeholder="{{ __('Detalla el trabajo a realizar, piezas a revisar, observaciones del equipo...') }}">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Prioridad --}}
            <div class="form-section animate-in delay-1">
                <div class="form-section-title"><i class="bi bi-exclamation-triangle-fill"></i> {{ __('Prioridad') }}</div>
                <div class="priority-grid">
                    @foreach([
                        ['Critical','🔴',__('Crítica'), __('Parada de planta')],
                        ['High',    '🟠',__('Alta'),    __('Afecta producción')],
                        ['Normal',  '🔵',__('Normal'),  __('Planificado')],
                        ['Low',     '⚪',__('Baja'),    __('Sin urgencia')],
                    ] as [$val,$emoji,$lbl,$desc])
                    <div class="prio-option {{ strtolower($val) }}">
                        <input type="radio" name="priority" id="prio_{{ $val }}" value="{{ $val }}"
                               {{ old('priority','Normal') === $val ? 'checked' : '' }}>
                        <label for="prio_{{ $val }}">
                            <span style="font-size:1.1rem;">{{ $emoji }}</span>
                            <strong>{{ $lbl }}</strong>
                            <span style="font-size:0.62rem;opacity:0.7;text-align:center;">{{ $desc }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Estado --}}
            <div class="form-section animate-in delay-2">
                <div class="form-section-title"><i class="bi bi-activity"></i> {{ __('Estado Inicial') }}</div>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="Pending"     {{ old('status','Pending') === 'Pending'     ? 'selected' : '' }}>⏳ {{ __('Pendiente') }}</option>
                    <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>▶ {{ __('En Progreso') }}</option>
                    <option value="Completed"   {{ old('status') === 'Completed'   ? 'selected' : '' }}>✅ {{ __('Completada') }}</option>
                </select>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-navy btn w-100 py-3" style="font-size:0.95rem;">
                <i class="bi bi-clipboard-check-fill me-2"></i> {{ __('Crear Orden de Trabajo') }}
            </button>
        </div>
    </div>
</form>
</div>
@endsection
