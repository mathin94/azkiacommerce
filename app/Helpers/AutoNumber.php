<?php

namespace App\Helpers;

use App\Models\Shop\Cart;
use Illuminate\Support\Str;

class AutoNumber
{
    public static function createUniqueCartNumber(): string
    {
        $prefix = 'CART/AZK/' . date('Ymd') . '/';
        // Generate a random string to use as the last part of the cart number
        $randomString = Str::of(Str::random(5))->upper->toString();

        // Combine the random string with the current date and a prefix to create the cart number
        $cartNumber = $prefix . $randomString;

        // Check if a cart with this number already exists
        $existingCart = Cart::where('number', $cartNumber)->first();

        // If a cart with this number already exists, generate a new random string and try again
        while ($existingCart) {
            $randomString = Str::of(Str::random(5))->upper->toString();
            $cartNumber = $prefix . $randomString;
            $existingCart = Cart::where('number', $cartNumber)->first();
        }

        // Return the unique cart number
        return $cartNumber;
    }
}
