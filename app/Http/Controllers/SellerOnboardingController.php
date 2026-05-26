<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\CompanyProfile;

class SellerOnboardingController extends Controller
{
    public function create()
    {
        return view('seller.onboarding');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'rut' => 'required|file|mimes:pdf|max:2048',
        ]);

        $rutPath = null;
        if ($request->hasFile('rut')) {
            $file = $request->file('rut');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $rutPath = $file->storeAs('ruts', $filename, 'private');
        }

        CompanyProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'company_name' => $request->company_name,
                'phone' => $request->phone,
                'rut_path' => $rutPath,
                'is_kyc_approved' => false,
            ]
        );

        return redirect()->route('seller.profile.edit')->with('success', 'Onboarding completed. Awaiting KYC approval.');
    }
}
