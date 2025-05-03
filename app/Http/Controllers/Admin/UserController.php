<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace benar

use App\Http\Controllers\Controller; // Import Controller dasar
use App\Models\User; // Import Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Support\Facades\Auth; // Untuk autentikasi
use Illuminate\Validation\Rules; // Untuk aturan validasi password

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user, urutkan berdasarkan nama, dan paginasi
        $users = User::orderBy('name')->paginate(10); // Angka 10 bisa disesuaikan
        return view('admin.users.index', compact('users')); // Kirim data ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create'); // Tampilkan form tambah user
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class], // Pastikan email unik
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // Validasi password + konfirmasi
            'is_admin' => ['sometimes', 'boolean'], // is_admin opsional, harus boolean
        ]);

        // Buat user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin'), // Ambil nilai boolean dari checkbox/select
        ]);

        // Redirect ke halaman index user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Opsional untuk admin, biasanya langsung ke edit)
     */
    public function show(User $user) // Route model binding
    {
        return view('admin.users.show', compact('user'));
        // Atau redirect ke edit: return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route model binding
    {
        return view('admin.users.edit', compact('user')); // Tampilkan form edit dengan data user
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Route model binding
    {
        // Validasi input (email unik diabaikan untuk user saat ini)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class . ',email,' . $user->id], // Abaikan email user ini
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password opsional saat update
            'is_admin' => ['sometimes', 'boolean'],
        ]);

        // Siapkan data update
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->boolean('is_admin'),
        ];

        // Jika password diisi, hash dan tambahkan ke data update
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // Update data user
        $user->update($dataToUpdate);

        // Redirect ke halaman index user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::check() && Auth::user()->id == $user->id) {
            if (Auth::check() && Auth::user()->id == $user->id) {
                return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            // Hapus user
            $user->delete();

            // Redirect ke halaman index user dengan pesan sukses
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        }
    }
}
