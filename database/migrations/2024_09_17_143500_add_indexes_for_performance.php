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
        // Add indexes to improve performance for summary queries
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->index(['material_id', 'transaction_id'], 'idx_material_transaction');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['type', 'project_id'], 'idx_type_project');
            $table->index(['cluster', 'delivery_note_no'], 'idx_cluster_dn');
        });

        Schema::table('boq_actuals', function (Blueprint $table) {
            $table->index(['material_id', 'project_id'], 'idx_material_project');
            $table->index(['cluster', 'dn_number'], 'idx_cluster_dn_boq');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->index(['sub_project_id', 'category_id'], 'idx_subproject_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndex('idx_material_transaction');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_type_project');
            $table->dropIndex('idx_cluster_dn');
        });

        Schema::table('boq_actuals', function (Blueprint $table) {
            $table->dropIndex('idx_material_project');
            $table->dropIndex('idx_cluster_dn_boq');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropIndex('idx_subproject_category');
        });
    }
};