<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bidang;
use App\Models\GrupDampingan;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataFasilitatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = GrupDampingan::query();

        if ($user->role === 'admin-provinsi') {
            $query->where('grup_dampingan.kode_provinsi', $user->kode_provinsi);
        } elseif ($user->role === 'admin-kabupaten') {
            $query->where('grup_dampingan.kode_kabupaten', $user->kode_kabupaten);
        } elseif ($user->role === 'admin-kecamatan') {
            $query->where('grup_dampingan.kode_kecamatan', $user->kode_kecamatan);
        }

        $grups = $query->get();

        $grupIds = $grups->pluck('id_grup_dampingan');

        $fasilitators = User::where('users.role', 'fasilitator')
            ->where(function ($query) use ($grupIds) {
                $query->whereHas('grupDampingan', function ($subQuery) use ($grupIds) {
                    $subQuery->whereIn('grup_dampingan.id_grup_dampingan', $grupIds);
                })
                    ->orWhereDoesntHave('grupDampingan');
            })
            ->with('bidangs', 'grupDampingan')
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('admin/DataFasilitatorView', [
            'data' => $fasilitators
        ]);
    }

    public function create()
    {
        $bidangs = Bidang::all(['id_bidang', 'nama_bidang']);
        return inertia('admin/FormFasilitatorView', [
            'bidangs' => $bidangs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|regex:/^\S*$/u|unique:users,username',
            'password' => 'required|min:8',
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image',
            'bidang_dampingan' => 'required|array|min:1',
            'bidang_dampingan.*' => 'exists:bidang,id_bidang',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'foto' => $fotoPath ?? null,
        ]);

        // Simpan bidang dampingannya (relasi many-to-many)
        $user->bidangs()->attach($request->bidang_dampingan);

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $fasilitator = User::with('bidangs')->findOrFail($id);
        $bidangs = Bidang::all(['id_bidang', 'nama_bidang']);

        return inertia('admin/FormFasilitatorView', [
            'fasilitator' => [
                'id' => $fasilitator->id,
                'name' => $fasilitator->name,
                'username' => $fasilitator->username,
                'nomor_telepon' => $fasilitator->nomor_telepon,
                'alamat' => $fasilitator->alamat,
                'foto' => $fasilitator->foto,
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
            'username' => 'required|string|regex:/^\S*$/u|unique:users,username,' . $id,
            'nomor_telepon' => 'required|max:15',
            'alamat' => 'required|string|max:255',
            'bidang_dampingan' => 'required|array|min:1',
            'bidang_dampingan.*' => 'exists:bidang,id_bidang',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['name', 'username', 'nomor_telepon', 'alamat']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($fasilitator->foto && Storage::disk('public')->exists($fasilitator->foto)) {
                Storage::disk('public')->delete($fasilitator->foto);
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('foto', 'public');
            $data['foto'] = $fotoPath;
        }

        $fasilitator->update($data);

        $fasilitator->bidangs()->sync($request->bidang_dampingan);

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $fasilitator = User::findOrFail($id);

        // Hapus file foto jika ada
        if ($fasilitator->foto && Storage::disk('public')->exists($fasilitator->foto)) {
            Storage::disk('public')->delete($fasilitator->foto);
        }

        // Hapus relasi terlebih dahulu
        $fasilitator->bidangs()->detach();
        $fasilitator->grupDampingan()->detach();

        $fasilitator->delete();

        return redirect()->route('fasilitator.index')->with('success', 'Fasilitator berhasil dihapus!');
    }

    public function resetPassword($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses tidak diizinkan');
        }

        $fasilitator = User::findOrFail($id);

        $fasilitator->password = Hash::make('12345678');
        $fasilitator->must_change_password = true;
        $fasilitator->save();

        return redirect()->route('fasilitator.index')->with('success', 'Password berhasil direset ke default');
    }

    /**
     * @OA\Get(
     *  path="/api/check-username",
     *  tags={"User"},
     *  summary="Cek apakah username sudah digunakan",
     *  description="Mengecek apakah username sudah ada di database. Digunakan saat create dan edit, dengan pengecualian berdasarkan ID.",
     *  operationId="checkUsername",
     *  @OA\Parameter(
     *      name="username",
     *      in="query",
     *      required=true,
     *      description="Username yang ingin dicek",
     *      @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=false,
     *      description="ID user untuk pengecualian saat edit (opsional)",
     *      @OA\Schema(type="integer")
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Hasil pengecekan apakah username sudah digunakan",
     *      @OA\JsonContent(
     *          @OA\Property(property="exists", type="boolean", example=true)
     *      )
     *  )
     * )
     */
    public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        $id = $request->query('id'); // untuk pengecualian saat edit

        $query = User::where('username', $username);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }
}