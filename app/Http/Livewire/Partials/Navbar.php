<?php

namespace App\Http\Livewire\Partials;

use App\Helpers\Menu;
use Livewire\Component;

class Navbar extends Component
{
    public $navbar_menu;
    public $user;
    public $wishlist_count;
    public $cart_count;
    public $cartItems;
    public $cart_total;

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->user = auth()->guard('shop')->user();
        }
    }

    protected $listeners = [
        'refreshComponent' => 'getCounter'
    ];

    public function logout()
    {
        auth()->guard('shop')->logout();
        return redirect('/');
    }

    public function getCounter()
    {
        $this->getWishlist();
        $this->getCart();
    }

    private function getWishlist()
    {
        $wishlist_count = $this->user?->wishlists()
            ->cacheTags(["user_wishlist_count:{$this->user->id}"])
            ->count();

        if ($wishlist_count > 0) {
            $this->wishlist_count = "( $wishlist_count )";
        } else {
            $this->wishlist_count = null;
        }
    }

    private function getCart()
    {
        $this->cart_count = $this->user?->cart
            ->item_count;

        $this->cartItems = $this->user?->cart
            ->items;

        $cart_total = $this->user?->cart->total_price ?? 0;

        $this->cart_total = 'Rp. ' . number_format($cart_total, 0, ',', '.');
    }

    public function mount()
    {
        $menu = new Menu;

        $this->navbar_menu = $menu->navbar()
            ->render();

        $this->getCounter();
    }

    public function openModal()
    {
        $this->emit('open-logout-dialog');
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
