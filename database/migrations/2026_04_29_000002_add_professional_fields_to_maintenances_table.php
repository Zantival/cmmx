<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->enum('priority', ['Critical', 'High', 'Normal', 'Low'])->default('Normal')->after('status');
            $table->decimal('estimated_hours', 5, 1)->nullable()->after('priority');
            $table->decimal('actual_hours', 5, 1)->nullable()->after('estimated_hours');
            $table->timestamp('completion_date')->nullable()->after('actual_hours');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['priority', 'estimated_hours', 'actual_hours', 'completion_date']);
        });
    }
};
