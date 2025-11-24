<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditScoreTeknisi extends Model
{
    use HasFactory;
    protected $table = 'credit_score_teknisi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'credit_score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
