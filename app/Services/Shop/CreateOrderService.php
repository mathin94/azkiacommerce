<?php

namespace App\Services\Shop;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Helpers\AutoNumber;
use App\Models\Backoffice\Address;
use App\Models\Shop\Cart;
use App\Models\Shop\Customer;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class CreateOrderService
{
    public $backoffice_sales,
        $order,
        $errors = [];

    public function __construct(
        private Customer $customer,
        private Address $shippingAddress,
        private Int $courier_id,
        private $courierServices,
        private $selectedService,
        private $dropship,
        private Cart $cart,
    ) {
    }

    public function perform()
    {
        if (!$this->createBackofficeSales()) {
            return false;
        }

        try {
            DB::beginTransaction();

            $this->createOrder();
            $this->createOrderItems();
            $this->createOrderShipping();
            $this->createOrderPayment();
            $this->updateCart();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $this->rollbackBackofficeSales();
            throw $th;
        }

        return true;
    }

    protected function service()
    {
        $services = collect($this->courierServices);

        $selected = json_decode($this->selectedService);

        $data = $services->where('name', $selected->service)->first();

        return json_decode(json_encode($data));
    }

    private function createBackofficeSales()
    {
        $data = [
            'recipient_name'    => $this->shippingAddress->recipient_name,
            'recipient_phone'   => $this->shippingAddress->recipient_phone,
            'recipient_address' => $this->shippingAddress->address_line,
            'subdistrict_id'    => $this->shippingAddress->subdistrict_id,
            'courier_id'        => $this->courier_id,
            'subdistrict_id'    => $this->shippingAddress->subdistrict_id,
            'courier_service'   => $this->service()->name,
            'shipping_cost'     => $this->service()->cost,
            'discount'          => 0, # TODO: Implement Discount
            'details'           => $this->buildSalesDetails(),
            'is_preorder'       => false, # TODO: Implement Preorder,
            'is_dropship'       => empty($dropship) ? false : true,
        ];

        if (!empty($dropship)) {
            $data['dropshipper_name'] = $dropship['dropshipper_name'];
            $data['dropshipper_phone'] = $dropship['dropshipper_phone'];
        }

        $request = Http::baseUrl(config('app.backoffice_api_url'))
            ->withOptions([
                'verify' => false
            ])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->withToken($this->customer->authorization_token)
            ->post('/customer/orders', $data);

        if ($request->failed()) {
            $this->errors = $request->body();
            return false;
        }

        $response = $request->json();

        $this->backoffice_sales = $response['data'];

        return true;
    }

    private function buildSalesDetails(): array
    {
        $data = [];

        foreach ($this->cart->items as $item) {
            $data[] = [
                'product_outlet_id' => $item->productVariant->product_outlet_id,
                'price' => $item->price,
                'qty'   => $item->quantity,
                'disc'  => $item->discount,
            ];
        }

        return $data;
    }

    private function createOrder(): void
    {
        $this->order = $this->customer->orders()->create([
            'resource_id' => $this->backoffice_sales['id'],
            'number' => AutoNumber::createUniqueOrderNumber(),
            'invoice_number' => $this->backoffice_sales['invoice_number'],
            'total_weight' => $this->totalWeight(),
            'total' => $this->cart->subtotal,
            'discount_voucher' => $this->backoffice_sales['discount'],
            'shipping_cost' => $this->service()->cost,
            'grandtotal' => $this->backoffice_sales['total_amount'],
            'total_profit' => $this->backoffice_sales['total_profit'],
            'status' => OrderStatus::WaitingPayment,
        ]);
    }

    private function createOrderItems(): void
    {
        foreach ($this->cart->items as $item) {
            $this->order->items()->create([
                'shop_product_variant_id' => $item->shop_product_variant_id,
                'name' => $item->name,
                'alternate_name' => $item->alternate_name,
                'normal_price' => $item->normal_price,
                'price' => $item->price,
                'weight' => $item->weight,
                'quantity' => $item->quantity,
                'discount' => $item->discount,
                'total_price' => $item->total_price,
                'color' => $item->productVariant?->color?->name,
                'size' => $item->productVariant?->size?->name,
            ]);
        }
    }

    private function createOrderShipping(): void
    {
        $data = [
            'subdistrict_id' => $this->shippingAddress->subdistrict_id,
            'courier_id' => $this->courier_id,
            'courier_service' => $this->service()->name,
            'courier_properties' => $this->service(),
            'is_dropship' => empty($dropship) ? false : true,
            'recipient_name' => $this->shippingAddress->recipient_name,
            'recipient_phone' => $this->shippingAddress->recipient_phone,
            'recipient_address' => $this->shippingAddress->address_line,
            'shipping_cost_estimation' => $this->service()->cost,
            'shipping_cost' => $this->service()->cost,
        ];

        if (!empty($dropship)) {
            $data['dropshipper_name'] = $dropship['dropshipper_name'];
            $data['dropshipper_phone'] = $dropship['dropshipper_phone'];
        }

        $this->order->shipping()->create($data);
    }

    private function createOrderPayment(): void
    {
        $this->order->payment()->create([
            'uuid' => Uuid::uuid4()
        ]);
    }

    private function rollbackBackofficeSales(): void
    {
        if (!empty($this->backoffice_sales)) {
            $uuid = $this->backoffice_sales['uuid'];
            $endpoint = "/customer/orders/{$uuid}/rollback";

            Http::baseUrl(config('app.backoffice_api_url'))
                ->withOptions([
                    'verify' => false
                ])
                ->withHeaders([
                    'accept' => 'application/json',
                ])
                ->withToken($this->customer->authorization_token)
                ->post($endpoint, []);
        }
    }

    private function updateCart(): void
    {
        $this->cart->update([
            'status' => CartStatus::CheckedOut,
            'checked_out_at' => now()
        ]);
    }

    private function totalWeight(): int
    {
        return (int) $this->cart->items->sum(function ($item) {
            return $item->weight * $item->quantity;
        });
    }
}
