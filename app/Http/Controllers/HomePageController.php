<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with('stockCombinations.size', 'stockCombinations.color')
            ->when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // if ($request->header('Turbo-Frame') === 'products_frame') {
        //     return view('homepage._list', compact('products'));
        // }

        return view('homepage', compact('products'));
    }

}
