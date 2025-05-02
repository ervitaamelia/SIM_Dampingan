<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';
    protected $primaryKey = 'id_kegiatan';
    public $timestamps = true;

    protected $fillable = [
        'judul',
        'deskripsi',
        'masalah',
        'solusi',
        'tanggal',
        'waktu',
        'tempat',
        'jumlah_peserta',
        'laporan',
        'id_user',
        'kode_kecamatan',
        'id_bidang'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kode_kecamatan', 'kode');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    public function galeris()
    {
        return $this->hasMany(Galeri::class, 'id_kegiatan');
    }

    public function grups()
    {
        return $this->belongsToMany(GrupDampingan::class, 'grup_kegiatan', 'id_kegiatan', 'id_grup_dampingan')
            ->withTimestamps();
    }
}
