<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;

class WilayahController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/provinsi",
     *  tags={"Provinsi"},
     *  summary="Mengambil daftar provinsi",
     *  description="Mengambil data semua provinsi",
     *  operationId="getProvinsi",
     *  @OA\Response(
     *      response=200,
     *      description="Berhasil mengambil data provinsi"
     *  )
     * )
     */
    public function getProvinsi()
    {
        $provinsi = Provinsi::select('kode', 'nama')->get();
        return response()->json($provinsi);
    }

    /**
     * @OA\Get(
     *  path="/api/kabupaten/{kode_provinsi}",
     *  tags={"Kabupaten"},
     *  summary="Mengambil daftar kabupaten berdasarkan provinsi",
     *  description="Mengambil data kabupaten sesuai kode provinsi",
     *  operationId="getKabupaten",
     *  @OA\Parameter(
     *      name="kode_provinsi",
     *      in="path",
     *      required=true,
     *      description="Kode provinsi",
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Berhasil mengambil data kabupaten"
     *  )
     * )
     */
    public function getKabupaten($kode_provinsi)
    {
        $kabupaten = Kabupaten::where('kode_provinsi', $kode_provinsi)->select('kode', 'nama')->get();
        return response()->json($kabupaten);
    }

    /**
     * @OA\Get(
     *  path="/api/kecamatan/{kode_kabupaten}",
     *  tags={"Kecamatan"},
     *  summary="Mengambil daftar kecamatan berdasarkan kabupaten",
     *  description="Mengambil data kecamatan sesuai kode kabupaten",
     *  operationId="getKecamatan",
     *  @OA\Parameter(
     *      name="kode_kabupaten",
     *      in="path",
     *      required=true,
     *      description="Kode kabupaten",
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Berhasil mengambil data kecamatan"
     *  )
     * )
     */
    public function getKecamatan($kode_kabupaten)
    {
        $kecamatan = Kecamatan::where('kode_kabupaten', $kode_kabupaten)->select('kode', 'nama')->get();
        return response()->json($kecamatan);
    }
}