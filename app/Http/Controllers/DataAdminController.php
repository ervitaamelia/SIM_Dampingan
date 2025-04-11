<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class DataAdminController extends Controller
{
    public function index()
    {
        $admins = User::select(
            'users.*',
            'provinsis.nama as nama_provinsi',
            'kabupatens.nama as nama_kabupaten',
            'kecamatans.nama as nama_kecamatan'
        )
            ->leftJoin('provinsis', 'users.kode_provinsi', '=', 'provinsis.kode')
            ->leftJoin('kabupatens', 'users.kode_kabupaten', '=', 'kabupatens.kode')
            ->leftJoin('kecamatans', 'users.kode_kecamatan', '=', 'kecamatans.kode')
            ->whereIn('users.role', ['superadmin', 'admin'])
            ->get();

        return Inertia::render('admin/DataAdminView', [
            'data' => $admins
        ]);
    }

    public function create()
    {
        return inertia('admin/FormAdminView', [
            'provinsis' => Provinsi::all(['kode', 'nama']),
            'kabupatens' => Kabupaten::all(['kode', 'nama', 'kode_provinsi']),
            'kecamatans' => Kecamatan::all(['kode', 'nama', 'kode_kabupaten']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
            'role' => 'required|in:admin,superadmin',
            'kode_provinsi' => 'nullable|string',
            'kode_kabupaten' => 'nullable|string',
            'kode_kecamatan' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'kode_provinsi' => $request->kode_provinsi,
            'kode_kabupaten' => $request->kode_kabupaten,
            'kode_kecamatan' => $request->kode_kecamatan,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);

        return inertia('admin/FormAdminView', [
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'nomor_telepon' => $admin->nomor_telepon,
                'alamat' => $admin->alamat,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus!');
    }
}
