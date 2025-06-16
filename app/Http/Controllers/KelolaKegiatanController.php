<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KelolaKegiatanController extends Controller
{
    public function index()
    {
        $kegiatanDiproses = Kegiatan::with('galeris')
            ->where('status_kegiatan', 'diproses')
            ->get();

        return Inertia::render('admin/KelolaKegiatan', [ // GANTI sesuai nama view
            'data' => $kegiatanDiproses
        ]);
    }
    
    public function validasi($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status_kegiatan = 'divalidasi';
        $kegiatan->save();

        return redirect()->back()->with('message', 'Kegiatan berhasil divalidasi.');
    }

    public function tolak($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status_kegiatan = 'ditolak';
        $kegiatan->save();

        return redirect()->back()->with('message', 'Kegiatan berhasil ditolak.');
    }
}