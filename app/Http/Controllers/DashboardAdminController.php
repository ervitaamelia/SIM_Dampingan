<?php

namespace App\Http\Controllers;

use App\Models\GrupDampingan;
use App\Models\Masyarakat;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalMasyarakat = Masyarakat::where('status', 'Aktif')
            ->count();

        $totalFasilitator = User::where('role', 'fasilitator')
            ->count();

        $totalGrup = GrupDampingan::count();

        $anggotaTerbanyak = Masyarakat::join('grup_dampingan', 'masyarakat.id_grup_dampingan', 'grup_dampingan.id_grup_dampingan')
            ->join('bidang', 'grup_dampingan.id_bidang', '=', 'bidang.id_bidang')
            ->where('masyarakat.status', 'Aktif')
            ->select('grup_dampingan.nama_grup_dampingan', 'bidang.nama_bidang', DB::raw('COUNT(masyarakat.id_grup_dampingan) as jumlah_anggota'))
            ->groupBy('grup_dampingan.nama_grup_dampingan', 'bidang.nama_bidang')
            ->orderByDesc('jumlah_anggota')
            ->take(10)
            ->get()
            ->toArray();

        return Inertia::render('admin/Dashboard', [
            'totalMasyarakat' => $totalMasyarakat,
            'totalFasilitator' => $totalFasilitator,
            'totalGrup' => $totalGrup,
            'anggotaTerbanyak' => $anggotaTerbanyak,
        ]);
    }
}