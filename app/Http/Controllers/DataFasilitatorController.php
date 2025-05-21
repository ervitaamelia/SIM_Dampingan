<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bidang;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class DataFasilitatorController extends Controller
{
    public function index()
    {
        $fasilitators = User::where('users.role', 'fasilitator')
            ->with('bidangs', 'grupDampingan')
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('admin/DataFasilitatorView', [
            'data' => $fasilitators
        ]);
    }

    public function create()
    {
        $bidangs = Bidang::all(['id_bidang as id', 'nama_bidang as nama']);
        return inertia('admin/FormFasilitatorView', [
            'bidangs' => $bidangs,
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
            'bidang_dampingan' => 'required|array|min:1',
            'bidang_dampingan.*' => 'exists:bidang,id_bidang',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
        ]);

        // Simpan bidang dampingannya (relasi many-to-many)
        $user->bidangs()->attach($request->bidang_dampingan);

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $fasilitator = User::with('bidangs')->findOrFail($id);
        $bidangs = Bidang::all(['id_bidang as id', 'nama_bidang as nama']);

        return inertia('admin/FormFasilitatorView', [
            'fasilitator' => [
                'id' => $fasilitator->id,
                'name' => $fasilitator->name,
                'email' => $fasilitator->email,
                'nomor_telepon' => $fasilitator->nomor_telepon,
                'alamat' => $fasilitator->alamat,
                'bidang_dampingan' => $fasilitator->bidangs->pluck('id_bidang')->map(fn($id) => (string) $id),
            ],
            'bidangs' => $bidangs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $fasilitator = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
            'bidang_dampingan' => 'required|array|min:1',
            'bidang_dampingan.*' => 'exists:bidang,id_bidang',
        ]);

        $fasilitator->update([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
        ]);

        // Sinkronisasi bidang dampingan
        $fasilitator->bidangs()->sync($request->bidang_dampingan);

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $fasilitator = User::findOrFail($id);

        // Hapus relasi bidang terlebih dahulu
        $fasilitator->bidangs()->detach();
        $fasilitator->delete();

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil dihapus!');
    }
}
