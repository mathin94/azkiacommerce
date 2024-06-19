<?php

namespace App\Console\Commands;

use App\Jobs\AutoCompleteOrderJob;
use App\Models\Shop\Order;
use Illuminate\Console\Command;

class DispatchAutoCompleteJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-complete-dispatch';

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
        $ordersIds = Order::FilterByStatus('packageSent')->whereNull('canceled_at')->pluck('id');
        $autoCompleteMinutes = config('order.auto_complete_hours') * 60;

        foreach ($ordersIds as $order_id) {
            AutoCompleteOrderJob::dispatch($order_id)
                ->delay(now()
                ->addMinutes($autoCompleteMinutes));
        }
    }
}
