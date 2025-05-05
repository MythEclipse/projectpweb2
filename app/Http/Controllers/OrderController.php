<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login
use App\Models\Transaction; // Pastikan model Transaction di-import
use App\Models\Order; // Import the Order model
use App\Models\Product; // Import the Product model
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class OrderController extends Controller
{
    public function index(): View
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Retrieve only the ORDERS belonging to this user
        // Use the Order model instead of Transaction
        // Eager load the 'transactionItems' relationship, and then the relationships *on* the items
        // 'transactionItems.product' -> Load the product for each item
        // 'transactionItems.size'    -> Load the size for each item
        // 'transactionItems.color'   -> Load the color for each item
        $orders = Order::where('user_id', $user->id) // Filter by user_id on the ORDERS table
                             ->with([
                                'transactionItems.product',
                                'transactionItems.size',
                                'transactionItems.color'
                             ]) // Eager load items and their details
                             ->latest() // Order the orders by their creation date descending
                             ->paginate(10); // Paginate the results

        // Pass the collection of Order models to the view
        // The view (orders.index) will now receive a collection of Orders,
        // and for each Order, it can access its items via $order->transactionItems
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order details for the authenticated user.
     * This fetches a single Order and its items.
     *
     * @param  \App\Models\Order  $order The Order model instance (Route Model Binding)
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Order $order): View | RedirectResponse
    {
        $user = Auth::user();

        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $user->id) {
            // Use abort(403) for forbidden access, or redirect
             abort(403, 'Unauthorized access.');
            // return redirect()->route('orders.index')->with('error', 'Order tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Eager load the transaction items and their details for this specific order
        $order->loadMissing([
            'transactionItems.product',
            'transactionItems.size',
            'transactionItems.color'
        ]);

        // Pass the single Order model (with items loaded) to the view
        return view('orders.show', compact('order'));
    }
}
