<?php

namespace App\Http\Controllers\Admin; // Ensure the namespace is correct

use App\Http\Controllers\Controller; // Import base Controller
use App\Models\User;                // Import User Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // For hashing passwords
use Illuminate\Support\Facades\Auth; // For authentication checks
use Illuminate\Validation\Rules;     // For password validation rules
use Illuminate\Database\Eloquent\Builder; // Import Builder for cleaner search query

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Handles searching and pagination.
     */
    public function index(Request $request) // Inject Request to access query parameters
    {
        $searchTerm = $request->query('search'); // Get search term from request

        // Start building the query
        $query = User::query();

        // Apply search filter if a search term exists
        if ($searchTerm) {
            $query->where(function (Builder $subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', "%{$searchTerm}%")
                         ->orWhere('email', 'like', "%{$searchTerm}%");
            });
            // You could add more searchable fields here if needed
            // ->orWhere('some_other_field', 'like', "%{$searchTerm}%");
        }

        // Order results by name and paginate
        // Use withQueryString() to automatically append existing query parameters (like search) to pagination links
        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        // Pass users data (and optionally the search term, though the view already gets it via request()) to the view
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create'); // Display the create user form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class], // Ensure email is unique in the users table
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // Validate password & confirmation using default rules
            'is_admin' => ['sometimes', 'boolean'], // is_admin is optional, must be boolean (handles checkbox value 0/1 or on/null)
        ]);

        // Create the new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password before saving
            'is_admin' => $request->boolean('is_admin'), // Get boolean value (true if 'is_admin' is present and truthy, false otherwise)
        ]);

        // Redirect to the user index page with a success message
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Often optional for admin panels where 'edit' is sufficient)
     */
    public function show(User $user) // Route model binding automatically finds the User or throws 404
    {
        // You might want to display a detailed view or just redirect to edit
        return view('admin.users.show', compact('user'));
        // Or redirect to edit: return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route model binding
    {
        return view('admin.users.edit', compact('user')); // Display the edit form with the user's data
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Route model binding
    {
        // Validate input data (ignore unique email rule for the current user's email)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id], // Ignore this user's current email for uniqueness check
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password is optional during update; must be confirmed if provided
            'is_admin' => ['sometimes', 'boolean'], // is_admin is optional, must be boolean
        ]);

        // Prepare data for update
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            // Only update is_admin if it was included in the request
            // Using boolean() handles checkbox value correctly
            'is_admin' => $request->has('is_admin') ? $request->boolean('is_admin') : $user->is_admin,
        ];

        // If a new password was provided, hash it and add it to the update data
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // Update the user's data
        $user->update($dataToUpdate);

        // Redirect to the user index page with a success message
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // Route model binding
    {
        // Prevent users from deleting their own account
        // Use Auth::id() which is slightly more direct than checking Auth::check() first
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                       ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Delete the user
        $user->delete();

        // Redirect to the user index page with a success message
        return redirect()->route('admin.users.index')
                   ->with('success', 'User berhasil dihapus.');
    }
}
