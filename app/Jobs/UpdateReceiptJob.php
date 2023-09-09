<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Backoffice\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private $order_resource_id,
        private $user_token,
        private $receipt_number,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new OrderService(
            $this->user_token,
            $this->order_resource_id
        );

        $service->updateReceiptNumber($this->receipt_number);
    }
}
