<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class approved_good_stockController extends Controller
{
    public function index()
    {
        return view('backend.approved_good_stock.index');
    }
}
