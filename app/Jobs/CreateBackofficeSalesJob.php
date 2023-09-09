<?php

namespace App\Jobs;

use App\Models\Shop\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Backoffice\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateBackofficeSalesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order_id;

    /**
     * Create a new job instance.
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::with(['items.productVariant.resource', 'shipping', 'customer'])
            ->find($this->order_id);

        if (!$order) return;

        if (!empty($order->invoice_number)) return;

        $customer = $order->customer;

        $service = new OrderService(token: $customer->authorization_token);
        $request = $service->createOrder(self::buildSalesParams($order));

        if (!$request) {
            throw new \Exception($service->errors);
        }
    }

    private static function buildSalesParams(Order $order)
    {
        $params = [
            'ecommerce_order_id'       => $order->id,
            'recipient_name'           => $order->shipping->recipient_name,
            'recipient_phone'          => $order->shipping->recipient_phone,
            'recipient_address'        => $order->shipping->recipient_address,
            'subdistrict_id'           => $order->shipping->subdistrict_id,
            'courier_id'               => $order->shipping->courier_id,
            'subdistrict_id'           => $order->shipping->subdistrict_id,
            'courier_service'          => $order->shipping->courier_service,
            'shipping_cost_estimation' => $order->shipping->shipping_cost_estimation ?? 0,
            'shipping_cost'            => $order->shipping->shipping_cost ?? 0,
            'discount'                 => $order->discount_voucher,
            'is_preorder'              => false, # TODO: Implement Preorder,
            'is_dropship'              => $order->shipping->is_dropship,
            'details'                  => self::buildSalesDetails($order),
        ];

        if ($order->shipping->is_dropship) {
            $params['dropshipper_name']  = $order->shipping->dropshipper_name;
            $params['dropshipper_phone'] = $order->shipping->dropshipper_phone;
        }

        return $params;
    }

    private static function buildSalesDetails(Order $order)
    {
        $details = [];

        foreach ($order->items as $item) {
            $details[] = [
                'product_outlet_id' => $item->productVariant->product_outlet_id,
                'price' => $item->price,
                'qty' => $item->quantity,
                'disc' => $item->discount,
            ];
        }

        return $details;
    }
}
