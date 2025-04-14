<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\BidangDampingan;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AdminController extends Controller
{

    public function create()
    {
        return Inertia::render('Admin/FormAdmin', [
            'provinsis' => Provinsi::select('kode', 'nama')->get(),
            'kabupatens' => Kabupaten::select('kode', 'nama', 'kode_provinsi')->get(),
            'kecamatans' => Kecamatan::select('kode', 'nama', 'kode_kabupaten')->get(),
        ]);
    }

    public function getDropdownData()
    {
        return response()->json([
            'provinsis' => Provinsi::select('id', 'nama')->get(),
            'kabupatens' => Kabupaten::select('id', 'provinsi_id', 'nama')->get(),
            'kecamatans' => Kecamatan::select('id', 'kabupaten_id', 'nama')->get(),
            'bidang_dampingan' => BidangDampingan::select('id', 'nama')->get(),
        ]);
    }

    public function filter(Request $request)
    {
        $query = Admin::query()
            ->with(['provinsi', 'kabupaten', 'kecamatan', 'bidangDampingan']);

        if ($request->provinsi_id) {
            $query->where('provinsi_id', $request->provinsi_id);
        }
        if ($request->kabupaten_id) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }
        if ($request->kecamatan_id) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->bidang_dampingan_id) {
            $query->where('bidang_dampingan_id', $request->bidang_dampingan_id);
        }

        $admins = $query->get()->map(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'alamat' => $admin->alamat,
                'nomor_telepon' => $admin->nomor_telepon,
                'nama_provinsi' => $admin->provinsi->nama ?? '',
                'nama_kabupaten' => $admin->kabupaten->nama ?? '',
                'nama_kecamatan' => $admin->kecamatan->nama ?? '',
            ];
        });

        return response()->json($admins);
    }
}