<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitator;
use App\Models\Bidang;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\User;

class FasilitatorController extends Controller
{

public function index(Request $request)
{
    $query = User::role('fasilitator')->with(['bidangs']);

    if ($request->has('bidang') && $request->bidang) {
        $query->whereHas('bidangs', function ($q) use ($request) {
            $q->where('id_bidang', $request->bidang);
        });
    }

    return Inertia::render('Admin/Fasilitator/Index', [
        'fasilitators' => $query->get(),
        'selectedBidang' => $request->bidang,
        'dampinganList' => Bidang::all()->map(function ($bidang) {
            return [
                'value' => $bidang->id_bidang,
                'label' => $bidang->nama_bidang,
            ];
        }),
    ]);
}
}