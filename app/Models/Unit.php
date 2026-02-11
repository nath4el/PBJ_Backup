<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengadaan;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';

    protected $fillable = [
        'nama',
    ];

    /**
     * ✅ Satu Unit punya banyak Pengadaan.
     * FK: pengadaans.unit_id -> units.id
     */
    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class, 'unit_id', 'id');
    }
}
