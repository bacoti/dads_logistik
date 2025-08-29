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
        Schema::create('po_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User PO yang input
            $table->string('po_number'); // No PO
            $table->string('supplier'); // Supplier
            $table->date('release_date'); // Tanggal rilis
            $table->string('location'); // Lokasi
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Nama project
            $table->foreignId('sub_project_id')->nullable()->constrained()->onDelete('cascade'); // Sub project
            $table->text('description'); // Keterangan (nama material)
            $table->decimal('quantity', 15, 2); // Qty
            $table->string('unit'); // Satuan
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active'); // Status PO
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_materials');
    }
};
