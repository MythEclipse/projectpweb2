<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $products = Product::where('name', 'like', '%'.$this->search.'%')->paginate(10);
        return view('livewire.product-index', compact('products'));
    }
}
