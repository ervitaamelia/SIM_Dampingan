<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Inertia\Inertia;

class KelolaKegiatanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Map role user ke jenis_dampingan di grup
        $roleToJenisDampingan = [
            'superadmin' => 'Pusat',
            'admin-provinsi' => 'Provinsi',
            'admin-kabupaten' => 'Kabupaten',
            'admin-kecamatan' => 'Kecamatan',
        ];

        $jenisDampingan = $roleToJenisDampingan[$user->role] ?? null;

        $kegiatanDiproses = Kegiatan::with(['user', 'galeris', 'grups'])
            ->where('status_kegiatan', 'diproses')
            ->whereHas('grups', function ($query) use ($user, $jenisDampingan) {
                $query->where('jenis_dampingan', $jenisDampingan)
                    ->when($user->role === 'admin-provinsi', function ($q) use ($user) {
                        $q->where('kode_provinsi', $user->kode_provinsi);
                    })
                    ->when($user->role === 'admin-kabupaten', function ($q) use ($user) {
                        $q->where('kode_kabupaten', $user->kode_kabupaten);
                    })
                    ->when($user->role === 'admin-kecamatan', function ($q) use ($user) {
                        $q->where('kode_kecamatan', $user->kode_kecamatan);
                    });
            })
            ->get();

        return Inertia::render('admin/KelolaKegiatan', [
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