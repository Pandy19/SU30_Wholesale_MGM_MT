<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class goods_receivingController extends Controller
{
    public function index()
    {
        return view('backend.goods_receiving.index');
    }
}
