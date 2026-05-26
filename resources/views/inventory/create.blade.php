@extends('layouts.app')
@section('title', __('Add Item'))

@section('content')
<div class="page-header">
    <div>
        <h1><i class="bi bi-plus-circle-fill me-2" style="color:var(--accent);"></i>{{ __('New Resource') }}</h1>
        <div class="page-breadcrumb">
            {{ __('Add a new item to the industrial inventory') }}
        </div>
    </div>
    <a href="{{ route('inventory.index') }}" class="btn-ghost btn">
        <i class="bi bi-arrow-left me-1"></i>{{ __('Back') }}
    </a>
</div>

<div class="content-area">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card no-hover shadow-sm p-4 animate-in">
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('SKU') }}</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Ex: FIL-2024">
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('Stock') }}</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Unit Price') }} ($)</label>
                            <input type="number" step="0.01" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', 0) }}" required>
                            @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Min. Stock') }} ({{ __('Alert') }})</label>
                            <input type="number" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 5) }}" required>
                            @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('Category') }}</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="General">{{ __('General') }}</option>
                                <option value="Spare Part">{{ __('Spare Part') }}</option>
                                <option value="Consumable">{{ __('Consumable') }}</option>
                                <option value="Tool">{{ __('Tool') }}</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="mb-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('inventory.index') }}" class="btn btn-ghost px-4">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-navy px-5">
                                    <i class="bi bi-save me-2"></i>{{ __('Add Item') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
