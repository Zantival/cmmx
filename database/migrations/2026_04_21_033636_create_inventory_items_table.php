<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $row) {
            $row->id();
            $row->string('name');
            $row->string('sku')->unique()->nullable();
            $row->text('description')->nullable();
            $row->integer('stock')->default(0);
            $row->decimal('unit_price', 12, 2)->default(0.00);
            $row->string('category')->default('General');
            $row->integer('min_stock')->default(5); // For low stock alerts
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
