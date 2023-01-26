<?php

namespace App\Http\Livewire\Web\Product;

use Livewire\Component;

class Index extends Component
{
    public $products, $category;

    public function mount($products, $category)
    {
        $this->products = $products;
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.web.product.index');
    }
}
