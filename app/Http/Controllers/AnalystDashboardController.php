<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Services\MachineLearningService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalystDashboardController extends Controller
{
    public function index()
    {
        // Load profiles with their user and the count of products that user has uploaded
        $pendingKyc = CompanyProfile::with(['user' => function($query) {
            $query->withCount('products');
        }])->where('is_kyc_approved', false)->get();

        // Metric stub
        $averageReviews = \App\Models\Review::avg('rating') ?? 0;

        return view('analyst.dashboard', compact('pendingKyc', 'averageReviews'));
    }

    public function approveKyc(Request $request, $id)
    {
        $profile = CompanyProfile::findOrFail($id);
        $profile->update(['is_kyc_approved' => true]);
        
        return back()->with('success', 'Company KYC Approved.');
    }

    public function rejectKyc(Request $request, $id)
    {
        $profile = CompanyProfile::findOrFail($id);
        // Maybe soft delete or keep state
        return back()->with('success', 'Company KYC Rejected.');
    }

    public function runDataAnalysis(Request $request, MachineLearningService $mlService)
    {
        // Aggregate some real metrics from the database
        $data = [
            'average_rating' => \App\Models\Review::avg('rating') ?? 4.0,
            'sales_volume'   => \App\Models\Product::where('is_active', true)->count(),
            'pending_kycs'   => CompanyProfile::where('is_kyc_approved', false)->count(),
        ];

        $result = $mlService->analyzeCompanyHealth($data);
        
        return back()->with('ml_result', json_encode($result, JSON_PRETTY_PRINT));
    }

    public function downloadRut($id)
    {
        $profile = CompanyProfile::findOrFail($id);
        
        // Ensure the path exists in private storage
        if (!Storage::disk('local')->exists($profile->rut_path)) {
            // Fallback or attempt to find in 'private' subfolder if structured that way
            // Based on seeder it's just 'rut_X.pdf'
            return back()->with('error', 'Document not found.');
        }

        return Storage::disk('local')->download($profile->rut_path);
    }
}
