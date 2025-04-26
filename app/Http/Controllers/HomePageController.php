<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Ambil data produk dengan pencarian jika ada
        $products = \App\Models\Product::with('sizes')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(24)
            ->withQueryString(); // Pertahankan query string seperti search di URL

        // Mengecek jika request datang dari Turbo Frame
        // if ($request->header('Turbo-Frame') === 'products_frame') {
        //     return view('admin.home._list', compact('products')); // Harus punya tag turbo-frame!
        // }

        // Mengembalikan halaman lengkap jika tidak menggunakan Turbo Frame
        return view('homepage', compact('products'));
    }
}
