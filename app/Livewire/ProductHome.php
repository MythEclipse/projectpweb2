<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ProductHome extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')->paginate(12);
        return view('livewire.product-home', compact('products'));
    }
}

