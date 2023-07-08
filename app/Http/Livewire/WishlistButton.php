<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Shop\Wishlist;

class WishlistButton extends Component
{
    public $product, $liked = false, $customer;

    public function mount($product)
    {
        if (auth()->guard('shop')->check()) {
            $this->customer = auth()->guard('shop')->user();
        }

        $this->product = $product;
        $this->liked   = $product->wishlisted;
    }

    public function addToWishlist()
    {
        if (!$this->customer) {
            $login_url = route('login');

            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Gagal !</h5>
                        <p>Anda harus login untuk menyukai / membeli produk ini</p>
                        <p><a href=\"{$login_url}\">Klik disini untuk login</a></p>
                    </div>
                "
            ]);

            return;
        }

        if ($this->liked) {
            $this->customer->products()->detach($this->product->id);
            $message = 'Produk dihapus dari wishlist';
            $this->liked = false;
        } else {
            $this->customer->products()->attach($this->product->id);
            $message = 'Produk ditambahkan ke wishlist';
            $this->liked = true;
        }

        Wishlist::flushQueryCache(["user_wishlist_count:{$this->customer->id}"]);

        $this->emit('showAlert', [
            "alert" => "
                    <div class=\"white-popup\">
                        <p>{$message}</p>
                    </div>
                "
        ]);

        $this->emit('refreshComponent');
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
