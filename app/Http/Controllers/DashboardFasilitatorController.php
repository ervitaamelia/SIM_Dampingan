<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardFasilitatorController extends Controller
{
    public function index() {
        return Inertia::render('fasilitator/Dashboard');
    }
}
