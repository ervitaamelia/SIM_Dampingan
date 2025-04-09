<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupatens';
    protected $primaryKey = 'kode';
    public $incrementing = false;

    protected $fillable = ['kode', 'nama', 'kode_provinsi'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kode_provinsi', 'kode');
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'kode_kabupaten', 'kode');
    }
}
