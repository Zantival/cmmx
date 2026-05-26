@extends('layouts.app')

@section('title', __('Nuevo Usuario'))

@section('content')
<div class="page-header animate-in">
    <div>
        <div class="page-breadcrumb">{{ __('Administración / Usuarios / Nuevo') }}</div>
        <h1>{{ __('Crear Nuevo Usuario') }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('users.index') }}" class="btn-ghost">
            <i class="bi bi-arrow-left"></i> {{ __('Volver al listado') }}
        </a>
    </div>
</div>

<div class="content-area">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm animate-in delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">{{ __('Nombre Completo') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Correo Electrónico') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Rol del Sistema') }}</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>{{ __('Seleccione un rol...') }}</option>
                                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>{{ __('Administrador') }}</option>
                                <option value="Technician" {{ old('role') == 'Technician' ? 'selected' : '' }}>{{ __('Técnico') }}</option>
                                <option value="Analyst" {{ old('role') == 'Analyst' ? 'selected' : '' }}>{{ __('Analista') }}</option>
                                <option value="Seller" {{ old('role') == 'Seller' ? 'selected' : '' }}>{{ __('Vendedor / Proveedor') }}</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Contraseña') }}</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Confirmar Contraseña') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-top">
                            <button type="submit" class="btn-navy w-100 py-2">
                                <i class="bi bi-person-check-fill me-2"></i> {{ __('Crear Usuario') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
