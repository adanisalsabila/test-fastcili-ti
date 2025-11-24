<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLaporan extends Model
{
    use HasFactory;
    protected $table = 'status_laporan';
    protected $primaryKey = 'id_status';

    protected $fillable = ['nama_status'];

    public function laporan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_status');
    }
}
