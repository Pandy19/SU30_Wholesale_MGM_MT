<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\StockLocation;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShelfControlController extends Controller
{
    public function index()
    {
        $shelves = StockLocation::with(['brand', 'category', 'stocks.product'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($shelf) {
                $shelf->total_quantity = $shelf->stocks->sum('quantity');
                return $shelf;
            });

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.shelf_control.index', compact('shelves', 'brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $brand = Brand::findOrFail($request->brand_id);
        $category = Category::findOrFail($request->category_id);

        $baseName = "{$category->name} {$brand->name} Shelf A";
        
        $existingShelves = StockLocation::where('category_id', $category->id)
            ->where('brand_id', $brand->id)
            ->where('name', 'like', "{$baseName}%")
            ->get();

        $maxNumber = 0;
        foreach ($existingShelves as $shelf) {
            $numberPart = str_replace($baseName, '', $shelf->name);
            if (is_numeric($numberPart)) {
                $maxNumber = max($maxNumber, (int)$numberPart);
            }
        }

        $nextNumber = $maxNumber + 1;
        $newName = $baseName . $nextNumber;
        
        $newCode = strtoupper(substr($category->name, 0, 1) . substr($brand->name, 0, 1)) . '-A' . $nextNumber;

        StockLocation::create([
            'name' => $newName,
            'code' => $newCode,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'max_capacity' => 50,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', "Shelf {$newName} created successfully.");
    }

    public function destroy($id)
    {
        $shelf = StockLocation::findOrFail($id);
        
        // Instead of deleting, we turn it to inactive
        $shelf->update(['status' => 'inactive']);

        return redirect()->back()->with('success', "Shelf {$shelf->name} has been set to inactive.");
    }
}
