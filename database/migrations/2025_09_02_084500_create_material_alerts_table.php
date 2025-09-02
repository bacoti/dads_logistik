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
        Schema::create('material_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_stock_id')->constrained()->onDelete('cascade');
            $table->enum('alert_type', [
                'low_stock',        // Stock menipis
                'out_of_stock',     // Stock habis
                'overstocked',      // Stock berlebihan
                'expired_soon',     // Material akan expired
                'expired',          // Material sudah expired
                'damaged'          // Material rusak
            ]);
            $table->string('alert_title');
            $table->text('alert_message');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active');
            $table->timestamp('triggered_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users');
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->text('notes')->nullable()->comment('Catatan tindakan yang diambil');
            $table->json('alert_data')->nullable()->comment('Data tambahan untuk alert');
            $table->timestamps();

            // Indexes
            $table->index(['material_stock_id', 'status']);
            $table->index(['alert_type']);
            $table->index(['severity']);
            $table->index(['status']);
            $table->index(['triggered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_alerts');
    }
};
