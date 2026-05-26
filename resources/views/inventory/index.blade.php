@extends('layouts.app')
@section('title', __('Inventory'))

@push('styles')
<style nonce="{{ $cspNonce }}">
/* ─── Inventory Grid ─── */
.inventory-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.25rem;
}

@media(max-width:575.98px) {
    .inventory-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
}

.product-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(56,189,248,0.35);
}

.product-img-wrap {
    height: 160px;
    background: #f8fafc;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    border-bottom: 1px solid var(--border);
    overflow: hidden;
}
@media(max-width:575.98px){
    .product-img-wrap { height: 110px; padding: 0.75rem; }
}
.product-img {
    max-width: 100%; max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
    z-index: 1;
}
.product-card:hover .product-img { transform: scale(1.06); }

.stock-badge {
    position: absolute;
    top: 9px; right: 9px;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(4px);
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 700;
    box-shadow: 0 2px 5px rgba(0,0,0,0.07);
    z-index: 2;
}
.stock-badge.in-stock    { color: #10B981; border:1px solid #10B981; }
.stock-badge.low-stock   { color: #F59E0B; border:1px solid #F59E0B; }
.stock-badge.out-of-stock{ color: #EF4444; border:1px solid #EF4444; }

.product-details {
    padding: 0.875rem 1rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
@media(max-width:575.98px){
    .product-details { padding: 0.625rem 0.75rem; }
}

.product-cat {
    font-size: 0.65rem;
    color: var(--accent);
    text-transform: uppercase;
    font-weight: 800;
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
}
.product-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.3;
    margin-bottom: 0.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
@media(max-width:575.98px){
    .product-title { font-size: 0.82rem; }
}
.product-sku {
    font-family: monospace;
    font-size: 0.7rem;
    color: var(--text-muted);
    background: var(--surface);
    padding: 2px 5px;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 0.875rem;
}
.product-action {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px dashed var(--border);
}
.btn-consume {
    background: var(--success);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    padding: 7px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex; align-items: center; gap: 5px;
    transition: all 0.2s;
    cursor: pointer;
}
.btn-consume:hover:not(:disabled) {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(16,185,129,0.3);
}
.btn-consume:disabled {
    background: #CBD5E1; cursor: not-allowed; color: #64748B;
}
@media(max-width:575.98px){
    .btn-consume { padding: 6px 10px; font-size: 0.73rem; }
    .btn-consume .consume-text { display: none; }
}

/* ─── Category Tabs ─── */
.category-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-muted);
    background: var(--surface);
    border: 1.5px solid var(--border);
    text-decoration: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}
.category-tab:hover {
    border-color: var(--accent);
    color: var(--accent);
    text-decoration: none;
    background: rgba(56, 189, 248, 0.06);
}
.category-tab.active {
    background: var(--navy);
    color: #fff;
    border-color: var(--navy);
    box-shadow: 0 2px 12px rgba(10, 25, 47, 0.2);
}
.category-tab i { font-size: 0.82rem; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1><i class="bi bi-box-seam-fill me-2" style="color:var(--accent);"></i>{{ __('Gestión de Inventario') }}</h1>
        <div class="page-breadcrumb">{{ __('Consulte el stock y registre el consumo de repuestos.') }}</div>
    </div>
    <div class="page-actions">
        <form id="searchForm" action="{{ route('inventory.index') }}" method="GET" class="d-flex" style="min-width:0;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-color:var(--border);"><i class="bi bi-search text-muted" id="searchIcon"></i></span>
                <input type="text" id="searchInput" name="search"
                       class="form-control border-start-0"
                       style="border-color:var(--border); min-width:140px;"
                       placeholder="{{ __('Buscar...') }}"
                       value="{{ request('search') }}">
            </div>
        </form>
        @if(auth()->user()->role === 'Admin')
        <a href="{{ route('inventory.create') }}" class="btn-navy btn">
            <i class="bi bi-plus-lg"></i>
            <span class="d-none d-sm-inline">{{ __('Añadir') }}</span>
        </a>
        @endif
    </div>
</div>

<div class="content-area pt-4" id="resultsArea">

    @if(session('success') && !request()->has('checkout_complete'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4 animate-in">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
    </div>
    @endif

    @if(request()->has('checkout_complete'))
    <div class="alert d-flex align-items-center gap-3 mb-4 animate-in" style="background:#10B981; color:#fff; border-radius:var(--radius-md); padding:1.25rem 1.5rem;">
        <i class="bi bi-bag-check-fill" style="font-size:1.75rem;"></i>
        <div>
            <strong>{{ __('¡Consumo exitoso!') }}</strong><br>
            <span style="font-size:0.875rem; opacity:0.9;">{{ __('Las partes han sido descontadas del inventario.') }}</span>
        </div>
    </div>
    @endif

    {{-- Category Filter Tabs --}}
    @php
        $categories = ['all' => __('Todos'), 'Spare Part' => __('Spare Part'), 'Consumable' => __('Consumable'), 'Tool' => __('Tool')];
        $activeCategory = request('category', 'all');
    @endphp
    <div class="d-flex gap-2 flex-wrap mb-4 animate-in">
        @foreach($categories as $catKey => $catLabel)
        <a href="{{ route('inventory.index', array_merge(request()->except('category', 'page'), $catKey !== 'all' ? ['category' => $catKey] : [])) }}"
           class="category-tab {{ $activeCategory === $catKey ? 'active' : '' }}">
            @if($catKey === 'all') <i class="bi bi-grid-3x3-gap-fill"></i>
            @elseif($catKey === 'Spare Part') <i class="bi bi-gear-wide-connected"></i>
            @elseif($catKey === 'Consumable') <i class="bi bi-droplet-fill"></i>
            @elseif($catKey === 'Tool') <i class="bi bi-wrench-adjustable"></i>
            @endif
            {{ $catLabel }}
        </a>
        @endforeach
    </div>

    <div class="inventory-grid animate-in delay-1">
        @forelse($items as $item)
        @php
            $images = ['motor.png', 'bearing.png', 'lubricant.png'];
            $imgSrc = asset('img/parts/' . $images[$item->id % count($images)]);
            $status = $item->stock_status;
            $statusCls = match($status) {
                'In Stock', 'Disponible' => 'in-stock',
                'Low Stock', 'Stock Bajo' => 'low-stock',
                'Out of Stock', 'Agotado' => 'out-of-stock',
                default => 'in-stock'
            };
        @endphp
        <div class="product-card">
            {{-- Admin actions --}}
            @if(auth()->user()->role === 'Admin')
            <div style="position:absolute; top:9px; left:9px; z-index:3;" class="d-flex gap-1">
                <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-light shadow-sm" style="width:28px;height:28px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                    <i class="bi bi-pencil" style="font-size:0.72rem;color:var(--info);"></i>
                </a>
                <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('{{ __('¿Seguro que desea eliminar?') }}');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light shadow-sm" style="width:28px;height:28px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                        <i class="bi bi-trash" style="font-size:0.72rem;color:var(--danger);"></i>
                    </button>
                </form>
            </div>
            @endif

            <div class="stock-badge {{ $statusCls }}">{{ __($status) }}</div>

            <div class="product-img-wrap">
                <img src="{{ $imgSrc }}" alt="{{ $item->name }}" class="product-img">
            </div>

            <div class="product-details">
                <div class="product-cat">{{ __($item->category) }}</div>
                <div class="product-title" title="{{ $item->name }}">{{ $item->name }}</div>
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="product-sku"><i class="bi bi-upc-scan me-1"></i>{{ $item->sku ?: __('Sin SKU') }}</div>
                    <div style="font-weight:800;color:var(--navy);font-size:0.95rem;">
                        <span style="font-size:0.72rem;color:var(--text-muted);font-weight:400;">{{ __('Stock:') }}</span> {{ $item->stock }}
                    </div>
                </div>

                <div class="product-action">
                    <div>
                        @if(in_array($status, ['Low Stock', 'Stock Bajo']))
                        <span style="font-size:0.72rem;color:var(--warning);font-weight:600;"><i class="bi bi-exclamation-triangle-fill"></i> {{ __('Bajo') }}</span>
                        @endif
                    </div>
                    <button class="btn-consume"
                            onclick="promptConsume({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->stock }})"
                            {{ $item->stock <= 0 ? 'disabled' : '' }}>
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="consume-text">{{ __('Registrar Uso') }}</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1;" class="text-center py-5 animate-in">
            <i class="bi bi-box-seam" style="font-size:4rem;color:var(--text-light);opacity:0.4;display:block;margin-bottom:1rem;"></i>
            <h4 class="text-muted fw-bold">{{ __('Almacén vacío') }}</h4>
            <p class="text-muted">{{ __('No se encontraron productos en el inventario.') }}</p>
            @if(auth()->user()->role === 'Admin')
            <a href="{{ route('inventory.create') }}" class="btn-navy btn">
                <i class="bi bi-plus me-1"></i>{{ __('Añadir artículo') }}
            </a>
            @endif
        </div>
        @endforelse
    </div>

    @if($items->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Consume Modal --}}
<div class="modal fade" id="consumeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius:var(--radius-lg);">
            <form id="consumeForm" method="POST">
                @csrf
                <div class="modal-header text-white pb-3" style="background:var(--navy); border-bottom:none; border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                    <h5 class="modal-title w-100 text-center fw-bold" style="font-size:1rem;">{{ __('Registrar Uso') }}</h5>
                    <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <h6 id="consumeItemName" class="mb-1 fw-bold" style="font-size:1rem;line-height:1.3;color:var(--navy);"></h6>
                    <div class="mb-4 text-muted" id="consumeItemStock" style="font-size:0.82rem;"></div>

                    <label class="form-label d-block mb-2">{{ __('Cantidad a usar') }}</label>
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        <button type="button" onclick="adjustQty(-1)" class="btn btn-ghost" style="width:40px;height:40px;padding:0;border-radius:10px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">−</button>
                        <input type="number" name="quantity" id="consumeQty"
                               class="form-control text-center fs-4"
                               value="1" min="1" required
                               style="max-width:90px;border:2px solid var(--accent);color:var(--navy);font-weight:800;border-radius:10px;">
                        <button type="button" onclick="adjustQty(1)" class="btn btn-ghost" style="width:40px;height:40px;padding:0;border-radius:10px;font-size:1.2rem;display:flex;align-items:center;justify-content:center;">+</button>
                    </div>
                </div>
                <div class="modal-footer justify-content-center p-3 border-top-0 pt-0">
                    <button type="submit" class="btn w-100 fw-bold text-white" style="border-radius:var(--radius-md);padding:12px;background:#10B981;border:none;font-size:0.95rem;">
                        <i class="bi bi-check2-circle me-2" style="font-size:1.1rem;"></i>{{ __('Confirmar Uso') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce }}">
    function adjustQty(d) {
        const inp = document.getElementById('consumeQty');
        const newVal = parseInt(inp.value) + d;
        const max = parseInt(inp.max) || 9999;
        if (newVal >= 1 && newVal <= max) inp.value = newVal;
    }

    function promptConsume(id, name, maxStock) {
        document.getElementById('consumeForm').action = '/inventory/' + id + '/consume';
        document.getElementById('consumeItemName').textContent = name;
        document.getElementById('consumeItemStock').innerHTML =
            `{{ __('Disponible:') }} <strong class="text-success">${maxStock}</strong> {{ __('unidades') }}`;
        const qty = document.getElementById('consumeQty');
        qty.max = maxStock;
        qty.value = 1;
        new bootstrap.Modal(document.getElementById('consumeModal')).show();
    }

    // Live search
    let searchTimer;
    const input = document.getElementById('searchInput');
    const form  = document.getElementById('searchForm');
    const icon  = document.getElementById('searchIcon');
    const area  = document.getElementById('resultsArea');

    if (input) {
        input.addEventListener('input', function() {
            clearTimeout(searchTimer);
            icon.className = 'spinner-border spinner-border-sm text-muted';
            searchTimer = setTimeout(() => {
                const url = new URL(form.action);
                url.searchParams.set('search', this.value);
                fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newArea = doc.getElementById('resultsArea');
                        if (newArea) area.innerHTML = newArea.innerHTML;
                        icon.className = 'bi bi-search text-muted';
                    })
                    .catch(() => { icon.className = 'bi bi-search text-muted'; });
            }, 400);
        });
    }
</script>
@endpush
@endsection
