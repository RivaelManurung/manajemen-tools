<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $fillable = ['name'];

    // Anda juga bisa menambahkan relasi ke User (opsional tapi bagus)
    public function users()
    {
        // Ganti Department::class dengan JobTitle::class di file JobTitle.php
        return $this->hasMany(User::class);
    }
}
