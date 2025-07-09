<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPenduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kepala_keluarga',
        'nama',
        'rt',
        'jenis_dokumen',
        'gender',
        'tanggal_lahir',
        'status_keluarga',
        'nama_file',
    ];
}
