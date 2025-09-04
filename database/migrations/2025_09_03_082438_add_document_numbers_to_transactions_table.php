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
        Schema::table('transactions', function (Blueprint $table) {
            // Field untuk transaksi penerimaan (wajib)
            $table->string('delivery_order_no')->nullable()->comment('No. DO (Delivery Order) - Required for penerimaan');
            $table->string('delivery_note_no')->nullable()->comment('No. DN (Delivery Note) - Required for penerimaan');
            
            // Field untuk transaksi pengembalian (opsional)
            $table->string('delivery_return_no')->nullable()->comment('No. DR (Delivery Return) - Optional for pengembalian');
            
            // Field untuk tujuan pengembalian (mengganti fungsi vendor untuk pengembalian)
            $table->string('return_destination')->nullable()->comment('Tujuan Pengembalian - for pengembalian type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_order_no',
                'delivery_note_no', 
                'delivery_return_no',
                'return_destination'
            ]);
        });
    }
};
