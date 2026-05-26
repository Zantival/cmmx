@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">{{ __('Analyst Dashboard') }}</h2>
            <div class="card shadow-sm border-0 border-top border-4 border-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold">{{ __('Global Quality Metrics') }}</h6>
                        <h3 class="mb-0">{{ number_format($averageReviews, 2) }} / 5.0 {{ __('Average Rating') }}</h3>
                    </div>
                    <div>
                        <form action="{{ route('analyst.run') }}" method="POST">
                            @csrf
                            <button class="btn btn-warning shadow-sm fw-bold">
                                <i class="bi bi-cpu"></i> {{ __('Run Machine Learning Models') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            @if(session('ml_result'))
                @php $res = json_decode(session('ml_result'), true); @endphp
                <div class="alert {{ isset($res['error']) ? 'alert-danger' : 'alert-info' }} mt-4 shadow-sm border-0 border-start border-4 {{ isset($res['error']) ? 'border-danger' : 'border-info' }}">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi {{ isset($res['error']) ? 'bi-exclamation-triangle-fill' : 'bi-robot' }} fs-4"></i>
                        <h5 class="mb-0 fw-bold">
                            {{ isset($res['error']) ? __('Engine Error') : __('Intelligence Report') }}
                        </h5>
                    </div>
                    
                    @if(isset($res['error']))
                        <p class="mb-1"><strong>{{ __('Issue') }}:</strong> {{ __($res['error']) }}</p>
                        @if(isset($res['details']))
                            <small class="text-muted d-block mb-2">{{ __('Details') }}: {{ $res['details'] }}</small>
                        @endif
                        <p class="mb-0"><i class="bi bi-info-circle me-1"></i><strong>{{ __('Recommendation') }}:</strong> {{ __($res['recommendation'] ?? 'Contact support') }}</p>
                    @else
                        <div class="row g-3">
                            <div class="col-md-6 border-end">
                                <p class="mb-1 text-muted small text-uppercase fw-bold">{{ __('Recommendation') }}</p>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $res['recommendation'] === 'APPROVE' ? 'bg-success' : 'bg-warning' }} px-3 py-2 fs-6">
                                        {{ $res['recommendation'] }}
                                    </span>
                                </div>
                                <p class="mt-2 mb-0 small">Health Score: <strong>{{ round($res['health_score'] * 100) }}%</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small text-uppercase fw-bold">{{ __('Analyzed Features') }}</p>
                                <ul class="list-unstyled mb-0 small">
                                    <li><i class="bi bi-star me-2"></i>{{ __('Avg. Rating') }}: {{ $res['input_features']['rating_avg'] }}</li>
                                    <li><i class="bi bi-graph-up me-2"></i>{{ __('Volume') }}: {{ $res['input_features']['sales_volume'] }}</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h4 class="mb-3 fw-bold">{{ __('Pending KYC Approvals') }}</h4>
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">{{ __('Company') }}</th>
                                <th>{{ __('User ID') }}</th>
                                <th>{{ __('RUT Document') }}</th>
                                <th class="pe-4 text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingKyc as $kyc)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-navy">{{ $kyc->company_name }}</div>
                                    <div class="small text-muted">{{ $kyc->phone }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $kyc->user_id }}</span></td>
                                <td>
                                    <a href="{{ route('analyst.kyc.download_rut', $kyc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>{{ __('Download RUT') }}
                                    </a>
                                </td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-sm btn-ghost me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#kycModal{{ $kyc->id }}">
                                        <i class="bi bi-eye"></i> {{ __('Detail') }}
                                    </button>
                                    <form action="{{ route('analyst.kyc.approve', $kyc->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success px-3">{{ __('Approve') }}</button>
                                    </form>
                                    <form action="{{ route('analyst.kyc.reject', $kyc->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger px-3">{{ __('Reject') }}</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- KYC Detail Modal -->
                            <div class="modal fade" id="kycModal{{ $kyc->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom-0 p-4">
                                            <h5 class="modal-title fw-bold text-navy">{{ __('Company Profile Detail') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 pt-0">
                                            <div class="mb-4 text-center">
                                                <div class="nav-user-avatar mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.5rem;">
                                                    {{ strtoupper(substr($kyc->company_name, 0, 1)) }}
                                                </div>
                                                <h4 class="fw-bold mb-1">{{ $kyc->company_name }}</h4>
                                                <span class="badge-status badge-prev">{{ __('Pending Approval') }}</span>
                                            </div>

                                            <div class="row g-3 mb-4">
                                                <div class="col-6">
                                                    <label class="form-label mb-0 small text-muted text-uppercase">{{ __('Phone') }}</label>
                                                    <p class="fw-semibold mb-0">{{ $kyc->phone }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label mb-0 small text-muted text-uppercase">{{ __('Products Volume') }}</label>
                                                    <p class="fw-semibold mb-0">{{ $kyc->user->products_count ?? 0 }} {{ __('Items') }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label mb-0 small text-muted text-uppercase">{{ __('Corporate Description') }}</label>
                                                <div class="bg-light p-3 rounded-3 mt-2 small text-secondary" style="min-height: 80px;">
                                                    {{ $kyc->corporate_info ?: __('No description provided.') }}
                                                </div>
                                            </div>

                                            <div class="d-grid">
                                                <a href="{{ route('analyst.kyc.download_rut', $kyc->id) }}" class="btn btn-navy py-2">
                                                    <i class="bi bi-download me-2"></i>{{ __('Download RUT Document') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    {{ __('No pending KYCs.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
