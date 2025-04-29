<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Show the transaction management page.
     */
    public function index(Request $request)
    {
        return view('admin.home');
    }
}
