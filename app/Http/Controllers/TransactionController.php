<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['product', 'size', 'color', 'user'])->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create', [
            'products' => Product::all(),
            'sizes' => Size::all(),
            'colors' => Color::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'size_id' => 'required',
            'color_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $total = $request->quantity * $request->price;

        Transaction::create([
            'product_id' => $request->product_id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $total,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }
}
