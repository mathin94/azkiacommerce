<?php

namespace App\Helpers;

use App\Models\Shop\Order;
use Illuminate\Support\Str;

class AutoNumber
{
    public static function createUniqueOrderNumber(): string
    {
        $prefix = 'TRX/AZK/' . date('Ymd') . '/';
        // Generate a random string to use as the last part of the cart number
        $randomString = Str::of(Str::random(5))->upper->toString();

        // Combine the random string with the current date and a prefix to create the cart number
        $orderNumber = $prefix . $randomString;

        // Check if a cart with this number already exists
        $orderExists = Order::where('number', $orderNumber)->count();

        // If a cart with this number already exists, generate a new random string and try again
        while ($orderExists) {
            $randomString = Str::of(Str::random(5))->upper->toString();
            $orderNumber = $prefix . $randomString;
            $orderExists = Cart::where('number', $orderNumber)->first();
        }

        // Return the unique cart number
        return $orderNumber;
    }
}
