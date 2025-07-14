<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    // Menentukan nama tabel karena tidak mengikuti standar penamaan jamak Laravel
    protected $table = 'peralatan';

    // Kolom yang boleh diisi secara massal
    protected $fillable = ['nama', 'kode', 'status'];
}
