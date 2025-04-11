<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class DataFasilitatorController extends Controller
{
    public function index() {
        $fasilitators = User::where('users.role', ['fasilitator'])
        ->get();

        return Inertia::render('admin/DataFasilitatorView', [
            'data' => $fasilitators
        ]);
    }

    public function create(){
        return inertia('admin/FormFasilitatorView');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil ditambahkan!');
    }

    public function edit($id){
        $fasilitator = User::findOrFail($id);

        return inertia('admin/FormFasilitatorView', [
            'fasilitator' => [
                'id' => $fasilitator->id,
                'name' => $fasilitator->name,
                'email' => $fasilitator->email,
                'nomor_telepon' => $fasilitator->nomor_telepon,
                'alamat' => $fasilitator->alamat,
            ],
        ]);
    }

    public function update(Request $request, $id){
        $fasilitator = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        $fasilitator->update([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil diperbarui!');
    }

    public function destroy($id){
        $fasilitator = User::findOrFail($id);
        $fasilitator->delete();

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil dihapus!');
    }
}
