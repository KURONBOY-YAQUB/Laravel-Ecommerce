<?php

namespace App\Http\Livewire\Web\Product;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public $category, $product, $productColorSelectedQuantity;

    public function addToWishlist($productId)
    {
        if (Auth::check())
        {
            if (Wishlist::where('user_id', auth()->user()->id)->where('product_id', $productId)->exists())
            {
                session()->flash('message', 'Already added to wishlist');
                return false;
            }
            else
            {
                Wishlist::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $productId,
                ]);

                session()->flash('message', 'Wishlist Added successfully');
            }
        }
        else
        {
            session()->flash('message', 'Please Login to continue');
            return false;
        }
    }

    public function mount($category, $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    public function colorSelected($productColorId)
    {
        $productColor = $this->product->productColors->where('id', $productColorId)->first();
        $this->productColorSelectedQuantity = $productColor->quantity;

        if ($this->productColorSelectedQuantity == 0) {
            $this->productColorSelectedQuantity = 'outOfStock';
        }
    }

    public function render()
    {
        return view('livewire.web.product.view', [
            'category' => $this->category,
            'product' => $this->product
        ]);
    }
}
