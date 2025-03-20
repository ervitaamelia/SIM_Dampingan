<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DataFasilitatorController extends Controller
{
    public function index() {
        return Inertia::render('admin/DataFasilitatorView');
    }
}
