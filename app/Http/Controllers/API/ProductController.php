<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shop\ProductVariant;

class ProductController extends Controller
{
    public function index()
    {
        $searchTerm = request()->query('search');

        $products = ProductVariant::limit(20);

        if ($searchTerm) {
            $products = $products->where('name', 'like', '%' . $searchTerm . '%');
        }

        return response()->json($products->get());
    }
}
