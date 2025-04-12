<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang'; // Nama tabel di database

    protected $primaryKey = 'id_bidang'; // Primary Key

    public $timestamps = true; // Jika tabel menggunakan timestamps (created_at & updated_at)

    protected $fillable = [
        'nama_bidang'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'bidang_dampingan', 'id_bidang', 'id_user')
            ->withTimestamps();
    }

}