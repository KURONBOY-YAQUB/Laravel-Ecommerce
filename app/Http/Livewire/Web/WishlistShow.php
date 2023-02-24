<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistShow extends Component
{
    public function removeWishListItem(int $id)
    {
        Wishlist::where('user_id', auth()->user()->id)->where('id', $id)->delete();
        $this->emit('wishListAddedOrUpdated');
        $this->dispatchBrowserEvent('message', [
            'text' => 'Wishlist Item Removed Successfully',
            'type' => 'success',
            'status' => 200
        ]);
    }

    public function render()
    {
        $wishlist = Wishlist::where('user_id', auth()->user()->id)->get();

        return view('livewire.web.wishlist-show', [
            'wishlist' => $wishlist
        ]);
    }
}
