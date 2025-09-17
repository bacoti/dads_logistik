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
        Schema::create('boq_actuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Admin yang input
            $table->foreignId('project_id')->constrained(); // Project utama
            $table->foreignId('sub_project_id')->constrained(); // Sub project
            $table->foreignId('material_id')->constrained(); // Material yang digunakan
            $table->string('cluster'); // Cluster/area proyek
            $table->string('dn_number'); // Nomor DN (Delivery Note)
            $table->decimal('actual_quantity', 15, 2); // Quantity material yang benar-benar terpakai
            $table->date('usage_date'); // Tanggal pemakaian
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();

            // Indexes for better performance
            $table->index(['project_id', 'sub_project_id', 'cluster']);
            $table->index(['material_id', 'usage_date']);
            $table->index('dn_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq_actuals');
    }
};
