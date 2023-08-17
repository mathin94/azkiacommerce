<?php

namespace App\Http\Livewire;

use App\Enums\VoucherType;
use App\Models\Backoffice\Courier;
use App\Models\Shop\Order;
use App\Models\Shop\Voucher;
use App\Services\RajaOngkir;
use App\Services\Shop\CreateOrderService;

class CartCheckout extends BaseComponent
{
    public $cart,
        $couriers,
        $courierId,
        $courierServices = [],
        $shipping_cost = 0,
        $shipping_cost_label = 'Layanan Pengiriman Belum Dipilih',
        $courierService,
        $grandtotal = 0,
        $grandtotal_label,
        $order,
        $isDropship,
        $shippingAddress,
        $dropshipperName,
        $voucher,
        $discountVoucher,
        $dropshipperPhone,
        $selectedVoucher;

    protected $origin_city_id = 430;

    public $listeners = ['select-address' => 'selectAddress'];

    public function selectAddress($id)
    {
        $this->shippingAddress = $this->customer->addresses->find($id);

        $this->reset(['shipping_cost', 'courierService', 'courierId', 'shipping_cost_label']);

        $this->emit('close-address-modal');
    }

    public function updatingCourierId($courierId)
    {
        $this->courierService = '';
    }

    public function updatedCourierId($courierId)
    {
        $this->shipping_cost = 0;

        $courier        = $this->couriers->find($courierId);

        if (empty($courier)) {
            $this->reset(['shipping_cost', 'courierService', 'courierId', 'shipping_cost_label']);
            return;
        }

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

        if (!empty($services)) {
            foreach ($services['costs'] as $row) {
                $etd = $row['cost'][0]['etd'];
                $etd_label = !empty($etd) ? "$etd Hari" : '';
                $data[] = [
                    'name'  => $row['service'],
                    'cost'  => $row['cost'][0]['value'],
                    'etd'   => $etd_label,
                    'value' => json_encode($row),
                ];
            }
        }

        $this->courierServices = $data;

        $this->mount();
    }

    public function applyVoucher()
    {
        if (empty($this->voucher)) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Gagal !</h5>
                        <p>Anda belum memilih voucher</p>
                    </div>
                "
            ]);

            return;
        }

        $this->calculateVoucher();
    }

    private function calculateVoucher()
    {
        $voucher = Voucher::active()->whereCode($this->voucher)->first();

        if (!$voucher) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Gagal !</h5>
                        <p>Kode Voucher Tidak Valid</p>
                    </div>
                "
            ]);

            return;
        }

        if ($this->cart->subtotal < $voucher->minimum_order) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Gagal !</h5>
                        <p>Kode Voucher Tidak Valid</p>
                    </div>
                "
            ]);

            return;
        }

        if ($voucher->voucher_type->value === VoucherType::ShippingCostDiscount) {
            $subject = $this->shipping_cost;
        } else {
            $subject = $this->cart->subtotal;
        }

        if ($voucher->is_percentage) {
            $discount = $subject * ($voucher->value / 100);
        } else {
            $discount = $voucher->value;
        }

        if (!is_null($voucher->maximum_discount) && $discount > $voucher->maximum_discount) {
            $discount = $voucher->maximum_discount;
        }

        $this->discountVoucher = $discount;
        $this->selectedVoucher = $voucher;
        $this->mount();
    }

    public function removeVoucher()
    {
        $this->reset(['discountVoucher', 'selectedVoucher', 'voucher']);
        $this->mount();
    }

    public function updatedCourierService($data)
    {
        if (!$data) {
            $this->shipping_cost = 0;
        }

        $data = json_decode($data);
        $this->shipping_cost = $data->cost[0]?->value ?? 0;

        if ($this->voucher) {
            $this->calculateVoucher();
        } else {
            $this->mount();
        }
    }

    private function validateSubmit()
    {
        if ($this->isDropship) {
            if (empty($this->dropshipperName) || empty($this->dropshipperPhone)) {
                $this->emit('showAlert', [
                    "alert" => "
                        <div class=\"white-popup\">
                            <h5>Pemesanan Gagal !</h5>
                            <p>Harap lengkapi informasi dropshipper</p>
                        </div>
                    "
                ]);

                return;
            }
        }

        if (!$this->courierId || empty($this->courierService)) {
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

        return true;
    }

    public function orderWhatsapp()
    {
        $dropshipParam = [];

        if (!$this->validateSubmit()) {
            return;
        }

        if ($this->isDropship) {
            $dropshipParam = [
                'dropshipper_name' => $this->dropshipperName,
                'dropshipper_phone' => $this->dropshipperPhone
            ];
        }

        $service = new CreateOrderService(
            customer: $this->customer,
            shippingAddress: $this->shippingAddress,
            courier_id: $this->courierId,
            courierServices: $this->courierServices,
            selectedService: $this->courierService,
            dropship: $dropshipParam,
            cart: $this->cart,
            discountVoucher: $this->discountVoucher,
            selectedVoucher: $this->selectedVoucher
        );

        if (!$service->perform()) {
            $this->emit('showAlert', [
                "alert" => "
                    <div class=\"white-popup\">
                        <h5>Terjadi Kesalahan !</h5>
                        <p>{$service->getMessage()}</p>
                    </div>
                "
            ]);

            return;
        }

        $order = $service->order;
        $shipping = $order->shipping;

        $text = "Halo kak admin Azkia Hijab, saya mau order produk berikut dong %0a%0a";
        $text .= "Subtotal : *$order->subtotal_label %0a";
        $text .= "Ongkos Kirim : *$order->shipping_cost_label %0a";
        $text .= "Diskon : *$order->discount_voucher_label %0a";
        $text .= "Total : *$order->final_price_label %0a";
        $text .= "------------------------------ %0a %0a";
        $text .= "*Nama Penerima*%0a";
        $text .= "$shipping->recipient_name ($shipping->recipient_phone) %0a %0a";
        $text .= "*Alamat Pengiriman*%0a";
        $text .= "$shipping->full_address %0a%0a";
        $text .= "*Jasa Pengiriman*%0a";
        $text .= "$shipping->courier_label";

        $url = "https://wa.me/+62895808855575/?text=$text";

        return redirect()->to($url);
    }

    public function submit()
    {
        $dropshipParam = [];

        if (!$this->validateSubmit()) {
            return;
        }

        if ($this->isDropship) {
            $dropshipParam = [
                'dropshipper_name' => $this->dropshipperName,
                'dropshipper_phone' => $this->dropshipperPhone
            ];
        }

        $service = new CreateOrderService(
            customer: $this->customer,
            shippingAddress: $this->shippingAddress,
            courier_id: $this->courierId,
            courierServices: $this->courierServices,
            selectedService: $this->courierService,
            dropship: $dropshipParam,
            cart: $this->cart,
            discountVoucher: $this->discountVoucher,
            selectedVoucher: $this->selectedVoucher
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

        if (is_null($this->customer->mainAddress)) {
            return redirect()->route('customer.addresses')
                ->with('warning', 'Anda belum memiliki alamat utama, silahkan tambah alamat agar anda dapat melakukan transaksi');
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
                $this->shipping_cost_label = format_rupiah($this->shipping_cost);
            }
        }

        $this->grandtotal = $this->cart->subtotal + $this->shipping_cost - $this->discountVoucher;
        $this->grandtotal_label = format_rupiah($this->grandtotal);
    }

    public function render()
    {
        return view('livewire.cart-checkout')
            ->layout('layouts.frontpage');
    }
}
