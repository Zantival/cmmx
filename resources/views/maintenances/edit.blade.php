@extends('layouts.app')
@section('title', 'Revise Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="card p-2">
            <div class="card-header pb-0 d-flex align-items-center">
                <span class="material-symbols-outlined me-2 text-primary">edit_calendar</span>
                <h5 class="mb-0 brand-title">Revise Service Ticket</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;">Select Machinery *</label>
                            <select name="equipment_id" class="form-select py-2 @error('equipment_id') is-invalid @enderror" required>
                                <option value="">Select Equipment...</option>
                                @foreach($equipments as $eq)
                                    <option value="{{ $eq->id }}" {{ old('equipment_id', $maintenance->equipment_id) == $eq->id ? 'selected' : '' }}>
                                        {{ $eq->name }} ({{ $eq->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;">Protocol *</label>
                            <select name="type" class="form-select py-2 @error('type') is-invalid @enderror" required>
                                <option value="Preventive" {{ old('type', $maintenance->type) == 'Preventive' ? 'selected' : '' }}>Preventive</option>
                                <option value="Corrective" {{ old('type', $maintenance->type) == 'Corrective' ? 'selected' : '' }}>Corrective</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;">Timeline *</label>
                            <input type="date" name="date" class="form-control py-2 @error('date') is-invalid @enderror" value="{{ old('date', $maintenance->date) }}" required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;">Lead Engineer *</label>
                            <select name="technician_id" class="form-select py-2 @error('technician_id') is-invalid @enderror" required>
                                <option value="">Assign Personnel...</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ old('technician_id', $maintenance->technician_id) == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('technician_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-muted" style="font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;">Diagnosis / Instructions *</label>
                        <textarea name="description" class="form-control py-3 @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $maintenance->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top border-light">
                        <a href="{{ route('maintenances.index') }}" class="btn btn-light" style="border:1px solid var(--outline-variant);">Cancel</a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <span class="material-symbols-outlined me-2 fs-5">publish</span> Overwrite Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
