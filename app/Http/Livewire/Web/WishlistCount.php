<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistCount extends Component
{
    public $wishListCount;

    protected $listeners = ['wishListAddedOrUpdated' => 'checkWishListCount'];

    public function checkWishListCount()
    {
        if (Auth::check()) {
            return $this->wishListCount = Wishlist::where('user_id', auth()->user()->id)->count();
        } else {
            return $this->wishListCount = 0;
        }
    }

    public function render()
    {
        $this->wishListCount = $this->checkWishListCount();
        return view('livewire.web.wishlist-count', [
            'wishListCount' => $this->wishListCount
        ]);
    }
}
