<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Shop\ProductDiscount;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Services\Shop\RecalculateCartDiscountService;

class RecalculateCartDiscountDelayedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $discount_id
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $discount = ProductDiscount::find($this->discount_id);

        if (!$discount) {
            return;
        }

        $service = new RecalculateCartDiscountService($discount);
        $service->execute();
    }
}
