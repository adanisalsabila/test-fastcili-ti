<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;
    
    protected $table = 'gedung';
    protected $primaryKey = 'id_gedung';

    protected $fillable = ['kode_gedung', 'nama_gedung', 'deskripsi', 'foto_gedung'];

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_gedung');
    }
}
