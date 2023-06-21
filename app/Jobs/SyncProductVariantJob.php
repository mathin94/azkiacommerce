<?php

namespace App\Jobs;

use App\Models\Shop\Product;
use App\Services\Product\SyncVariantService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProductVariantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $product_id
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = Product::find($this->product_id);

        if (!$product) {
            return;
        }

        $service = new SyncVariantService($product);
        $service->perform();
    }
}
