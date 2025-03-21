<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class DataAdminController extends Controller
{
    public function index() {
        $admins = User::whereIn('role', ['superadmin', 'admin'])
        ->get();

        return Inertia::render('admin/DataAdminView', [
            'data' => $admins
        ]);
    }
}
