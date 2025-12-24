<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class admin_loginController extends Controller
{
    public function index()
    {
        return view('backend.admin_login.index');
    }
}
