<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelaporLaporan extends Model
{
    use HasFactory;

    protected $table = 'pelapor_laporan';

    protected $fillable = [
        'id_user',
        'id_laporan',
        'deskripsi_tambahan',
        'rating_pengguna',
        'feedback_pengguna',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function penugasan()
    {
        return $this->hasOne(PenugasanTeknisi::class, 'id_laporan');
    }
}
