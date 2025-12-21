<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class sale_reportController extends Controller
{
    public function index()
    {
        return view('backend.sale_report.index');
    }
}
