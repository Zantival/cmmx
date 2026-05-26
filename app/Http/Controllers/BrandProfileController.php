<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->companyProfile;
        
        if (!$profile) {
            return redirect()->route('seller.onboarding');
        }

        return view('seller.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = $request->user()->companyProfile;

        if (!$profile) {
            return redirect()->route('seller.onboarding');
        }

        $request->validate([
            'corporate_info' => 'nullable|string',
            'logo' => 'nullable|image|max:1024',
            'banner' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $profile->logo = $request->file('logo')->store('brand/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($profile->banner) {
                Storage::disk('public')->delete($profile->banner);
            }
            $profile->banner = $request->file('banner')->store('brand/banners', 'public');
        }

        $profile->corporate_info = $request->corporate_info;
        $profile->save();

        return back()->with('success', 'Brand page updated successfully.');
    }
}
