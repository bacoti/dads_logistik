<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama material (Kabel, Tiang 7m, dll)
            $table->string('type'); // Jenis (Kabel, Tiang, dll)
            $table->integer('stock')->default(0); // Stok terkini
            $table->string('unit'); // Satuan (meter, buah, dll)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
