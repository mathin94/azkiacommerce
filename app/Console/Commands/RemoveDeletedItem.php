<?php

namespace App\Console\Commands;

use App\Enums\CartStatus;
use App\Models\Shop\Cart;
use Illuminate\Console\Command;

class RemoveDeletedItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-deleted-item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $carts = Cart::with('items')
            ->whereStatus(CartStatus::Draft)
            ->get();

        foreach ($carts as $cart) {
            $this->info('cart id: ' . $cart->id);

            foreach ($cart->items as $item) {
                $variant = $item->productVariant;

                if (!$variant) {
                    $this->info('Deleting item ' . $item->name);
                    $item->delete();
                }
            }

            $cart->recalculate();
        }
    }
}
