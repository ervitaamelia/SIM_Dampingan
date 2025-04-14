<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupDampingan;
use App\Models\Bidang;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\User;
use Inertia\Inertia;

class DataDampinganController extends Controller
{
    public function index()
    {
        $grups = GrupDampingan::with([
            'bidang',
            'users',
        ])
            ->select(
                'grup_dampingan.*',
                'provinsis.nama as nama_provinsi',
                'kabupatens.nama as nama_kabupaten',
                'kecamatans.nama as nama_kecamatan'
            )
            ->leftJoin('provinsis', 'grup_dampingan.kode_provinsi', '=', 'provinsis.kode')
            ->leftJoin('kabupatens', 'grup_dampingan.kode_kabupaten', '=', 'kabupatens.kode')
            ->leftJoin('kecamatans', 'grup_dampingan.kode_kecamatan', '=', 'kecamatans.kode')
            ->get();

        return Inertia::render('admin/DataDampinganView', [
            'data' => $grups
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/FormDampinganView', [
            'bidangs' => Bidang::all(['id_bidang', 'nama_bidang']),
            'provinsis' => Provinsi::all(['kode', 'nama']),
            'kabupatens' => Kabupaten::all(['kode', 'nama', 'kode_provinsi']),
            'kecamatans' => Kecamatan::all(['kode', 'nama', 'kode_kabupaten']),
            'users' => User::where('role', 'fasilitator')->with(['bidangs:id_bidang,nama_bidang'])->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_grup_dampingan' => 'required|string|max:100',
            'jenis_dampingan' => 'required|in:Pusat,Provinsi,Kabupaten,Kecamatan',
            'kode_provinsi' => 'required|exists:provinsis,kode',
            'kode_kabupaten' => 'required|exists:kabupatens,kode',
            'kode_kecamatan' => 'required|exists:kecamatans,kode',
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'id_user' => 'required|array|min:1',
            'id_user.*' => 'exists:users,id',
        ]);

        $grup = GrupDampingan::create([
            'nama_grup_dampingan' => $request->nama_grup_dampingan,
            'jenis_dampingan' => $request->jenis_dampingan,
            'kode_provinsi' => $request->kode_provinsi,
            'kode_kabupaten' => $request->kode_kabupaten,
            'kode_kecamatan' => $request->kode_kecamatan,
            'id_bidang' => $request->id_bidang,
        ]);

        // Simpan fasilitator (relasi many-to-many)
        $grup->users()->attach($request->id_user);

        return redirect()->route('dampingan.index')->with('success', 'Data grup dampingan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $grup = GrupDampingan::with('users:id,name')->findOrFail($id);

        return Inertia::render('admin/FormDampinganView', [
            'grup' => [
                'id_grup_dampingan' => $grup->id_grup_dampingan,
                'nama_grup_dampingan' => $grup->nama_grup_dampingan,
                'jenis_dampingan' => $grup->jenis_dampingan,
                'kode_provinsi' => $grup->kode_provinsi,
                'kode_kabupaten' => $grup->kode_kabupaten,
                'kode_kecamatan' => $grup->kode_kecamatan,
                'id_bidang' => $grup->id_bidang,
                'users' => $grup->users,
            ],
            'bidangs' => Bidang::all(['id_bidang', 'nama_bidang']),
            'provinsis' => Provinsi::all(['kode', 'nama']),
            'kabupatens' => Kabupaten::all(['kode', 'nama', 'kode_provinsi']),
            'kecamatans' => Kecamatan::all(['kode', 'nama', 'kode_kabupaten']),
            'users' => User::where('role', 'fasilitator')->with(['bidangs:id_bidang,nama_bidang'])->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $grup = GrupDampingan::findOrFail($id);

        $request->validate([
            'nama_grup_dampingan' => 'required|string|max:100',
            'jenis_dampingan' => 'required|in:Pusat,Provinsi,Kabupaten,Kecamatan',
            'kode_provinsi' => 'required|exists:provinsis,kode',
            'kode_kabupaten' => 'required|exists:kabupatens,kode',
            'kode_kecamatan' => 'required|exists:kecamatans,kode',
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'id_user' => 'required|array|min:1',
            'id_user.*' => 'exists:users,id',
        ]);

        $grup->update([
            'nama_grup_dampingan' => $request->nama_grup_dampingan,
            'jenis_dampingan' => $request->jenis_dampingan,
            'kode_provinsi' => $request->kode_provinsi,
            'kode_kabupaten' => $request->kode_kabupaten,
            'kode_kecamatan' => $request->kode_kecamatan,
            'id_bidang' => $request->id_bidang,
        ]);

        // Sinkronisasi fasilitator
        $grup->users()->sync($request->id_user);

        return redirect()->route('dampingan.index')->with('success', 'Data grup dampingan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $grup = GrupDampingan::findOrFail($id);

        // Hapus relasi ke users terlebih dahulu
        $grup->users()->detach();
        $grup->delete();

        return redirect()->route('dampingan.index')->with('success', 'Data grup dampingan berhasil dihapus!');
    }
}
