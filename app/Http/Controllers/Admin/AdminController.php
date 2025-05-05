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

        // 1. Total Products
        $totalProducts = Product::count();

        // 2. New Orders in the last 24 hours
        // We now count *Orders*, not individual transaction items, for "new orders" stat.
        $newOrdersCount = Order::where('created_at', '>=', Carbon::now()->subDay())->count();

        // 3. Total Customers
        $totalCustomers = User::count(); // Counts all registered users

        // 4. Revenue This Month
        // Revenue is calculated from the *total_amount* in the *orders* table,
        // filtering by a successful payment status (e.g., 'settlement' or 'paid').
        // The 'status' column is now in the 'orders' table, not 'transactions'.
        // *** ASSUMPTION: A successfully paid order has payment_status = 'settlement' ***
        // *** Adjust the 'settlement' string below if your order status logic uses a different value (e.g., 'paid') ***
        $revenueThisMonth = Order::where('payment_status', 'settlement') // Filter by successful payment status in Orders table
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount'); // Sum the 'total_amount' from the Orders table

        // Format the revenue into a more readable string
        $formattedRevenue = 'Rp ' . number_format($revenueThisMonth / 1000000, 1, ',', '.') . 'jt';
        // Or full format: $formattedRevenue = 'Rp ' . number_format($revenueThisMonth, 0, ',', '.');


        // --- Fetch Recent Activity Data ---

        // Example: Get the 5 most recent orders (formerly called transactions in the old schema context)
        // We now fetch *Order* models, eager loading the 'user' relationship.
        $recentOrders = Order::with('user') // 'user' relation exists on the Order model
            ->latest() // Order by created_at descending (newest first)
            ->take(5)  // Limit the results
            ->get();

        // You could add more activity types here if needed:
        $recentUsers = User::latest()->take(3)->get();
        // $recentProductUpdates = Product::latest('updated_at')->take(3)->get();


        // --- Pass Data to the View ---

        // Return the admin dashboard view and pass the fetched data as variables
        return view('admin.index', [
            'totalProducts'     => $totalProducts,
            'newOrdersCount'    => $newOrdersCount,
            'totalCustomers'    => $totalCustomers,
            'formattedRevenue'  => $formattedRevenue,    // Pass the pre-formatted string
            'recentTransactions' => $recentOrders,      // Pass the collection of recent Orders, maybe rename variable in view?
            'recentUsers' => $recentUsers,           // Pass other activity data if you fetched it
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
