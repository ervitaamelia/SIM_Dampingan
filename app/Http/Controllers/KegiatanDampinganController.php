<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KegiatanDampinganController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::with('user', 'provinsi', 'kabupaten', 'kecamatan', 'bidang', 'galeris', 'grups')
            ->latest()
            ->get();

        return Inertia::render('admin/KegiatanDampinganView', [
            'data' => $kegiatans
        ]);
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with('user', 'provinsi', 'kabupaten', 'kecamatan', 'bidang', 'galeris', 'grups')
            ->findOrFail($id);

        $artikelLain = Kegiatan::with('galeris')
            ->where('id_kegiatan', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('DetailKegiatanView', [
            'kegiatan' => $kegiatan,
            'artikelLain' => $artikelLain
        ]);
    }
}
