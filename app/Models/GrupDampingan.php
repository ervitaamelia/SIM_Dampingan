<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupDampingan extends Model
{
    use HasFactory;

    protected $table = 'grup_dampingan';
    protected $primaryKey = 'id_grup_dampingan';
    public $timestamps = true;

    protected $fillable = [
        'nama_grup_dampingan',
        'jenis_dampingan',
        'kode_provinsi',
        'kode_kabupaten',
        'kode_kecamatan',
        'id_bidang',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kode_provinsi', 'kode');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kode_kabupaten', 'kode');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kode_kecamatan', 'kode');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'fasilitator', 'id_grup_dampingan', 'id_user')
            ->withTimestamps();
    }

    public function kegiatans()
    {
        return $this->belongsToMany(Kegiatan::class, 'grup_kegiatan', 'id_grup_dampingan', 'id_kegiatan')
            ->withTimestamps();
    }
}
