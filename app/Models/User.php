<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'must_change_password',
        'nomor_telepon',
        'alamat',
        'role',
        'foto',
        'kode_provinsi',
        'kode_kabupaten',
        'kode_kecamatan'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bidangs()
    {
        return $this->belongsToMany(Bidang::class, 'bidang_dampingan', 'id_user', 'id_bidang')
            ->withTimestamps();
    }

    public function grupDampingan()
    {
        return $this->belongsToMany(GrupDampingan::class, 'fasilitator', 'id_user', 'id_grup_dampingan');
    }

}