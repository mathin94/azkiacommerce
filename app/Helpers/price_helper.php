<?php

if (!function_exists('base_price')) {
    function base_price(float | int $price, bool $with_membership_price = true): float | int
    {
        if (auth()->guard('shop')->check() && $with_membership_price) {
            $discount = auth()->guard('shop')->user()->discount_percentage;

            if ($discount) {
                $price -= ($price * $discount / 100);
            }
        }

        return $price;
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah($price): string
    {
        return 'Rp. ' . number_format($price, 0, ',', '.');
    }
}
