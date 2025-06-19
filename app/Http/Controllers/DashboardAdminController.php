<?php

namespace App\Http\Controllers;

use App\Models\GrupDampingan;
use App\Models\Masyarakat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $jumlahKegiatanBulanan = DB::table('kegiatan')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();

        $totalMasyarakat = Masyarakat::where('status', 'Aktif')->count();
        $totalFasilitator = User::where('role', 'fasilitator')->count();
        $totalGrup = GrupDampingan::count();

        $anggotaTerbanyak = Masyarakat::join('grup_dampingan', 'masyarakat.id_grup_dampingan', '=', 'grup_dampingan.id_grup_dampingan')
            ->join('bidang', 'grup_dampingan.id_bidang', '=', 'bidang.id_bidang')
            ->where('masyarakat.status', 'Aktif')
            ->select(
                'grup_dampingan.nama_grup_dampingan',
                'bidang.nama_bidang',
                DB::raw('COUNT(masyarakat.id_grup_dampingan) as jumlah_anggota')
            )
            ->groupBy('grup_dampingan.nama_grup_dampingan', 'bidang.nama_bidang')
            ->orderByDesc('jumlah_anggota')
            ->take(5)
            ->get();

        $kegiatanPerGrup = DB::table('grup_dampingan')
            ->join('grup_kegiatan', 'grup_dampingan.id_grup_dampingan', '=', 'grup_kegiatan.id_grup_dampingan')
            ->join('kegiatan', 'grup_kegiatan.id_kegiatan', '=', 'kegiatan.id_kegiatan')
            ->whereMonth('kegiatan.tanggal', $month)
            ->whereYear('kegiatan.tanggal', $year)
            ->select(
                'grup_dampingan.nama_grup_dampingan',
                DB::raw('COUNT(kegiatan.id_kegiatan) as jumlah_kegiatan'),
                DB::raw("GROUP_CONCAT(CONCAT(kegiatan.judul, ' (', DATE_FORMAT(kegiatan.tanggal, '%d-%m-%Y'), ')') SEPARATOR '||') as detail_kegiatan")
            )
            ->groupBy('grup_dampingan.nama_grup_dampingan')
            ->orderByDesc('jumlah_kegiatan')
            ->take(10)
            ->get();

        $grupPerJenis = GrupDampingan::select('jenis_dampingan', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_dampingan')
            ->get();

        return Inertia::render('admin/Dashboard', [
            'totalMasyarakat' => $totalMasyarakat,
            'totalFasilitator' => $totalFasilitator,
            'totalGrup' => $totalGrup,
            'anggotaTerbanyak' => $anggotaTerbanyak,
            'kegiatanPerGrup' => $kegiatanPerGrup,
            'grupPerJenis' => $grupPerJenis,
            'jumlahKegiatanBulanan' => $jumlahKegiatanBulanan,
            'selectedMonth' => (int) $month,
            'selectedYear' => (int) $year,
        ]);
    }
}