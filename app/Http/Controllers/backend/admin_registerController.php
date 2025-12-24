<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;

class admin_registerController extends Controller
{
    public function index()
    {
        return view('backend.admin_register.index');
    }
}
