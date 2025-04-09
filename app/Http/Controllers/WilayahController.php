<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;

class WilayahController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Provinsi::select('kode', 'nama')->get();
        return response()->json($provinsi);
    }

    public function getKabupaten($kode_provinsi)
    {
        $kabupaten = Kabupaten::where('kode_provinsi', $kode_provinsi)->select('kode', 'nama')->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan($kode_kabupaten)
    {
        $kecamatan = Kecamatan::where('kode_kabupaten', $kode_kabupaten)->select('kode', 'nama')->get();
        return response()->json($kecamatan);
    }
}
