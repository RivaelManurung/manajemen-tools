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
            Schema::create('transaksis', function (Blueprint $table) {
        $table->id();
        $table->string('kode_transaksi')->unique();
        $table->enum('tipe', ['peminjaman', 'pengembalian']);
        $table->foreignId('user_id')->constrained('users');
        // PERBAIKAN: Nama kolom diubah menjadi 'storeman_id'
        $table->foreignId('storeman_id')->nullable()->constrained('users');
        $table->timestamp('tanggal_transaksi');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
