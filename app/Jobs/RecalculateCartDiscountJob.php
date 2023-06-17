<?php

namespace App\Jobs;

use App\Models\Shop\Cart;
use Illuminate\Bus\Queueable;
use App\Models\Shop\ProductDiscount;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Jobs\RecalculateCartDiscountDelayedJob;
use App\Services\Shop\RecalculateCartDiscountService;

class RecalculateCartDiscountJob implements ShouldQueue
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

        if ($discount->active_at > now()) {
            RecalculateCartDiscountDelayedJob::dispatch($discount->id)
                ->delay($discount->active_at);
        }

        if ($discount->inactive_at > now()) {
            RecalculateCartDiscountDelayedJob::dispatch($discount->id)
                ->delay($discount->inactive_at);
        }

        $service = new RecalculateCartDiscountService($discount);
        $service->execute();
    }
}
