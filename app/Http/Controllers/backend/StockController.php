<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function addApproved(Request $request)
    {
        $request->validate([
            'sku' => 'required|string',
            'po' => 'required|string',
            'qty' => 'required|integer|min:1',
            'location' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // TODO: stock insert logic here

        return response()->json([
            'status' => 'success',
            'message' => 'Added to stock successfully'
        ]);
    }
}
