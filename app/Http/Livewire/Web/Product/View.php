<?php

namespace App\Http\Livewire\Web\Product;

use App\Models\Cart;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public $category, $product, $productColorSelectedQuantity, $productQuantity = 1, $productColorId;

    public function addToWishlist($productId)
    {
        if (Auth::check())
        {
            if (Wishlist::where('user_id', auth()->user()->id)->where('product_id', $productId)->exists())
            {
                session()->flash('message', 'Already added to wishlist');
                $this->dispatchBrowserEvent('message', [
                    'text' => 'Already added to wishlist',
                    'type' => 'warning',
                    'status' => 409
                ]);
                return false;
            }
            else
            {
                Wishlist::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $productId,
                ]);

                $this->emit('wishListAddedOrUpdated');

                session()->flash('message', 'Wishlist Added successfully');
                $this->dispatchBrowserEvent('message', [
                    'text' => 'Wishlist Added successfully',
                    'type' => 'success',
                    'status' => 200
                ]);
            }
        }
        else
        {
            session()->flash('message', 'Please Login to continue');
            $this->dispatchBrowserEvent('message', [
                'text' => 'Please Login to continue',
                'type' => 'info',
                'status' => 401
            ]);
            return false;
        }
    }

    public function colorSelected($productColorId)
    {
        $this->productColorId = $productColorId;
        $productColor = $this->product->productColors->where('id', $productColorId)->first();
        $this->productColorSelectedQuantity = $productColor->quantity;

        if ($this->productColorSelectedQuantity == 0) {
            $this->productColorSelectedQuantity = 'outOfStock';
        }
    }

    public function incrementQuantity()
    {
        if ($this->productQuantity < 10) {
            $this->productQuantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->productQuantity > 1) {
            $this->productQuantity--;
        }
    }

    public function addToCart(int $product_id)
    {
        if (Auth::check())
        {
            if ($this->product->where('id', $product_id)->where('status', '0')->exists())
            {
                // Check for Product color quantity and to cart
                if ($this->product->productColors()->count() > 1)
                {
                    if ($this->productColorSelectedQuantity != NULL)
                    {
                        if (Cart::where('user_id', auth()->user()->id)
                                    ->where('product_id', $product_id)
                                    ->where('product_color_id', $productColorId)->exists())
                        {
                            $this->dispatchBrowserEvent('message', [
                                'text' => 'Product Already Added',
                                'type' => 'warning',
                                'status' => 200
                            ]);
                        }
                        else
                        {
                        $productColor = $this->product->productColors()
                                    ->where('id', $this->productColorId)
                                    ->first();
                        if ($productColor->quantity > 0)
                        {
                            if ($productColor->quantity > $this->productQuantity)
                            {
                                // Insert Product to Cart with Colors
                                Cart::create([
                                    'user_id' => auth()->user()->id,
                                    'product_id' => $product_id,
                                    'product_color_id' => $this->productColorId,
                                    'quantity' => $this->productQuantity
                                ]);
                                $this->dispatchBrowserEvent('message', [
                                    'text' => 'Product Added to Cart',
                                    'type' => 'success',
                                    'status' => 200
                                ]);
                            }
                            else
                            {
                                $this->dispatchBrowserEvent('message', [
                                    'text' => 'Only '.$productColor->quantity.' Quantity Available',
                                    'type' => 'warning',
                                    'status' => 404
                                ]);
                                return false;
                            }
                        }
                        else
                        {
                            $this->dispatchBrowserEvent('message', [
                                'text' => 'Out of Stock',
                                'type' => 'warning',
                                'status' => 404
                            ]);
                            return false;
                        }
                    }
                }
                    else
                    {
                        $this->dispatchBrowserEvent('message', [
                            'text' => 'Select Your Product Color',
                            'type' => 'info',
                            'status' => 404
                        ]);
                        return false;
                    }
                }
                else
                {
                    if (Cart::where('user_id', auth()->user()->id)->where('product_id', $product_id)->exists())
                    {
                        $this->dispatchBrowserEvent('message', [
                            'text' => 'Product Already Added',
                            'type' => 'warning',
                            'status' => 200
                        ]);
                    }
                    else
                    {

                        if ($this->product->quantity > 0)
                        {
                            if ($this->product->quantity > $this->productQuantity)
                            {
                                // Insert product to cart
                                Cart::create([
                                    'user_id' => auth()->user()->id,
                                    'product_id' => $product_id,
                                    'quantity' => $this->productQuantity
                                ]);
                                $this->dispatchBrowserEvent('message', [
                                    'text' => 'Product Added to Cart',
                                    'type' => 'success',
                                    'status' => 200
                                ]);
                            }
                            else
                            {
                                $this->dispatchBrowserEvent('message', [
                                    'text' => 'Only '.$this->product->quantity.' Quantity Available',
                                    'type' => 'warning',
                                    'status' => 404
                                ]);
                                return false;
                            }
                        }
                        else
                        {
                            $this->dispatchBrowserEvent('message', [
                                'text' => 'Out of Stock',
                                'type' => 'warning',
                                'status' => 404
                            ]);
                            return false;
                        }
                    }
                }
            }


            else
            {
                $this->dispatchBrowserEvent('message', [
                    'text' => 'Product Does not exists',
                    'type' => 'warning',
                    'status' => 404
                ]);
                return false;
            }
        }
        else
        {
            $this->dispatchBrowserEvent('message', [
                'text' => 'Please Login to add to cart',
                'type' => 'info',
                'status' => 401
            ]);
            return false;
        }
    }

    public function mount($category, $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.web.product.view', [
            'category' => $this->category,
            'product' => $this->product
        ]);
    }
}
