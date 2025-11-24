<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaPenilaian extends Model
{
    use HasFactory;

    protected $table = 'kriteria_penilaian';
    protected $primaryKey = 'id_kriteria';

    protected $fillable = [
        'id_laporan',
        'tingkat_kerusakan',
        'frekuensi_digunakan',
        'dampak',
        'estimasi_biaya',
        'potensi_bahaya'
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }
}
