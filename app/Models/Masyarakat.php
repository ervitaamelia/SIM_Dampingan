<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masyarakat extends Model
{
    use HasFactory;

    protected $table = 'masyarakat';
    protected $primaryKey = 'nomor_anggota';
    protected $casts = [
        'nomor_anggota' => 'string',
    ];

    public $timestamps = true;

    protected $fillable = [
        'nomor_anggota',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'nomor_telepon',
        'alamat',
        'status',
        'foto',
        'id_pekerjaan',
        'id_bidang',
        'id_grup_dampingan',
    ];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'id_pekerjaan', 'id_pekerjaan');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    public function grup()
    {
        return $this->belongsTo(GrupDampingan::class, 'id_grup_dampingan', 'id_grup_dampingan');
    }
}