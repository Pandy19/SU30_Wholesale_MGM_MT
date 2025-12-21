<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class supplier_returnsController extends Controller
{
    public function index()
    {
        return view('backend.supplier_returns.index');
    }
}
