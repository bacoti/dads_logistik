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
        Schema::create('material_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_material_item_id')->constrained()->onDelete('cascade');
            $table->string('material_name')->comment('Nama material untuk kemudahan query');
            $table->string('material_category')->nullable()->comment('Kategori material');
            $table->decimal('current_stock', 10, 2)->default(0)->comment('Stock saat ini');
            $table->string('unit', 50);
            $table->decimal('minimum_stock', 10, 2)->default(0)->comment('Minimum stock untuk alert');
            $table->decimal('maximum_stock', 10, 2)->nullable()->comment('Maximum stock capacity');
            $table->decimal('reserved_stock', 10, 2)->default(0)->comment('Stock yang sudah di-reserve');
            $table->decimal('available_stock', 10, 2)->default(0)->comment('Stock yang bisa digunakan');
            $table->string('storage_location')->nullable()->comment('Lokasi penyimpanan');
            $table->decimal('average_unit_cost', 12, 2)->nullable()->comment('Average cost per unit');
            $table->decimal('total_value', 12, 2)->nullable()->comment('Total nilai stock');
            $table->date('last_transaction_date')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique('po_material_item_id');
            $table->index(['material_name']);
            $table->index(['current_stock']);
            $table->index(['minimum_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_stocks');
    }
};
