<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class stock_ledgerController extends Controller
{
    public function index()
    {
        return view('backend.stock_ledger.index');
    }
}
