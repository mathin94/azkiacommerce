<?php

namespace App\Services\Shop;

use Ramsey\Uuid\Uuid;
use App\Enums\CartStatus;
use App\Models\Shop\Cart;
use App\Enums\OrderStatus;
use App\Helpers\AutoNumber;
use App\Models\Shop\Voucher;
use App\Models\Shop\Customer;
use App\Models\Backoffice\Address;
use Illuminate\Support\Facades\DB;
use App\Jobs\CreateBackofficeSalesJob;
use App\Services\Backoffice\OrderService;
use App\Services\Shop\CheckLimitationService;

class CreateOrderService
{
    public $order, $errors = [];

    public function __construct(
        private Customer $customer,
        private Address $shippingAddress,
        private Int $courier_id,
        private $courierServices,
        private $selectedService,
        private $dropship,
        private Cart $cart,
        private ?Voucher $selectedVoucher = null,
        private ?Int $discountVoucher = 0
    ) {
    }

    public function perform()
    {
        if (!$this->validCourier() && !$this->validStock()) {
            return false;
        }

        try {
            DB::beginTransaction();

            $this->createOrder();
            $this->createOrderItems();
            $this->createOrderShipping();
            $this->createOrderPayment();
            $this->updateCart();

            if (!empty($this->selectedVoucher)) {
                $this->createVoucherUsage();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        CreateBackofficeSalesJob::dispatch($this->order->id);

        return true;
    }

    protected function validStock()
    {
        $cart = $this->cart->load(['items.productVariant.resource.detail', 'items.productVariant.product']);

        $valid = true;

        foreach ($cart->items as $item) {
            $variant = $item->productVariant;
            $resource = $variant->resource;

            if (empty($product)) {
                $this->errors[] = "Produk {$item->name} tidak ditemukan";
                $valid = false;
            }

            if ($resource->stock < $item->quantity) {
                $this->errors[] = "Stok {$item->name} tidak mencukupi, Stok tersedia : {$resource->stock}";
                $valid = false;
            } else {
                $service = new CheckLimitationService($cart, $variant->product, $item->quantity, $variant->id);

                if (!$service->execute()) {
                    $this->errors[] = "Produk {$item->name} melebihi batas pembelian untuk produk {$variant->product->name}, anda hanya dapat membeli {$service->limit} buah untuk keseluruhan total quantity produk ini";
                }
            }
        }

        return $valid;
    }

    protected function validCourier()
    {
        $valid = !empty($this->selectedService);

        if (!$valid) {
            $this->errors[] = 'Layanan Pengiriman Belum Dipilih';
        }

        return $valid;
    }

    public function getMessage()
    {
        if (empty($this->errors)) {
            return 'Terjadi Kesalahan, Silahkan periksa kembali inputan anda.';
        }

        if (is_array($this->errors)) {
            return join("<br>", $this->errors);
        }

        return $this->errors;
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
        if (blank($this->service())) {
            return false;
        }

        $data = [
            'recipient_name'    => $this->shippingAddress->recipient_name,
            'recipient_phone'   => $this->shippingAddress->recipient_phone,
            'recipient_address' => $this->shippingAddress->address_line,
            'subdistrict_id'    => $this->shippingAddress->subdistrict_id,
            'courier_id'        => $this->courier_id,
            'subdistrict_id'    => $this->shippingAddress->subdistrict_id,
            'courier_service'   => $this->service()->name,
            'shipping_cost'     => $this->service()->cost,
            'discount'          => $this->discountVoucher ?? 0, # TODO: Implement Discount
            'details'           => $this->buildSalesDetails(),
            'is_preorder'       => false, # TODO: Implement Preorder,
            'is_dropship'       => empty($this->dropship) ? false : true,
        ];

        if (!empty($this->dropship)) {
            $data['dropshipper_name']  = $this->dropship['dropshipper_name'];
            $data['dropshipper_phone'] = $this->dropship['dropshipper_phone'];
        }

        $service = new OrderService(token: $this->customer->authorization_token);

        $request = $service->createOrder($data);

        if (!$request) {
            $this->errors = $service->errors;
            return false;
        }

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
            'number'           => AutoNumber::createUniqueOrderNumber(),
            'total_weight'     => $this->totalWeight(),
            'total'            => $this->cart->subtotal,
            'discount_voucher' => $this->discountVoucher ?? 0,
            'shipping_cost'    => $this->service()->cost,
            'grandtotal'       => $this->cart->subtotal + $this->service()->cost - ($this->discountVoucher ?? 0),
            'status'           => OrderStatus::WaitingPayment,
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
            'is_dropship' => empty($this->dropship) ? false : true,
            'recipient_name' => $this->shippingAddress->recipient_name,
            'recipient_phone' => $this->shippingAddress->recipient_phone,
            'recipient_address' => $this->shippingAddress->address_line,
            'shipping_cost_estimation' => $this->service()->cost,
            'shipping_cost' => $this->service()->cost,
        ];

        if (!empty($this->dropship)) {
            $data['dropshipper_name'] = $this->dropship['dropshipper_name'];
            $data['dropshipper_phone'] = $this->dropship['dropshipper_phone'];
        }

        $this->order->shipping()->create($data);
    }

    private function createOrderPayment(): void
    {
        $this->order->payment()->create([
            'uuid' => Uuid::uuid4()
        ]);
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

    private function createVoucherUsage(): void
    {
        $this->order->voucherUsage()->create([
            'shop_voucher_id' => $this->selectedVoucher->id,
            'amount' => $this->discountVoucher
        ]);

        $this->selectedVoucher->decrement('quota', 1);
    }
}
