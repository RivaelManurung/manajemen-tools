<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peralatan_id')->constrained('peralatan');
            $table->foreignId('mekanik_id')->constrained('users');
            $table->foreignId('storeman_id')->constrained('users');
            $table->timestamp('waktu_pinjam')->nullable();
            $table->timestamp('waktu_kembali')->nullable();
            $table->enum('kondisi', ['sangat baik', 'baik', 'rusak'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_peminjaman');
    }
};