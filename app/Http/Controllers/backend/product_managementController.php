<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class product_managementController extends Controller
{
    public function index()
    {
        return view('backend.product_management.index');
    }
}
