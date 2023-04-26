<?php

namespace App\Http\Livewire\Products;

use App\Enums\CartStatus;
use App\Helpers\AutoNumber;
use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use App\Models\Shop\Wishlist;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Livewire\Component;

class Show extends Component
{
    public $product, $colors, $sizes, $stock, $stockLabel, $liked = false;

    public $quantity = 1, $variant, $sizeId, $colorId, $price, $weight;

    protected $customer;

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->customer = auth()->guard('shop')->user();
        }
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

    public function setSize($id)
    {
        $this->sizeId = $id;
        $this->getVariant();
    }

    public function setColor($id)
    {
        $this->colorId = $id;
        $this->getVariant();
    }

    protected function getVariant()
    {
        if (empty($this->sizeId) && empty($this->colorId)) {
            return null;
        }

        $variant = $this->product
            ->variants
            ->where('color_id', $this->colorId)
            ->where('size_id', $this->sizeId)
            ->first();

        if ($variant) {
            $this->price = 'Rp. ' . number_format($variant->resource->getFinalPrice($this->quantity), 0, ',', '.');
            $this->weight = $variant->weight . ' gram';

            $this->emit('variantChanged', [
                'image' => $variant->media?->original_url
            ]);
        }

        $this->stock = $variant?->resource->stock ?? 0;
        $this->stockLabel = $variant?->resource->stock_label;
        $this->variant = $variant;
    }

    public function mount()
    {
        $slug      = request()->route('slug');

        $product = Product::with(['media', 'category', 'variants.color', 'variants.size', 'seo'])
            ->cacheTags(["products:$slug"])
            ->where('slug', $slug)
            ->first();

        abort_if(!$product, 404);

        $colors = $product->variants
            ->pluck('color.name', 'color_id')
            ->toArray();
        $sizes  = $product->variants
            ->pluck('size.name', 'size_id')
            ->toArray();

        $this->product = $product;
        $this->price = $product->price_label;
        $this->weight = $product->weight_label;
        $this->colors = Arr::where($colors, fn ($val) => !is_null($val));
        $this->sizes = Arr::where($sizes, fn ($val) => !is_null($val));

        if ($this->customer) {
            $this->liked = $this->customer->wishlists()->dontCache()->whereShopProductId($this->product->id)->count() > 0;
        }
    }

    public function addToCart()
    {
        if (!$this->customer) {
            $login_url = route('login');

            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Gagal !</h5>
                        <p>Anda harus login untuk membeli produk ini</p>
                        <p><a href=\"{$login_url}\">Klik disini untuk login</a></p>
                    </div>
                "
            ]);

            return;
        }

        $cart = $this->customer->cart()->firstOrNew(['status' => CartStatus::Draft]);

        if (is_null($cart->number)) {
            $cart->number = AutoNumber::createUniqueCartNumber();
        }

        $cart->save();

        $cartItem             = $cart->items()->firstOrNew(['shop_product_variant_id' => $this->variant->id]);
        $cartItem->name       = $this->variant->name;
        $cartItem->unit_price = $this->variant->resource->getFinalPrice();
        $cartItem->weight     = $this->variant->weight;
        $cartItem->quantity   = (int) $cartItem->quantity + $this->quantity;

        if ($cartItem->quantity <= $this->variant->resource->stock) {
            $cartItem->save();

            $this->emit('showAlert', [
                "alert" => "
                        <div class=\"white-popup\">
                            <p>Sukses Menambahkan Produk ke keranjang</p>
                        </div>
                    "
            ]);

            $this->emit('refreshComponent');
        } else {
            $this->emit('showAlert', [
                "alert" => "
                        <div class=\"white-popup\">
                            <p>Stok tidak mencukupi {$this->variant->resource->stock}</p>
                        </div>
                    "
            ]);
        }
    }

    public function render()
    {
        return view('livewire.products.show')
            ->layout('layouts.frontpage');
    }
}
