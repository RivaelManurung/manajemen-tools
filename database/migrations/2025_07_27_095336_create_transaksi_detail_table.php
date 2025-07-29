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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            // PERBAIKAN: Foreign key sekarang akan berhasil
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->foreignId('peralatan_id')->constrained('peralatan');
            $table->unsignedInteger('jumlah');
            $table->enum('kondisi', ['sangat baik', 'baik', 'rusak']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
