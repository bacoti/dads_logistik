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
        Schema::table('boq_actuals', function (Blueprint $table) {
            $table->decimal('actual_usage', 15, 2)->default(0)->after('actual_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boq_actuals', function (Blueprint $table) {
            $table->dropColumn('actual_usage');
        });
    }
};
