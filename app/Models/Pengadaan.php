<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pengadaan',
        'id_rup',
        'unit_kerja',
        'tahun_anggaran',
        'metode_pengadaan',
        'status',
        'pagu_anggaran',
        'hps',
        'nilai_kontrak',
        'rekanan',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'dokumen_rup',
        'dokumen_hps',
        'dokumen_kontrak',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}