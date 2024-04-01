<?php

namespace App\Services\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\Customer;
use App\Models\Shop\Product;

class CheckLimitationService
{
    protected Customer $customer;
    public int $limit;

    public function __construct(
        protected Cart $cart,
        protected Product $product,
        protected int $quantity,
        protected ?int $variantId = null,
    ) {
        $this->customer = $this->cart->customer;
    }

    public function execute(): bool
    {
        $limitation = $this->product
            ->limitations
            ->where('customer_type_id', $this->customer->customer_type_id)
            ->where('is_active', true)
            ->first();

        if (empty($limitation))
            return true;


        $cart_qty = Cart::join('shop_cart_items as sci', 'shop_carts.id', 'sci.shop_cart_id')
            ->join('shop_product_variants as spv', 'sci.shop_product_variant_id', 'spv.id')
            ->where('spv.shop_product_id', $this->product->id)
            ->where('shop_carts.id', $this->cart->id);

        if (!is_null($this->variantId)) {
            $cart_qty = $cart_qty->where('spv.id', '!=', $this->variantId);
        }

        $cart_qty = $cart_qty->sum('sci.quantity');

        $total_qty = $cart_qty + $this->quantity;

        $this->limit = $limitation->quantity_limit;

        if ($total_qty > $this->limit)
            return false;

        return true;
    }
}
