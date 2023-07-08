<?php

namespace App\Helpers;

use App\Models\Page;
use App\Models\Shop\Category;
use Illuminate\Support\Facades\Cache;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu as SpatieMenu;

class Menu
{
    private function mainMenu()
    {
        $pages = Page::active()->get();

        return SpatieMenu::new()
            ->add(Link::to('/', 'Home')->addClass('pr-2'))
            ->submenu(
                Link::to('/products', 'Produk')->addClass('sf-with-ul'),
                function (SpatieMenu $menu) {
                    $categories = Category::orderBy('name', 'asc')->get();

                    foreach ($categories as $item) {
                        $menu->link($item->public_url, $item->name);
                    }
                }
            )
            ->add(Link::to('/blogs', 'Blog')->addClass('pr-2'))
            ->submenu(
                Link::to('#', 'Informasi')->addClass('sf-with-ul'),
                function (SpatieMenu $menu) use ($pages) {
                    $menu->link(route('contact-us'), 'Hubungi Kami');

                    foreach ($pages as $item) {
                        $menu->link($item->public_url, $item->title);
                    }
                }
            )
            ->add(Link::to(route('partner-location'), 'Peta Mitra')->addClass('pr-2'));
    }

    public function navbar()
    {

        return $this->mainMenu()
            ->addClass('menu sf-arrows');
    }

    public function mobile()
    {
        return $this->mainMenu()
            ->addClass('mobile-menu');
    }
}
