<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Inertia\Inertia;

class KelolaKegiatanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $kegiatan = Kegiatan::with(['user', 'galeris', 'grups'])->get();
        } else {
            $kegiatan = Kegiatan::with(['user', 'galeris', 'grups'])
                ->where('status_kegiatan', 'diproses')
                ->whereHas('grups', function ($query) use ($user) {
                    if ($user->role === 'admin-provinsi') {
                        $query->where(function ($q) {
                            $q->where('jenis_dampingan', 'Pusat')
                                ->orWhere('jenis_dampingan', 'Provinsi');
                        })
                            ->where('kode_provinsi', $user->kode_provinsi);
                    }

                    if ($user->role === 'admin-kabupaten') {
                        $query->where('jenis_dampingan', 'Kabupaten')
                            ->where('kode_kabupaten', $user->kode_kabupaten);
                    }

                    if ($user->role === 'admin-kecamatan') {
                        $query->where('jenis_dampingan', 'Kecamatan')
                            ->where('kode_kecamatan', $user->kode_kecamatan);
                    }
                })
                ->get();
        }

        return Inertia::render('admin/KelolaKegiatan', [
            'data' => $kegiatan
        ]);
    }

    public function tolak($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status_kegiatan = 'ditolak';
        $kegiatan->save();

        return redirect()->back()->with('message', 'Kegiatan berhasil ditolak.');
    }
}