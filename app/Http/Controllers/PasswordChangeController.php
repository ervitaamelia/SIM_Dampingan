<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordChangeController extends Controller
{
    public function edit()
    {
        return Inertia::render('Auth/UbahPassword');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        // Arahkan ke dashboard sesuai role
        if ($user->role === 'fasilitator') {
            return redirect()->route('fasilitator.dashboard')->with('success', 'Password berhasil diubah');
        }

        if (in_array($user->role, ['superadmin', 'admin-provinsi', 'admin-kabupaten', 'admin-kecamatan'])) {
            return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diubah');
        }

        // Fallback (jika tidak punya role dikenali)
        Auth::logout();
        return redirect()->route('login')->withErrors(['role' => 'Role tidak dikenali.']);
    }
}