<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'name',
        'email',
        'alamat',
        'nomor_telepon',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'bidang_dampingan_id',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function bidangDampingan()
    {
        return $this->belongsTo(BidangDampingan::class);
    }
}
