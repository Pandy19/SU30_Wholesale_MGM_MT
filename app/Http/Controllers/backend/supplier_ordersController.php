<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class supplier_ordersController extends Controller
{
    public function index()
    {
        return view('backend.supplier_orders.index');
    }
}
