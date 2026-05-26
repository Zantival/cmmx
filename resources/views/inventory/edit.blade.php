@extends('layouts.app')
@section('title', __('Edit Item'))

@section('content')
<div class="page-header">
    <div>
        <h1><i class="bi bi-pencil-square me-2" style="color:var(--accent);"></i>{{ __('Edit Item') }}</h1>
        <div class="page-breadcrumb">
            {{ __('Update resource information and stock') }}
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
                <form action="{{ route('inventory.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('SKU') }}</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $item->sku) }}">
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $item->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('Stock') }}</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $item->stock) }}" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Unit Price') }} ($)</label>
                            <input type="number" step="0.01" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', $item->unit_price) }}" required>
                            @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Min. Stock') }}</label>
                            <input type="number" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', $item->min_stock) }}" required>
                            @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('Category') }}</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="General" {{ $item->category === 'General' ? 'selected' : '' }}>{{ __('General') }}</option>
                                <option value="Spare Part" {{ $item->category === 'Spare Part' ? 'selected' : '' }}>{{ __('Spare Part') }}</option>
                                <option value="Consumable" {{ $item->category === 'Consumable' ? 'selected' : '' }}>{{ __('Consumable') }}</option>
                                <option value="Tool" {{ $item->category === 'Tool' ? 'selected' : '' }}>{{ __('Tool') }}</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="mb-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('inventory.index') }}" class="btn btn-ghost px-4">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-navy px-5">
                                    <i class="bi bi-save me-2"></i>{{ __('Save changes') }}
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
