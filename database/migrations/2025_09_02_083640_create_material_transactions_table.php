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
        Schema::create('material_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_material_id')->constrained()->onDelete('cascade');
            $table->foreignId('po_material_item_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('transaction_type', [
                'receipt',     // Material masuk dari supplier
                'usage',       // Material dipakai untuk project
                'return',      // Material dikembalikan ke stock
                'damage',      // Material rusak/hilang
                'adjustment'   // Koreksi stock manual
            ]);
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50);
            $table->date('transaction_date');
            $table->foreignId('user_id')->constrained()->comment('User yang melakukan transaksi');
            $table->foreignId('project_id')->nullable()->constrained()->comment('Project tujuan (untuk usage)');
            $table->string('activity_name')->nullable()->comment('Nama kegiatan/aktivitas');
            $table->string('pic_name')->nullable()->comment('Person In Charge');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable()->comment('Foto/dokumen pendukung');
            $table->enum('condition', ['good', 'damaged', 'expired'])->default('good');
            $table->decimal('unit_price', 12, 2)->nullable()->comment('Harga per unit untuk perhitungan cost');
            $table->string('location')->nullable()->comment('Lokasi penyimpanan/penggunaan');
            $table->timestamps();

            // Indexes untuk performance
            $table->index(['po_material_id', 'transaction_type']);
            $table->index(['transaction_date']);
            $table->index(['project_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_transactions');
    }
};
