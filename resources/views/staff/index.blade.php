@extends('layouts.app')

@section('content')
<div class="container px-3 pt-4 pb-5 mb-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold mb-0">Gestión de Staff</h1>
        <button class="btn btn-sm btn-navy rounded-pill px-3 shadow-none"><i class="bi bi-plus"></i> Nuevo</button>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="input-group drop-shadow-sm">
            <span class="input-group-text bg-white border-end-0 rounded-start" style="border-radius: 12px 0 0 12px;">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" class="form-control border-start-0 py-2 rounded-end" style="border-radius: 0 12px 12px 0; background-color: #fff;" placeholder="Buscar técnico, especialidad...">
        </div>
    </div>

    <!-- Staff List -->
    <div class="d-flex flex-column gap-3">
        
        <!-- Card 1 -->
        <div class="card border-0 p-3">
            <div class="d-flex align-items-center mb-3">
                <img src="https://ui-avatars.com/api/?name=Carlos+Ruiz&background=random" class="rounded-circle me-3" width="56" height="56" alt="Carlos">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Carlos Ruiz</h6>
                            <p class="small text-muted mb-0">Mecánico Senior</p>
                        </div>
                        <span class="badge badge-green small">Disponible</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center small text-muted mb-3">
                <i class="bi bi-geo-alt-fill me-2 fs-6"></i> Base Central
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light border w-100 fw-semibold rounded-element" style="font-size: 0.85rem;">Ver Asign.</button>
                <button class="btn btn-light border text-navy rounded-element px-3"><i class="bi bi-telephone-fill"></i></button>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card border-0 p-3">
            <div class="d-flex align-items-center mb-3">
                <img src="https://ui-avatars.com/api/?name=Ana+Torres&background=random" class="rounded-circle me-3" width="56" height="56" alt="Ana">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Ana Torres</h6>
                            <p class="small text-muted mb-0">Ing. Sistemas</p>
                        </div>
                        <span class="badge badge-blue small">En Sitio</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center small text-muted mb-3">
                <i class="bi bi-geo-alt-fill me-2 fs-6"></i> Zona B - Servidores
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light border w-100 fw-semibold rounded-element" style="font-size: 0.85rem;">Ver Asign.</button>
                <button class="btn btn-light border text-navy rounded-element px-3"><i class="bi bi-envelope-fill"></i></button>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card border-0 p-3" style="opacity: 0.8;">
            <div class="d-flex align-items-center mb-3">
                <img src="https://ui-avatars.com/api/?name=Miguel+Paz&background=random" class="rounded-circle me-3" width="56" height="56" alt="Miguel">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Miguel Paz</h6>
                            <p class="small text-muted mb-0">Especialista HVCA</p>
                        </div>
                        <span class="badge badge-red small">Ausente</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light border w-100 fw-semibold rounded-element" style="font-size: 0.85rem;" disabled>Ver Asign.</button>
                <button class="btn btn-light border text-navy rounded-element px-3"><i class="bi bi-telephone-fill"></i></button>
            </div>
        </div>

    </div>
</div>
@endsection
