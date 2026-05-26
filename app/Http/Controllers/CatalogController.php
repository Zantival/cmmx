<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CompanyProfile;
use App\Models\Review;

class CatalogController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->with('seller.companyProfile')->paginate(12);
        return view('catalog.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['seller.companyProfile', 'reviews.reviewer']);
        return view('catalog.show', compact('product'));
    }

    public function directory()
    {
        $companies = CompanyProfile::where('is_kyc_approved', true)->paginate(12);
        return view('catalog.directory', compact('companies'));
    }

    public function brandPage(CompanyProfile $companyProfile)
    {
        if (!$companyProfile->is_kyc_approved) {
            abort(404);
        }
        $products = Product::where('user_id', $companyProfile->user_id)->where('is_active', true)->paginate(12);
        return view('catalog.brand', compact('companyProfile', 'products'));
    }

    public function buy(Request $request, Product $product)
    {
        // Stub for purchasing/adding to cart logic protected by 'buyer' Auth Gate
        return back()->with('success', 'Product added to cart / purchased successfully.');
    }

    public function review(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // XSS sanitization using strip_tags
        $safeComment = strip_tags($request->comment);

        Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $safeComment,
        ]);

        return back()->with('success', 'Review added successfully.');
    }
}
