<?php

namespace App\Http\Livewire;

use App\Models\Backoffice\Courier;
use App\Models\Shop\Order;
use App\Services\RajaOngkir;
use App\Services\Shop\CreateOrderService;

class CartCheckout extends BaseComponent
{
    public $cart,
        $couriers,
        $courierId,
        $courierServices = [],
        $shipping_cost = 0,
        $shipping_cost_label,
        $courierService,
        $grandtotal = 0,
        $grandtotal_label,
        $order,
        $isDropship,
        $shippingAddress,
        $dropshipperName,
        $dropshipperPhone;

    protected $origin_city_id = 430;

    public $listeners = ['select-address' => 'selectAddress'];

    public function selectAddress($id)
    {
        $this->shippingAddress = $this->customer->addresses->find($id);

        $this->emit('close-address-modal');
    }

    public function updatedCourierId($courierId)
    {
        $this->courierService = null;
        $this->shipping_cost = 0;

        $courier        = $this->couriers->find($courierId);
        $rajaongkir     = new RajaOngkir;
        $total_weight   = $this->cart->total_weight;
        $subdistrict_id = $this->shippingAddress->subdistrict_id;
        $origin         = ['city' => $this->origin_city_id];
        $destination    = ['subdistrict' => $subdistrict_id];
        $metrics        = ['weight' => $total_weight, 'length' => 1, 'width' => 1, 'height' => 1, 'diameter' => 1];

        $weight_tolerance = $rajaongkir->getWeightTolerance($total_weight, $courier->code);
        $cache_key        = "shipping::$subdistrict_id::$weight_tolerance::$courier->code";
        $cache_ttl        = 24 * 60 * 60;

        $services = cache()->remember($cache_key, $cache_ttl, function () use ($rajaongkir, $origin, $destination, $metrics, $courier) {
            return $rajaongkir->getCost($origin, $destination, $metrics, $courier->code);
        });

        $data = [];

        foreach ($services['costs'] as $row) {
            $etd = $row['cost'][0]['etd'];
            $etd_label = !empty($etd) ? "$etd Hari" : '';
            $data[] = [
                'name'  => $row['service'],
                'cost'  => $row['cost'][0]['value'],
                'etd'  => $etd_label,
                'value' => json_encode($row),
            ];
        }

        $this->courierServices = $data;

        $this->mount();
    }

    public function updatedCourierService($data)
    {
        if (!$data) {
            $this->shipping_cost = 0;
        }

        $data = json_decode($data);
        $this->shipping_cost = $data->cost[0]->value;

        $this->mount();
    }

    public function submit()
    {
        if (!$this->courierId && empty($this->courierService)) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Pemesanan Gagal !</h5>
                        <p>Anda belum memilih ekspedisi</p>
                    </div>
                "
            ]);

            return;
        }

        $service = new CreateOrderService(
            customer: $this->customer,
            shippingAddress: $this->shippingAddress,
            courier_id: $this->courierId,
            courierServices: $this->courierServices,
            selectedService: $this->courierService,
            dropship: [
                'dropshipper_name' => $this->dropshipperName,
                'dropshipper_phone' => $this->dropshipperPhone
            ],
            cart: $this->cart
        );

        if (!$service->perform()) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Terjadi Kesalahan !</h5>
                        <p>Silahkan refresh browser anda dan coba lagi</p>
                    </div>
                "
            ]);

            info($service->errors);

            return;
        }

        $this->order = $service->order;

        if ($this->order) {
            $payment_uuid = $this->order->payment->uuid;

            return redirect(route('order.payment', $payment_uuid));
        }
    }

    public function openAddressModal()
    {
        $this->emit('open-address-modal', [
            'selectedId' => $this->shippingAddress->id
        ]);
    }

    public function mount()
    {
        if (empty($this->customer->cart)) {
            return redirect('/');
        }

        $this->cart = $this->customer->cart;
        $this->couriers = cache()->remember('supported_couriers', 24 * 60 * 60, function () {
            return Courier::supported()->get();
        });

        if (!$this->shippingAddress) {
            $this->shippingAddress = $this->customer->mainAddress;
        }

        if (!$this->courierId || !$this->courierService) {
            $this->shipping_cost_label = 'Layanan Pengiriman Belum Dipilih';
        } else {
            if ($this->shipping_cost >= 0) {
                $this->shipping_cost_label = 'Rp. ' . number_format($this->shipping_cost, 0, ',', '.');
            }
        }

        $this->grandtotal = $this->cart->subtotal + $this->shipping_cost;
        $this->grandtotal_label = 'Rp. ' . number_format($this->grandtotal, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.cart-checkout')
            ->layout('layouts.frontpage');
    }
}
