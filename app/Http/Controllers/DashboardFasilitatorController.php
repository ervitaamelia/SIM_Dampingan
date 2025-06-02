<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Masyarakat;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardFasilitatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $grupIds = $user->grupDampingan->pluck('id_grup_dampingan');

        $totalMasyarakat = Masyarakat::where('status', 'Aktif')
            ->whereIn('id_grup_dampingan', $grupIds)
            ->count();

        $totalGrup = $user->grupDampingan()->count();

        $totalKegiatan = Kegiatan::where('id_user', $user->id)->count();

        $grups = Masyarakat::join('grup_dampingan', 'masyarakat.id_grup_dampingan', '=', 'grup_dampingan.id_grup_dampingan')
            ->join('bidang', 'grup_dampingan.id_bidang', '=', 'bidang.id_bidang')
            ->leftJoin('provinsis', 'grup_dampingan.kode_provinsi', '=', 'provinsis.kode')
            ->leftJoin('kabupatens', 'grup_dampingan.kode_kabupaten', '=', 'kabupatens.kode')
            ->leftJoin('kecamatans', 'grup_dampingan.kode_kecamatan', '=', 'kecamatans.kode')
            ->where('masyarakat.status', 'Aktif')
            ->whereIn('masyarakat.id_grup_dampingan', $grupIds)
            ->select(
                'grup_dampingan.nama_grup_dampingan',
                'bidang.nama_bidang',
                'provinsis.nama as nama_provinsi',
                'kabupatens.nama as nama_kabupaten',
                'kecamatans.nama as nama_kecamatan',
                DB::raw('COUNT(masyarakat.id_grup_dampingan) as jumlah_anggota')
            )
            ->groupBy(
                'grup_dampingan.nama_grup_dampingan',
                'bidang.nama_bidang',
                'provinsis.nama',
                'kabupatens.nama',
                'kecamatans.nama'
            )
            ->orderBy('grup_dampingan.nama_grup_dampingan', 'asc')
            ->get();

        $kegiatans = Kegiatan::with('user', 'provinsi', 'kabupaten', 'kecamatan', 'bidang', 'galeris', 'grups')
            ->where('id_user', $user->id)
            ->orderBy('tanggal', 'desc')
            ->take(3)
            ->get();

        return Inertia::render('fasilitator/Dashboard', [
            'totalGrup' => $totalGrup,
            'totalMasyarakat' => $totalMasyarakat,
            'totalKegiatan' => $totalKegiatan,
            'grups' => $grups,
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
            'artikelLain' => $artikelLain,
        ]);
    }
}