<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storemen', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique(); // Hanya berisi nama
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storemen');
    }
};