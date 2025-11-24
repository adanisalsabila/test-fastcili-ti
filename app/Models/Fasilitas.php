<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;
    protected $table = 'fasilitas';
    protected $primaryKey = 'id_fasilitas';

    protected $fillable = ['id_ruangan', 'kode_fasilitas', 'nama_fasilitas', 'jumlah'];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    public function laporan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_fasilitas');
    }   
}
