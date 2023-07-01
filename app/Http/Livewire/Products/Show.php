<?php

namespace App\Http\Livewire\Products;

use App\Enums\CartStatus;
use App\Models\Shop\Product;
use App\Models\Shop\Wishlist;
use App\Services\Shop\AddToCartService;
use App\Services\Shop\ProductVariantPriceService;
use Illuminate\Support\Arr;
use Livewire\Component;

class Show extends Component
{
    public $product, $colors, $sizes, $stock, $stockLabel, $liked = false, $reviews;

    public $quantity = 1, $variant, $sizeId, $colorId, $price, $weight, $normalPrice;

    protected $customer, $cart;

    public function __construct()
    {
        if (auth()->guard('shop')->check()) {
            $this->customer = auth()->guard('shop')->user();
            $this->customer->load('cart');

            $this->cart = $this->customer->cart;
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
        if (empty($this->sizeId) || empty($this->colorId)) {
            return null;
        }

        $variant = $this->product
            ->variants
            ->where('color_id', $this->colorId)
            ->where('size_id', $this->sizeId)
            ->first();

        $service = new ProductVariantPriceService($this->customer, $variant);
        $service->execute();

        $prices = $service->prices;

        if ($variant) {
            $this->normalPrice = format_rupiah($prices['normal_price']);
            $this->price       = format_rupiah($prices['final_price']);
            $this->weight      = $variant->weight . ' gram';

            $this->emit('variantChanged', [
                'image' => $variant->media?->original_url
            ]);
        }

        $this->stock      = $variant?->resource->stock ?? 0;
        $this->stockLabel = $variant?->resource->stock_label;
        $this->variant    = $variant;
    }

    public function mount()
    {
        $slug      = request()->route('slug');

        $product = Product::with([
            'media',
            'category',
            'activeDiscount.discountVariants',
            'variants.color',
            'variants.size',
            'variants.resource',
            'seo', 'reviews'
        ])
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

        $this->product     = $product;
        $this->normalPrice = $product->normal_price_label;
        $this->price       = $product->price_label;
        $this->weight      = $product->weight_label;
        $this->colors      = Arr::where($colors, fn ($val) => !is_null($val));
        $this->sizes       = Arr::where($sizes, fn ($val)  => !is_null($val));

        if ($this->customer) {
            $this->liked = $this->customer->wishlists()->dontCache()->whereShopProductId($this->product->id)->count() > 0;
        }

        $this->getReviews();
    }

    public function getReviews()
    {
        # TODO: add pagination when more than 100
        $this->reviews = $this->product->reviews->sortBy('created_at')->reverse();
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

        $cart = $this->cart;

        if (empty($this->cart)) {
            $cart = $this->customer->cart()->firstOrNew(['status' => CartStatus::Draft]);
            $cart->save();
        }

        $service = new AddToCartService(
            cart: $cart,
            product: $this->product,
            variant: $this->variant,
            quantity: $this->quantity
        );

        if (!$service->execute()) {
            $alert = "<div class=\"white-popup\">";

            foreach ($service->errors as $error) {
                $alert .= "<p>{$error}</p>";
            }

            $alert .= "</div>";

            $this->emit('showAlert', [
                "alert" => $alert
            ]);

            return;
        }

        $this->emit('showAlert', [
            "alert" => "
                    <div class=\"white-popup\">
                        <p>Sukses Menambahkan Produk ke keranjang</p>
                    </div>
                "
        ]);

        $this->emit('refreshComponent');
    }

    public function render()
    {
        return view('livewire.products.show')
            ->layout('layouts.frontpage');
    }
}
