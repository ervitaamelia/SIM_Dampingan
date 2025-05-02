<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BidangController extends Controller
{
    /**
     * Tampil daftar semua Bidang
     *
     * @return \\Illuminate\\Http\\JsonResponse
     */
    public function index()
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return response()->json([
            'data' => $bidangs
        ], Response::HTTP_OK);
    }

    /**
     * Simpan Bidang baru ke tabel bidang
     *
     * @param  \\Illuminate\\Http\\Request  $request
     * @return \\Illuminate\\Http\\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bidang' => 'required|string|max:255|unique:bidang,nama_bidang',
        ]);

        $bidang = Bidang::create([
            'nama_bidang' => $validated['nama_bidang'],
        ]);

        return response()->json([
            'message' => 'Bidang berhasil ditambahkan',
            'data' => $bidang
        ], Response::HTTP_CREATED);
    }

    /**
     * Hapus Bidang berdasarkan ID
     *
     * @param  int  $id
     * @return \\Illuminate\\Http\\JsonResponse
     */
    public function destroy($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return response()->json([
            'message' => 'Bidang berhasil dihapus'
        ], Response::HTTP_OK);
    }
}