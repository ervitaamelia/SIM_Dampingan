<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';
    protected $primaryKey = 'kode';
    public $incrementing = false;

    protected $fillable = ['kode', 'nama', 'kode_kabupaten'];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kode_kabupaten', 'kode');
    }
}
