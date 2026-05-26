@extends('layouts.app')

@section('title', __('Gestión de Usuarios'))

@section('content')
<div class="page-header animate-in">
    <div>
        <div class="page-breadcrumb">{{ __('Administración / Seguridad') }}</div>
        <h1>{{ __('Gestión de Usuarios') }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('users.create') }}" class="btn-navy">
            <i class="bi bi-person-plus-fill"></i> {{ __('Nuevo Usuario') }}
        </a>
    </div>
</div>

<div class="content-area">
    @if(session('success'))
        <div class="alert alert-success mb-4 animate-in">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4 animate-in">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm animate-in delay-1">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Rol') }}</th>
                        <th>{{ __('Creado') }}</th>
                        <th class="text-end">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="nav-user-avatar me-2" style="width:32px; height:32px; font-size:0.8rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @php
                                $badgeClass = match($user->role) {
                                    'Admin' => 'badge-oos', // Reddish
                                    'Technician' => 'badge-rep', // Orange
                                    'Analyst' => 'badge-prev', // Blue
                                    default => 'badge-op' // Green
                                };
                            @endphp
                            <span class="badge-status {{ $badgeClass }}">{{ $user->role }}</span>
                        </td>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $user->created_at->format('d M, Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="{{ __('Editar usuario') }}">
                                    <i class="bi bi-pencil-square"></i> {{ __('Editar') }}
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.') }}');" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Eliminar usuario') }}">
                                        <i class="bi bi-trash3"></i> {{ __('Eliminar') }}
                                    </button>
                                </form>
                                @else
                                <span class="text-muted" style="font-size: 0.85rem;">{{ __('(Tu cuenta)') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-top">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
