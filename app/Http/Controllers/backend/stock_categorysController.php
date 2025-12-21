<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class stock_categorysController extends Controller
{
    public function index()
    {
        return view('backend.stock_categorys.index');
    }
}
