<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class supplier_dashboardController extends Controller
{
    public function index()
    {
        return view('backend.Supplier_Dashboard.index');
    }
}
