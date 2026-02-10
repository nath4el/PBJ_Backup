<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    /**
     * Satu Unit punya banyak Pengadaan.
     */
    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class);
    }
}
