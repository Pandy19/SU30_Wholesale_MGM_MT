<?php

namespace App\Http\Controllers;

class SupplierProductDemoController extends Controller
{
    public function index()
    {
        return view('backend.supplier_products.demo'); // your no-DB page
    }
}
