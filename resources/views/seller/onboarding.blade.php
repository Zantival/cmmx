@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Seller Onboarding (KYC)</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.onboarding.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">RUT (PDF Only - max 2MB)</label>
                            <input type="file" name="rut" class="form-control" accept="application/pdf" required>
                            <small class="text-muted">OWASP File Upload Protected.</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Complete Onboarding</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
