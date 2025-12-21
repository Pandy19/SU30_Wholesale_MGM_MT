<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class product_stock_listController extends Controller
{
    public function index()
    {
        return view('backend.product_stock_list.index');
    }
}
