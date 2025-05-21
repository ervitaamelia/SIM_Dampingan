<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Inertia\Inertia;

class DashboardFasilitatorController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::with('user', 'provinsi', 'kabupaten', 'kecamatan', 'bidang', 'galeris', 'grups')
            ->latest()
            ->get();

        return Inertia::render('fasilitator/Dashboard', [
            'data' => $kegiatans
        ]);
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with('user', 'provinsi', 'kabupaten', 'kecamatan', 'bidang', 'galeris', 'grups')
            ->findOrFail($id);

        return Inertia::render('DetailKegiatanView', [
            'kegiatan' => $kegiatan
        ]);
    }
}
