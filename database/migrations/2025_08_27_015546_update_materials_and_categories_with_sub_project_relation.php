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
        // Add sub_project_id to categories table if it doesn't exist
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'sub_project_id')) {
                $table->unsignedBigInteger('sub_project_id')->nullable()->after('name');
                $table->foreign('sub_project_id')->references('id')->on('sub_projects')->onDelete('cascade');
                $table->index(['sub_project_id', 'name']);
            }
        });

        // Add sub_project_id to materials table if it doesn't exist
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'sub_project_id')) {
                $table->unsignedBigInteger('sub_project_id')->nullable()->after('category_id');
                $table->foreign('sub_project_id')->references('id')->on('sub_projects')->onDelete('cascade');
                $table->index(['sub_project_id', 'category_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys and columns from materials table if they exist
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'sub_project_id')) {
                $table->dropForeign(['sub_project_id']);
                $table->dropIndex(['sub_project_id', 'category_id']);
                $table->dropColumn('sub_project_id');
            }
        });

        // Drop foreign keys and columns from categories table if they exist
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'sub_project_id')) {
                $table->dropForeign(['sub_project_id']);
                $table->dropIndex(['sub_project_id', 'name']);
                $table->dropColumn('sub_project_id');
            }
        });
    }
};
