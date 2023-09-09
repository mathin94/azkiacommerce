<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Backoffice\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CancelOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private $order_resource_id,
        private $user_token,
        private $cancel_by_admin = false
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new OrderService($this->user_token, $this->order_resource_id);

        if ($this->cancel_by_admin) {
            $service->cancelByAdmin();
        } else {
            $service->cancel();
        }
    }
}
