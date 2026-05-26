@extends('layouts.app')
@section('title', __('Nuevo Activo'))

@push('styles')
<style nonce="{{ $cspNonce }}">
.form-section { background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.25rem; }
.form-section-title { font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: var(--accent); display: flex; align-items: center; gap: 6px; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border); }
.form-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.4rem; }
.form-control, .form-select { border: 1.5px solid var(--border); border-radius: var(--radius-md); font-size: 0.875rem; padding: 0.6rem 0.875rem; transition: border-color 0.2s; background: var(--card-bg); color: var(--text-primary); }
.form-control:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(56,189,248,0.12); }
.criticality-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; }
.crit-option input[type="radio"] { display: none; }
.crit-option label { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 6px; border-radius: var(--radius-md); border: 2px solid var(--border); cursor: pointer; font-size: 0.72rem; font-weight: 700; transition: all 0.2s; }
.crit-option input:checked + label { border-color: currentColor; }
.crit-option.critical label { color: #EF4444; } .crit-option.critical input:checked + label { background: #FEE2E2; }
.crit-option.high label { color: #F59E0B; } .crit-option.high input:checked + label { background: #FEF3C7; }
.crit-option.medium label { color: #3B82F6; } .crit-option.medium input:checked + label { background: #DBEAFE; }
.crit-option.low label { color: #10B981; } .crit-option.low input:checked + label { background: #D1FAE5; }
@media(max-width:575.98px){ .criticality-grid { grid-template-columns: repeat(2,1fr); } }
</style>
@endpush

@section('content')
<div class="page-header animate-in">
    <div>
        <div class="page-breadcrumb">{{ __('Activos') }} / {{ __('Nuevo') }}</div>
        <h1><i class="bi bi-plus-circle-fill me-2" style="color:var(--accent);"></i>{{ __('Registrar Nuevo Activo') }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('equipment.index') }}" class="btn-ghost btn">
            <i class="bi bi-arrow-left"></i> {{ __('Volver') }}
        </a>
    </div>
</div>

<div class="content-area">
<form action="{{ route('equipment.store') }}" method="POST">
    @csrf

    @if($errors->any())
    <div class="alert alert-danger mb-4 animate-in">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>{{ __('Por favor corrige los errores:') }}</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Información General --}}
            <div class="form-section animate-in">
                <div class="form-section-title">
                    <i class="bi bi-info-circle-fill"></i> {{ __('Información General') }}
                </div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">{{ __('Nombre del Activo') }} *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Ej. Compresor de Aire Tornillo" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Código / TAG') }} *</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code') }}" placeholder="EQ-001" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Categoría') }} *</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="" disabled {{ !old('category') ? 'selected' : '' }}>{{ __('Seleccionar...') }}</option>
                            @foreach(['Maquinaria Industrial','Eléctrico','HVAC','Hidráulico','Neumático','Combustión','Vehículos','Instrumentación','Otro'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ __($cat) }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Marca') }}</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Ej. Caterpillar">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Modelo') }}</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model') }}" placeholder="Ej. C15 ACERT">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Número de Serie') }}</label>
                        <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}" placeholder="SN-000-0000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Ubicación en Planta') }}</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Ej. Zona A — Cuarto de Fuerza">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Notas / Observaciones') }}</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Instrucciones especiales, frecuencia de mantenimiento, advertencias...') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Criticidad --}}
            <div class="form-section animate-in delay-1">
                <div class="form-section-title">
                    <i class="bi bi-shield-exclamation"></i> {{ __('Nivel de Criticidad') }}
                </div>
                <div class="criticality-grid">
                    @foreach([
                        ['Critical', '🔴', __('Crítico'), __('Parada total de planta')],
                        ['High',     '🟠', __('Alto'),    __('Impacto en producción')],
                        ['Medium',   '🔵', __('Medio'),   __('Operación parcial')],
                        ['Low',      '🟢', __('Bajo'),    __('Sin impacto crítico')],
                    ] as [$val, $emoji, $lbl, $desc])
                    <div class="crit-option {{ strtolower($val) }}">
                        <input type="radio" name="criticality" id="crit_{{ $val }}" value="{{ $val }}"
                               {{ old('criticality', 'Medium') === $val ? 'checked' : '' }}>
                        <label for="crit_{{ $val }}">
                            <span style="font-size:1.25rem;">{{ $emoji }}</span>
                            <strong>{{ $lbl }}</strong>
                            <span style="font-size:0.65rem;opacity:0.7;text-align:center;">{{ $desc }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('criticality')<div class="text-danger mt-2" style="font-size:0.8rem;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Estado y Fechas --}}
            <div class="form-section animate-in delay-1">
                <div class="form-section-title">
                    <i class="bi bi-calendar3"></i> {{ __('Estado y Fechas') }}
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Estado Actual') }} *</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Operational" {{ old('status','Operational') === 'Operational' ? 'selected' : '' }}>✅ {{ __('Operativo') }}</option>
                        <option value="In Repair"   {{ old('status') === 'In Repair' ? 'selected' : '' }}>🔧 {{ __('En Reparación') }}</option>
                        <option value="Out of Service" {{ old('status') === 'Out of Service' ? 'selected' : '' }}>🚫 {{ __('Fuera de Servicio') }}</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Fecha de Instalación') }}</label>
                    <input type="date" name="installation_date" class="form-control" value="{{ old('installation_date') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Próx. Mantenimiento') }}</label>
                    <input type="date" name="next_maintenance_date" class="form-control" value="{{ old('next_maintenance_date') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Vencimiento de Garantía') }}</label>
                    <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry') }}">
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-navy btn w-100 py-3" style="font-size:0.95rem;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ __('Registrar Activo') }}
            </button>
        </div>
    </div>
</form>
</div>
@endsection
