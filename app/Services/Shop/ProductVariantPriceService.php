<?php

namespace App\Services\Shop;

use App\Models\Shop\Customer;
use App\Models\Shop\ProductVariant;

class ProductVariantPriceService
{
    public $discount, $discountPercentage, $response, $customerType;

    public $prices;

    public function __construct(
        private readonly ?Customer $customer = null,
        private readonly ProductVariant $productVariant
    ) {
        $this->discount = $productVariant->product->activeDiscount;

        if (!is_null($this->customer)) {
            $this->customerType = $this->customer->customer_type;
        }
    }

    public function execute()
    {
        $this->prices = [
            'retail_price'                   => $this->productVariant->price,
            'normal_price'                   => $this->getNormalPrice(),
            'final_price'                    => $this->getFinalPrice(),
            'discount_percentage'            => $this->discount?->discount_percentage ?? 0,
            'membership_discount_percentage' => $this->customerType['discount'] ?? 0,
        ];

        return true;
    }

    public function getFinalPrice()
    {
        $base_price = $this->getNormalPrice();

        if (!$this->validDiscount()) {
            return $base_price;
        }

        return $base_price - ($base_price * ($this->discount->discount_percentage / 100));
    }

    public function getNormalPrice()
    {
        if (is_null($this->customer) || $this->discount?->with_membership_price == false) {
            return $this->productVariant->price;
        }

        $price = $this->productVariant->price;
        $membership_discount = $this->customerType['discount'];

        return $price - ($price * ($membership_discount / 100));
    }

    private function validDiscount(): bool
    {
        $discount = $this->productVariant->product->activeDiscount;

        if (!$discount) {
            return 0;
        }

        $discount_variant = $discount->discountVariants()->where('shop_product_variant_id', $this->productVariant->id)->first();

        if (!$discount_variant) {
            return 0;
        }

        if (!blank($discount->max_quantity) && $this->productVariant->quantity > $discount->max_quantity) {
            return 0;
        }

        $this->discount = $discount;

        return true;
    }
}
