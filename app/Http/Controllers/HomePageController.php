<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSizeColor;
use Illuminate\Support\Facades\DB; // Keep for stock aggregation
// use Illuminate\Support\Str; // Only if needed for slug

class HomePageController extends Controller
{
    /**
     * Display the product listing page (homepage).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = 24;

        $productsQuery = Product::query();

        // Calculate Total Stock
        $stockAggregationSubQuery = ProductSizeColor::select(
                'product_id', DB::raw('SUM(stock) as aggregated_stock')
            )->groupBy('product_id');
        $productsQuery->leftJoinSub(
            $stockAggregationSubQuery, 'stock_summary',
            fn ($join) => $join->on('products.id', '=', 'stock_summary.product_id')
        );
        $productsQuery->select(
            'products.*',
            DB::raw('COALESCE(stock_summary.aggregated_stock, 0) as total_stock')
        );

        // Apply search
        if ($search) {
            $productsQuery->where(fn ($q) => $q->where('name', 'like', "%$search%")->orWhere('description', 'like', "%$search%"));
        }

        // Apply Sorting
        $productsQuery
            ->orderByRaw('CASE WHEN COALESCE(stock_summary.aggregated_stock, 0) > 0 THEN 0 ELSE 1 END ASC')
            ->orderBy('products.created_at', 'desc');

        // Paginate
        $products = $productsQuery->paginate($perPage)->withQueryString();

        return view('homepage', compact('products', 'search'));
    }

    // purchase() method removed
    // options() method removed
}
