<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('category')->nullable()->after('code');
            $table->string('serial_number')->nullable()->after('category');
            $table->enum('criticality', ['Critical', 'High', 'Medium', 'Low'])->default('Medium')->after('serial_number');
            $table->date('next_maintenance_date')->nullable()->after('installation_date');
            $table->date('warranty_expiry')->nullable()->after('next_maintenance_date');
            $table->text('notes')->nullable()->after('warranty_expiry');
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['category', 'serial_number', 'criticality', 'next_maintenance_date', 'warranty_expiry', 'notes']);
        });
    }
};
