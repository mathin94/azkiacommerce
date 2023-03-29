<?php

namespace App\Helpers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu as SpatieMenu;

class Menu
{
    public function navbar()
    {
        return SpatieMenu::new()
            ->link('/', 'Home')
            ->add(Link::to(route('products.index'), 'Produk'))
            ->add(Link::to('/blogs', 'Blog'))
            ->submenu('<a href="#" class="sf-with-ul">Informasi</a>', function (SpatieMenu $menu) {
                $pages = Cache::remember(Page::ACTIVE_CACHE_KEY, 24 * 60 * 60, function () {
                    return Page::whereActive(true)->get();
                });

                foreach ($pages as $item) {
                    $menu->link($item->public_url, $item->title);
                }
            })
            ->add(Link::to('/partner-locations', 'Peta Mitra'))
            ->add(Link::to('/tracking-order', 'Lacak Pesanan'))
            ->addClass('menu sf-arrows');
    }

    public function topbar()
    {

        return SpatieMenu::new()
            ->submenu('<a href="#">Menu</a>', function (SpatieMenu $menu) {
                $menu->add(Link::to('/contact-us', 'Kontak Kami'))
                    ->add(Link::to('/wishlists', '<i class="icon-heart-o"></i> Wishlist <span class="wishlist-count"></span>'));

                if (auth()->guard('shop')->guest()) {
                    $menu->add(Link::to(route('auth.login'), '<i class="icon-user"></i> Login'));
                } else {
                    $menu->add(Link::to('#', '<i class="icon-user"></i> ' . auth()->guard('shop')->user()->name));
                }
            })
            ->addClass('top-menu pt-1 pb-0')
            ->wrap('div', ['class' => 'header-right']);
    }
}
