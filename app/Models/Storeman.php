<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storeman extends Model
{
    protected $fillable = ['nama'];

    /**
     * Mendefinisikan relasi ke User.
     * Setiap Storeman dapat memiliki banyak User.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
