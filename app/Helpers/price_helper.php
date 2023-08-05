<?php

if (!function_exists('base_price')) {
    function base_price($price): float | int
    {
        if (!in_array(gettype($price), ['int', 'float'])) {
            return 0;
        }

        if (auth()->guard('shop')->check()) {
            $discount = auth()->guard('shop')->user()->discount_percentage;

            if ($discount) {
                $price -= ($price * $discount / 100);
            }
        }

        return $price;
    }
}

if (!function_exists('format_rupiah')) {
    function format_rupiah($price, $nosymbol = false): string
    {
        $text = number_format($price, 0, ',', '.');

        if ($nosymbol) {
            return $text;
        }

        return 'Rp. ' . $text;
    }
}
