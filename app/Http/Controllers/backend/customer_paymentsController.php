<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class customer_paymentsController extends Controller
{
    public function index()
    {
        return view('backend.customer_payments.index');
    }
}
