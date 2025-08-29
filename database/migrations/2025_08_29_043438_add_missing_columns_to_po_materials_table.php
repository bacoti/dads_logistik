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
            // Tambah kolom admin_notes (terpisah dari notes user)
            $table->text('admin_notes')->nullable()->after('notes');
            
            // Tambah kolom approved_by dan approved_at
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('admin_notes');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_materials', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['admin_notes', 'approved_by', 'approved_at']);
        });
    }
};
