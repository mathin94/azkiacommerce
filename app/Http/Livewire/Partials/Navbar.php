<?php

namespace App\Http\Livewire\Partials;

use App\Helpers\Menu;
use Livewire\Component;

class Navbar extends Component
{
    public $navbar_menu;
    public $user;
    public $wishlist_count;
    public $cartCount;
    public $cartItems;
    public $cartTotal;
    public $head_notification;

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->user = auth()->guard('shop')->user();
            if ($this->user->is_default_password) {
                $link = route('customer.profile');

                $this->head_notification = [
                    'content'   => "Anda masih menggunakan password default, demi keamanan harap ubah password anda. <a href='{$link}'>Ubah Password</a>",
                    'class'     => 'bg-danger'
                ];
            }
        }
    }

    protected $listeners = [
        'refreshComponent' => 'getCounter',
        'open-logout-modal' => 'openLogoutModal',
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
        $this->cartCount = $this->user?->cart?->item_count;

        $this->cartItems = $this->user?->cart?->items;

        $this->cartTotal = $this->user?->cart?->subtotal_label ?? 'Rp. 0';
    }

    public function mount()
    {
        $menu = new Menu;

        $this->navbar_menu = $menu->navbar()
            ->render();

        $this->getCounter();
    }

    public function openLogoutModal()
    {
        $this->emit('open-logout-dialog');
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
