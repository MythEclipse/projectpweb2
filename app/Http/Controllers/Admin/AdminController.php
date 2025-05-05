<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;        // Import the Product model
use App\Models\Transaction;   // Import the Transaction model (representing orders)
use App\Models\User;          // Import the User model (representing customers)
use Carbon\Carbon;            // Import Carbon for date manipulation
use App\Models\Order;         // Import the Order model (if using a different name for transactions)

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with dynamic data.
     * This method fetches summary data and recent activity.
     */
    public function index(Request $request)
    {
        // --- Fetch Quick Stats Data ---

        // 1. Total Products (still the same)
        $totalProducts = Product::count();

        // 2. New Orders in the last 24 hours
        // Now count from the 'orders' table
        $newOrdersCount = Order::where('created_at', '>=', Carbon::now()->subDay())->count();

        // 3. Total Customers (assuming User model represents customers)
        $totalCustomers = User::count();

        // 4. Revenue This Month
        // Revenue is calculated from 'total_amount' in the 'orders' table
        // Filter by a status indicating successful payment (e.g., 'settlement')
        // ADJUST 'settlement' if your Order model uses a different status for completed payments.
        $revenueThisMonth = Order::where('payment_status', 'settlement') // Assuming 'settlement' means paid
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount'); // Sum the 'total_amount' from the Orders table

        // Format the revenue
        // Keep the original formatting if that's desired, or use full format
        // Example 1: Rp X.Xjt format for millions
        if ($revenueThisMonth >= 1000000) {
             $formattedRevenue = 'Rp ' . number_format($revenueThisMonth / 1000000, 1, ',', '.') . 'jt';
        } else {
             // Example 2: Full format for smaller amounts
             $formattedRevenue = 'Rp ' . number_format($revenueThisMonth, 0, ',', '.');
        }


        // --- Fetch Recent Activity Data ---

        // Example: Get the 5 most recent orders (from the 'orders' table)
        // Eager load the 'user' relation as user_id is on the Order model
        $recentOrders = Order::with('user')
            ->latest() // Order by created_at descending (newest first)
            ->take(5)  // Limit the results
            ->get();


        // --- Pass Data to the View ---

        // Return the admin dashboard view and pass the fetched data as variables
        // We'll pass the recent orders using a variable name that reflects they are orders.
        return view('admin.index', [
            'totalProducts'     => $totalProducts,
            'newOrdersCount'    => $newOrdersCount,
            'totalCustomers'    => $totalCustomers,
            'formattedRevenue'  => $formattedRevenue,
            'recentOrders'      => $recentOrders, // Use a variable name like recentOrders
        ]);
    }

    // --- Placeholder Methods for other Admin Actions ---
    // You would create separate controllers (ProductController, OrderController, etc.)
    // for the actual management pages linked from the dashboard.
    // Including them here just shows they wouldn't typically live in the main AdminController.

    // public function showProducts() { /* ... */ }
    // public function createProduct() { /* ... */ }
    // public function showOrders() { /* ... */ }
    // public function showUsers() { /* ... */ }
    // public function showSettings() { /* ... */ }
    // public function showActivityLog() { /* ... */ }
    // public function showReports() { /* ... */ }

}
