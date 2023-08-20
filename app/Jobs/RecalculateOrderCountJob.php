<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\RecalculateOrderCountService;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RecalculateOrderCountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $order_id
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new RecalculateOrderCountService($this->order_id);
    }
}
