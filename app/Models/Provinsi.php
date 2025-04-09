<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsis';
    protected $primaryKey = 'kode';
    public $incrementing = false;

    protected $fillable = ['kode', 'nama'];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class, 'kode_provinsi', 'kode');
    }
}
