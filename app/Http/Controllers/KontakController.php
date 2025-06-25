<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontakController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'nomor_telepon' => 'required|string|max:15',
        ]);

        DB::table('kontak')->truncate();
        DB::table('kontak')->insert([
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        return back()->with('success', 'Nomor telepon berhasil disimpan.');
    }
}
