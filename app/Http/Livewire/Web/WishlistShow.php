<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistShow extends Component
{
    public function render()
    {
        $wishlist = Wishlist::where('user_id', auth()->user()->id)->get();

        return view('livewire.web.wishlist-show', [
            'wishlist' => $wishlist
        ]);
    }
}
