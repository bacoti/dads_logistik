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
        Schema::table('po_materials', function (Blueprint $table) {
            // Update status enum to include 'pending' and other necessary statuses
            $table->dropColumn('status');
        });

        Schema::table('po_materials', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'completed', 'cancelled'])
                  ->default('pending')
                  ->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_materials', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('po_materials', function (Blueprint $table) {
            $table->enum('status', ['active', 'completed', 'cancelled'])
                  ->default('active')
                  ->after('unit');
        });
    }
};
