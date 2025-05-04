<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login
use App\Models\Transaction; // Pastikan model Transaction di-import

class OrderController extends Controller
{
       /**
     * Display a listing of the authenticated user's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Retrieve only the transactions belonging to this user
        // Eager load relationships for efficiency
        // Order by the most recent first
        $orders = Transaction::where('user_id', $user->id)
                             ->with(['product', 'size', 'color']) // Eager load product, size, color details
                             ->latest() // Order by created_at descending
                             ->paginate(10); // Paginate the results (e.g., 10 per page)

        // Pass the orders to the view
        return view('orders.index', compact('orders'));
    }

    // Add other methods like show($orderId) if you need a dedicated detail page later
    /**
     * Display the specified order for the authenticated user.
     * Optional: If you want a dedicated detail page per order.
     *
     * @param  int  $orderId The ID of the transaction/order.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
     /*
    public function show($orderId): View | RedirectResponse
    {
        $user = Auth::user();
        $order = Transaction::where('user_id', $user->id)
                            ->where('id', $orderId)
                            ->with(['product', 'size', 'color']) // Load details
                            ->first(); // Find the specific order

        // If order not found or doesn't belong to the user
        if (!$order) {
            // Redirect back with an error or to the orders index
            return redirect()->route('orders.index')->with('error', 'Order tidak ditemukan.');
        }

        return view('orders.show', compact('order'));
    }
    */
}
