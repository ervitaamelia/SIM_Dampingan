<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Masyarakat;
use App\Models\Pekerjaan;
use App\Models\Bidang;
use App\Models\GrupDampingan;
use Inertia\Inertia;

class DataMasyarakatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = GrupDampingan::query();

        if ($user->role === 'admin-provinsi') {
            $query->where('grup_dampingan.kode_provinsi', $user->kode_provinsi);
        } elseif ($user->role === 'admin-kabupaten') {
            $query->where('grup_dampingan.kode_kabupaten', $user->kode_kabupaten);
        } elseif ($user->role === 'admin-kecamatan') {
            $query->where('grup_dampingan.kode_kecamatan', $user->kode_kecamatan);
        }

        $grups = $query->get();

        $grupIds = $grups->pluck('id_grup_dampingan');

        $masyarakats = Masyarakat::with([
            'pekerjaan',
            'bidang',
            'grup',
        ])
            ->whereIn('id_grup_dampingan', $grupIds)
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $bidangs = Bidang::all();

        return Inertia::render('admin/DataMasyarakatView', [
            'data' => $masyarakats,
            'bidangList' => $bidangs,
            'grupList' => $grups,
        ]);
    }

    public function create()
    {
        $grupKoordinatorMap = Masyarakat::where('peran', 'koordinator')
            ->get()
            ->groupBy('id_grup_dampingan')
            ->map(fn($group) => $group->first()->nama_lengkap);

        return Inertia::render('admin/FormMasyarakatView', [
            'pekerjaans' => Pekerjaan::all(['id_pekerjaan', 'nama_pekerjaan']),
            'bidangs' => Bidang::all(['id_bidang', 'nama_bidang']),
            'grups' => GrupDampingan::with(['bidang:id_bidang,nama_bidang'])->get(['id_grup_dampingan', 'nama_grup_dampingan', 'id_bidang']),
            'grupKoordinators' => $grupKoordinatorMap,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu',
            'nomor_telepon' => 'required|string|max:15',
            'alamat' => 'required',
            'status' => 'required|in:Aktif,Non Aktif',
            'peran' => 'required|in:koordinator,anggota',
            'foto' => 'nullable|image',
            'id_pekerjaan' => 'required|exists:pekerjaan,id_pekerjaan',
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'id_grup_dampingan' => 'required|exists:grup_dampingan,id_grup_dampingan',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto', 'public');
        }

        // Ambil data grup dampingan
        $grup = GrupDampingan::findOrFail($request->id_grup_dampingan);

        // Hapus titik dari kode_kecamatan
        $kodeKecamatan = str_replace('.', '', $grup->kode_kecamatan);

        // Mapping jenis_dampingan ke angka
        $jenisMap = [
            'Pusat' => '01',
            'Provinsi' => '02',
            'Kabupaten' => '03',
            'Kecamatan' => '04',
        ];

        // Ambil kode jenis_dampingan
        $jenisKode = $jenisMap[$grup->jenis_dampingan];

        // Gabungkan prefix
        $prefix = $kodeKecamatan . $jenisKode;

        // Cari nomor anggota terakhir dengan prefix yang sama
        $last = Masyarakat::where('nomor_anggota', 'like', "$prefix%")
            ->orderBy('nomor_anggota', 'desc')
            ->first();

        $nextNumber = $last ? intval(substr($last->nomor_anggota, strlen($prefix))) + 1 : 1;

        // Buat nomor anggota baru dengan 4 digit increment
        $nomor_anggota = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $affected = 0;
        if ($request->peran === 'koordinator') {
            $affected = Masyarakat::where('id_grup_dampingan', $request->id_grup_dampingan)
                ->update(['peran' => 'anggota']);
        }

        Masyarakat::create([
            'nomor_anggota' => $nomor_anggota,
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'peran' => $request->peran,
            'foto' => $fotoPath ?? null,
            'id_pekerjaan' => $request->id_pekerjaan,
            'id_bidang' => $request->id_bidang,
            'id_grup_dampingan' => $request->id_grup_dampingan,
        ]);

        return redirect()->route('masyarakat.index')
            ->with('success', 'Data masyarakat berhasil ditambahkan.' . ($affected ? ' Koordinator sebelumnya telah diubah menjadi anggota.' : ''));
    }

    public function edit($id)
    {
        $masyarakat = Masyarakat::findOrFail($id);

        $grupKoordinatorMap = Masyarakat::where('peran', 'koordinator')
            ->get()
            ->groupBy('id_grup_dampingan')
            ->map(fn($group) => $group->first()->nama_lengkap);

        return Inertia::render('admin/FormMasyarakatView', [
            'masyarakat' => $masyarakat,
            'pekerjaans' => Pekerjaan::all(['id_pekerjaan', 'nama_pekerjaan']),
            'bidangs' => Bidang::all(['id_bidang', 'nama_bidang']),
            'grups' => GrupDampingan::with(['bidang:id_bidang,nama_bidang'])->get(['id_grup_dampingan', 'nama_grup_dampingan', 'id_bidang']),
            'grupKoordinators' => $grupKoordinatorMap,
        ]);
    }

    public function update(Request $request, $id)
    {
        $masyarakat = Masyarakat::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu',
            'nomor_telepon' => 'required|string|max:15',
            'alamat' => 'required',
            'status' => 'required|in:Aktif,Non Aktif',
            'peran' => 'required|in:koordinator,anggota',
            'id_pekerjaan' => 'required|exists:pekerjaan,id_pekerjaan',
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'id_grup_dampingan' => 'required|exists:grup_dampingan,id_grup_dampingan',
        ]);

        if ($request->peran === 'koordinator') {
            $affected = Masyarakat::where('id_grup_dampingan', $request->id_grup_dampingan)
                ->where('nomor_anggota', '!=', $masyarakat->nomor_anggota)
                ->update(['peran' => 'anggota']);
        } else {
            $affected = 0;
        }

        if ($request->hasFile('foto')) {
            //upload new image
            $fotoPath = $request->file('foto')->store('foto', 'public');

            //delete old image
            Storage::delete('public/' . $masyarakat->foto);

            //update product with new image
            $masyarakat->update([
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat' => $request->alamat,
                'status' => $request->status,
                'peran' => $request->peran,
                'foto' => $fotoPath,
                'id_pekerjaan' => $request->id_pekerjaan,
                'id_bidang' => $request->id_bidang,
                'id_grup_dampingan' => $request->id_grup_dampingan,
            ]);
        } else {
            //update product without image
            $masyarakat->update([
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat' => $request->alamat,
                'status' => $request->status,
                'peran' => $request->peran,
                'id_pekerjaan' => $request->id_pekerjaan,
                'id_bidang' => $request->id_bidang,
                'id_grup_dampingan' => $request->id_grup_dampingan,
            ]);
        }

        return redirect()->route('masyarakat.index')
            ->with('success', 'Data masyarakat berhasil diperbarui.' . ($affected ? ' Koordinator sebelumnya telah diubah menjadi anggota.' : ''));
    }

    public function destroy($id)
    {
        $masyarakat = Masyarakat::findOrFail($id);

        // Hapus file foto jika ada
        if ($masyarakat->foto && Storage::disk('public')->exists($masyarakat->foto)) {
            Storage::disk('public')->delete($masyarakat->foto);
        }

        $masyarakat->delete();

        return redirect()->route('masyarakat.index')->with('success', 'Data masyarakat berhasil dihapus.');
    }
}