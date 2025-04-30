<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;        // Import the Product model
use App\Models\Transaction;   // Import the Transaction model (representing orders)
use App\Models\User;          // Import the User model (representing customers)
use Carbon\Carbon;            // Import Carbon for date manipulation
use Illuminate\Support\Facades\Auth; // Import Auth facade if needed directly (though Auth::user() works)
use Illuminate\Support\Facades\DB;   // Optional: If needed for more complex queries

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

        // 2. New Orders (Transactions) in the last 24 hours
        $newOrdersCount = Transaction::where('created_at', '>=', Carbon::now()->subDay())->count();

        // 3. Total Customers
        // Adjust the query if you only want to count users with a specific role, e.g., 'customer'
        // $totalCustomers = User::where('role', 'customer')->count();
        $totalCustomers = User::count(); // Counts all registered users

        // 4. Revenue This Month
        // Sums the 'total' column for transactions marked as 'paid' within the current calendar month.
        $revenueThisMonth = Transaction::where('payment_status', 'paid') // Or your equivalent status for a completed/paid order
                                       ->whereMonth('created_at', Carbon::now()->month)
                                       ->whereYear('created_at', Carbon::now()->year)
                                       ->sum('total'); // Sum the 'total' field from your transactions table

        // Format the revenue into a more readable string (e.g., "Rp 15.7jt" or "Rp 15.700.000")
        // Option 1: Format as 'jt' (juta/million)
        $formattedRevenue = 'Rp ' . number_format($revenueThisMonth / 1000000, 1, ',', '.') . 'jt';
        // Option 2: Format with full thousands separators
        // $formattedRevenue = 'Rp ' . number_format($revenueThisMonth, 0, ',', '.');


        // --- Fetch Recent Activity Data ---

        // Example: Get the 5 most recent transactions (orders)
        // Eager load the 'user' relationship to avoid N+1 query issues in the view
        $recentTransactions = Transaction::with('user')
                                           ->latest() // Order by created_at descending (newest first)
                                           ->take(5)  // Limit the results
                                           ->get();

        // You could add more activity types here if needed:
        // $recentUsers = User::latest()->take(3)->get();
        // $recentProductUpdates = Product::latest('updated_at')->take(3)->get();


        // --- Pass Data to the View ---

        // Return the admin dashboard view and pass the fetched data as variables
        return view('admin.index', [
            'totalProducts'     => $totalProducts,
            'newOrdersCount'    => $newOrdersCount,
            'totalCustomers'    => $totalCustomers,
            'formattedRevenue'  => $formattedRevenue,    // Pass the pre-formatted string
            'recentTransactions'=> $recentTransactions, // Pass the collection of recent transactions
            // 'recentUsers' => $recentUsers,           // Pass other activity data if you fetched it
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
