<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class purchase_ordersController extends Controller
{
    public function index()
    {
        return view('backend.purchase_orders.index');
    }
}