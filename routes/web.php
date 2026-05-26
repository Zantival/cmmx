<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SellerOnboardingController;
use App\Http\Controllers\BrandProfileController;
use App\Http\Controllers\AnalystDashboardController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;

// ─── Raíz ─────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect(match(auth()->user()->role) {
            'Admin'      => '/dashboard',
            'Technician' => '/technician/dashboard',
            'Analyst'    => '/analyst/dashboard',
            default      => '/dashboard',
        });
    }
    return redirect()->route('login');
});

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// ─── Autenticación (con rate limiting anti-fuerza-bruta) ──────────────────────
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')   // máx 5 intentos por minuto por IP
    ->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Registro Público (Técnicos) ──────────────────────────────────────────────
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:3,1')   // máx 3 intentos por minuto por IP
    ->name('register.post');

// ─── Admin: Dashboard + CRUD completo ─────────────────────────────────────────
Route::middleware(['auth', 'has.role:Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Equipos — escritura solo Admin
    Route::get('/equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    Route::get('/equipment/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
    Route::put('/equipment/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/equipment/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');

    // Órdenes de Trabajo — escritura y eliminación solo Admin
    Route::get('/maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
    Route::post('/maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
    Route::get('/maintenances/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('maintenances.edit');
    Route::put('/maintenances/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenances.update');
    Route::delete('/maintenances/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenances.destroy');

    // Inventario — gestión solo Admin
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // Usuarios — gestión solo Admin
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ─── Técnico: su propio panel ─────────────────────────────────────────────────
Route::middleware(['auth', 'has.role:Technician'])
    ->prefix('technician')
    ->name('technician.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'technicianDashboard'])->name('dashboard');
    });

// ─── Rutas comunes autenticadas (Admin + Técnico) ─────────────────────────────
Route::middleware(['auth'])->group(function () {
    // Equipos — lectura para todos los roles autenticados
    Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
    Route::get('/equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');

    // OTs — listado y detalle (el controller filtra por rol internamente)
    Route::get('/maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::get('/maintenances/{maintenance}', [MaintenanceController::class, 'show'])->name('maintenances.show');

    // Inventario — lectura para todos
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{inventory}', [InventoryController::class, 'show'])->name('inventory.show');

    // Consumo de stock: solo Admin y Técnico pueden descontar inventario
    Route::post('/inventory/{inventory}/consume', [InventoryController::class, 'consume'])
        ->middleware('has.role:Admin,Technician')
        ->name('inventory.consume');

    // OTs — acciones de técnico/admin (el controller valida ownership)
    Route::patch('/maintenances/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])
        ->name('maintenances.status');
    Route::patch('/maintenances/{maintenance}/notes', [MaintenanceController::class, 'updateNotes'])
        ->name('maintenances.notes');
    Route::post('/maintenances/{maintenance}/parts', [MaintenanceController::class, 'addPart'])
        ->name('maintenances.addPart');

    // PDF exportable
    Route::get('/maintenances/{maintenance}/pdf', [MaintenanceController::class, 'exportPdf'])
        ->name('maintenances.pdf');
});

// ─── Analista / Admin → Métricas ──────────────────────────────────────────────
Route::middleware(['auth', 'has.role:Admin,Analyst'])
    ->prefix('analyst')
    ->name('analyst.')
    ->group(function () {
        Route::get('/dashboard', [AnalystDashboardController::class, 'index'])->name('dashboard');
        Route::post('/kyc/{id}/approve', [AnalystDashboardController::class, 'approveKyc'])->name('kyc.approve');
        Route::post('/kyc/{id}/reject', [AnalystDashboardController::class, 'rejectKyc'])->name('kyc.reject');
        Route::get('/kyc/{id}/download-rut', [AnalystDashboardController::class, 'downloadRut'])->name('kyc.download_rut');
        Route::post('/run-ml', [AnalystDashboardController::class, 'runDataAnalysis'])->name('run');
    });

// ─── B2B2C Marketplace (público) ──────────────────────────────────────────────
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/directory', [CatalogController::class, 'directory'])->name('catalog.directory');
Route::get('/directory/{companyProfile}', [CatalogController::class, 'brandPage'])->name('catalog.brand');

Route::middleware(['buyer'])->group(function () {
    Route::post('/catalog/{product}/buy', [CatalogController::class, 'buy'])->name('catalog.buy');
    Route::post('/catalog/{product}/review', [CatalogController::class, 'review'])->name('catalog.review');
});

// ─── Seller ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'has.role:Seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/onboarding', [SellerOnboardingController::class, 'create'])->name('onboarding');
        Route::post('/onboarding', [SellerOnboardingController::class, 'store'])->name('onboarding.store');
        Route::get('/profile', [BrandProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [BrandProfileController::class, 'update'])->name('profile.update');
    });
