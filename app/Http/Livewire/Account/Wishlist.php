<?php

namespace App\Http\Livewire\Account;

use App\Http\Livewire\BaseComponent;

class Wishlist extends BaseComponent
{
    public function delete($id)
    {
        $wishlist = $this->customer->wishlists()->findOrFail($id);

        $wishlist->delete();

        $this->emit('showAlert', [
            "alert" => "
                    <div class=\"white-popup\">
                        <h5>Sukses !</h5>
                        <p>Wishlist berhasil dihapus</p>
                    </div>
                "
        ]);
    }

    public function render()
    {
        $wishlists = $this->customer->wishlists()->paginate(10);

        return view('livewire.account.wishlist', [
            'wishlists' => $wishlists
        ])
            ->layout('layouts.dashboard', [
                'title' => 'Wishlist'
            ]);
    }
}
