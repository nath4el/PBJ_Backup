<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsips'; // ganti kalau nama tabel kamu beda

    // (opsional) kalau mau mass assignment:
    protected $guarded = [];
}
