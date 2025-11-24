<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanTeknisi extends Model
{
    use HasFactory;
    protected $table = 'penugasan_teknisi';
    protected $primaryKey = 'id_penugasan';

    protected $fillable = [
        'id_laporan',
        'id_user',
        'tanggal_selesai',
        'tenggat',
        'status_perbaikan',
        'catatan_teknisi',
        'dokumentasi',
        'komentar_sarpras',
        'skor_kinerja'
    ];

    protected $casts = [
        'tenggat' => 'date', // atau 'datetime'
        'tanggal_selesai' => 'date', // atau 'datetime'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan', 'id_laporan');
    }
    
}
