<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_level',
        'nama',
        'email',
        'password',
        'foto_profil',
        'akses'
    ];

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level');
    }

    public function laporan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_user');
    }

    public function penugasan()
    {
        return $this->hasMany(PenugasanTeknisi::class, 'id_user');
    }

    public function getRole()
    {
        return $this->id_level;
    }

    public function laporanDilaporkan()
    {
        return $this->hasMany(PelaporLaporan::class, 'id_user');
    }

    public function credit_score()
    {
        return $this->hasOne(CreditScoreTeknisi::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
