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
        Schema::create('po_material_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_material_id')->constrained('po_materials')->onDelete('cascade');
            $table->text('description'); // Nama material
            $table->decimal('quantity', 10, 2); // Kuantitas
            $table->string('unit', 50); // Satuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_material_items');
    }
};
