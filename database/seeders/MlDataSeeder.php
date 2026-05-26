<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MlDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Analyst User
        User::updateOrCreate(['email' => 'analyst@cmms.com'], [
            'name' => 'Data Analyst',
            'password' => Hash::make('password'),
            'role' => 'Analyst',
        ]);

        // 2. Create some Sellers and Profiles
        $sellers = [
            ['name' => 'Industrial Supplies Corp', 'email' => 'seller1@market.com'],
            ['name' => 'Tech Tools Ltd', 'email' => 'seller2@market.com'],
        ];

        foreach ($sellers as $s) {
            $user = User::updateOrCreate(['email' => $s['email']], [
                'name' => $s['name'],
                'password' => Hash::make('password'),
                'role' => 'Seller',
            ]);

            CompanyProfile::updateOrCreate(['user_id' => $user->id], [
                'company_name' => $s['name'],
                'phone' => '555-0199',
                'rut_path' => 'rut_' . $user->id . '.pdf',
                'corporate_info' => 'Leading industrial provider.',
                'is_kyc_approved' => false, // Leave some unapproved for the dashboard
            ]);

            // Create some products for volume
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'user_id' => $user->id,
                    'name' => "Tool {$i} for " . $s['name'],
                    'description' => 'High quality industrial tool.',
                    'price' => rand(100, 5000),
                    'stock' => rand(10, 100),
                    'is_active' => true,
                ]);
            }
        }

        // 3. Create a Buyer and some Reviews
        $buyer = User::updateOrCreate(['email' => 'buyer@market.com'], [
            'name' => 'Corporate Buyer',
            'password' => Hash::make('password'),
            'role' => 'Technician', // Using Technician as a proxy for buyer if role not exists
        ]);

        $products = Product::all();
        foreach ($products as $p) {
            Review::create([
                'user_id' => $buyer->id,
                'product_id' => $p->id,
                'rating' => rand(3, 5),
                'comment' => 'Great product, arrived on time.',
            ]);
        }
    }
}
