<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class profit_reportController extends Controller
{
    public function index()
    {
        return view('backend.profit_report.index');
    }
}
