<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class sales_order_historyController extends Controller
{
    public function index()
    {
        return view('backend.sales_order_history.index');
    }
}