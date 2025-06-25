<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Bidang;
use App\Models\GrupDampingan;
use Inertia\Inertia;

class DataKegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::with(
            'user',
            'galeris',
        )
            ->where('id_user', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('fasilitator/DataKegiatan', [
            'data' => $kegiatans
        ]);
    }

    public function create()
    {
        return Inertia::render('fasilitator/FormKegiatan', [
            'bidangs' => Bidang::all(['id_bidang', 'nama_bidang']),
            'grups' => GrupDampingan::with(['bidang:id_bidang,nama_bidang'])->get(['id_grup_dampingan', 'nama_grup_dampingan', 'id_bidang']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'masalah' => 'nullable|string',
            'solusi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'alamat' => 'required|string',
            'jumlah_peserta' => 'required|integer',
            'laporan' => 'nullable|mimes:pdf,doc,docx|file',
            'kode_provinsi' => 'required|exists:provinsis,kode',
            'kode_kabupaten' => 'required|exists:kabupatens,kode',
            'kode_kecamatan' => 'required|exists:kecamatans,kode',
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'id_grup_dampingan' => 'required|array|min:1',
            'id_grup_dampingan.*' => 'exists:grup_dampingan,id_grup_dampingan',
            'foto' => 'required|array|min:1',
            'foto.*' => 'image'
        ]);

        // Simpan file laporan jika ada
        $laporanPath = null;
        if ($request->hasFile('laporan')) {
            $laporanPath = $request->file('laporan')->store('laporan_kegiatan', 'public');
        }

        $kegiatan = Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'masalah' => $request->masalah ?? null,
            'solusi' => $request->solusi ?? null,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'alamat' => $request->alamat,
            'jumlah_peserta' => $request->jumlah_peserta,
            'laporan' => $laporanPath,
            'id_user' => Auth::id(),
            'kode_provinsi' => $request->kode_provinsi,
            'kode_kabupaten' => $request->kode_kabupaten,
            'kode_kecamatan' => $request->kode_kecamatan,
            'id_bidang' => $request->id_bidang
        ]);

        // Simpan grup dampingan (relasi many-to-many)
        $kegiatan->grups()->attach($request->id_grup_dampingan);

        // Simpan foto ke galeri
        foreach ($request->foto as $foto) {
            $fotoPath = $foto->store('foto_kegiatan', 'public');

            $kegiatan->galeris()->create([
                'foto' => $fotoPath,
            ]);
        }

        return redirect()->route('kegiatan.index')->with('success', 'Data kegiatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::with('user', 'provinsi', 'kabupaten', 'kecamatan', 'bidang', 'galeris', 'grups')
            ->where('id_user', Auth::id()) // pastikan hanya bisa edit kegiatan sendiri
            ->findOrFail($id);

        return Inertia::render('fasilitator/FormKegiatan', [
            'kegiatan' => $kegiatan,
            'bidangs' => Bidang::all(['id_bidang', 'nama_bidang']),
            'grups' => GrupDampingan::with(['bidang:id_bidang,nama_bidang'])->get(['id_grup_dampingan', 'nama_grup_dampingan', 'id_bidang']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::where('id_user', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'masalah' => 'nullable|string',
            'solusi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'alamat' => 'required|string',
            'jumlah_peserta' => 'required|integer',
            'laporan' => 'nullable|mimes:pdf,doc,docx|file',
            'kode_provinsi' => 'required|exists:provinsis,kode',
            'kode_kabupaten' => 'required|exists:kabupatens,kode',
            'kode_kecamatan' => 'required|exists:kecamatans,kode',
            'id_bidang' => 'required|exists:bidang,id_bidang',
            'id_grup_dampingan' => 'required|array|min:1',
            'id_grup_dampingan.*' => 'exists:grup_dampingan,id_grup_dampingan',
            'foto.*' => 'nullable|image',
            'edited_galeris.*' => 'nullable|image',
            'deleted_galeris' => 'nullable|array',
            'deleted_galeris.*' => 'exists:galeri,id_galeri'
        ]);

        // Update data kegiatan
        $kegiatan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'masalah' => $request->masalah,
            'solusi' => $request->solusi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'alamat' => $request->alamat,
            'jumlah_peserta' => $request->jumlah_peserta,
            'status_kegiatan' => 'diproses',
            'kode_provinsi' => $request->kode_provinsi,
            'kode_kabupaten' => $request->kode_kabupaten,
            'kode_kecamatan' => $request->kode_kecamatan,
            'id_bidang' => $request->id_bidang,
        ]);

        // Update grup dampingan
        $kegiatan->grups()->sync($request->id_grup_dampingan);

        // Proses foto yang dihapus
        if ($request->has('deleted_galeris')) {
            foreach ($request->deleted_galeris as $galeriId) {
                $galeri = $kegiatan->galeris()->find($galeriId);
                if ($galeri) {
                    Storage::disk('public')->delete($galeri->foto);
                    $galeri->delete();
                }
            }
        }

        // Proses foto yang diedit
        if ($request->hasFile('edited_galeris')) {
            foreach ($request->file('edited_galeris') as $galeriId => $file) {
                $galeri = $kegiatan->galeris()->find($galeriId);
                if ($galeri) {
                    Storage::disk('public')->delete($galeri->foto);
                    $path = $file->store('foto_kegiatan', 'public');
                    $galeri->update(['foto' => $path]);
                }
            }
        }

        // Proses foto baru
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('foto_kegiatan', 'public');
                $kegiatan->galeris()->create(['foto' => $path]);
            }
        }

        // Proses laporan baru jika ada
        if ($request->hasFile('laporan')) {
            if ($kegiatan->laporan) {
                Storage::disk('public')->delete($kegiatan->laporan);
            }
            $path = $request->file('laporan')->store('laporan_kegiatan', 'public');
            $kegiatan->update(['laporan' => $path]);
        }

        return redirect()->route('kegiatan.index')
            ->with('success', 'Data kegiatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::with('galeris')->where('id_user', Auth::id())->findOrFail($id);

        // Hapus semua file foto
        foreach ($kegiatan->galeris as $galeri) {
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $galeri->delete();
        }

        // Hapus laporan
        if ($kegiatan->laporan && Storage::disk('public')->exists($kegiatan->laporan)) {
            Storage::disk('public')->delete($kegiatan->laporan);
        }

        // Hapus relasi many-to-many
        $kegiatan->grups()->detach();

        // Hapus kegiatan
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Data kegiatan berhasil dihapus.');
    }
}