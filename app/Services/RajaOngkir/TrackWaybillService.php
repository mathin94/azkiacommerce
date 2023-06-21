<?php

namespace App\Services\RajaOngkir;

use App\Models\Shop\Order;
use Carbon\Carbon;

class TrackWaybillService extends BaseService
{
    public $shipping;
    protected $waybill, $courier;

    public function __construct(
        protected Order $order,
    ) {
        parent::__construct();

        $this->shipping = $order->shipping;
        $this->waybill = $order->shipping->receipt_number;
        $this->courier = $order->shipping->courier->code;
    }

    public function execute()
    {
        if ($this->invalid()) {
            return false;
        }

        $oneHourAgo = now()->subHours(1);

        if ($this->shipping->tracking_updated_at?->isAfter($oneHourAgo) || $this->shipping->delivered) {
            $this->response = $this->shipping->tracking_details;
            return true;
        }

        $request = $this->client->asForm()->post('/waybill', [
            'waybill' => $this->waybill,
            'courier' => strtolower($this->courier),
        ]);

        if ($request->failed()) {
            $this->errors = [
                'Invalid request.'
            ];

            return false;
        }
        $this->response = $request->json();
        $this->saveResponse($request->json());

        return true;
    }

    protected function saveResponse($response)
    {
        $this->shipping->update([
            'tracking_details' => $response,
            'delivered_at' => $this->parseDeliveryDate($response),
            'tracking_updated_at' => now(),
        ]);

        $this->response = $this->shipping->tracking_details;
    }

    public function parseDeliveryDate($response)
    {
        if ($response['rajaongkir']['result']['delivered'] == false) {
            return null;
        }

        $delivery_status = $response['rajaongkir']['result']['delivery_status'];

        return "{$delivery_status['pod_date']} {$delivery_status['pod_time']}";
    }

    public function details()
    {
        if (!$this->success()) {
            return null;
        }

        $manifests = $this->response['rajaongkir']['result']['manifest'];

        $data = [];

        foreach ($manifests as $key => $item) {
            $timestamp = $item['manifest_date'] . ' ' . $item['manifest_time'];
            $timestamp = Carbon::parse($timestamp)->format('d/m/Y H:i') . ' WIB';

            $description = $item['manifest_description'] . ' [' . $item['city_name'] . ']';

            $data[] = [
                'description' => $description,
                'timestamp' => $timestamp,
            ];
        }

        return $data;
    }

    public function delivered(): bool
    {
        if (!$this->success()) {
            return false;
        }

        return $this->response['rajaongkir']['result']['delivered'];
    }

    public function invalid(): bool
    {
        if (!in_array(strtolower($this->courier), $this->supportedCouriers)) {
            $this->errors = [
                'Courier not supported.'
            ];

            return true;
        }

        return false;
    }
}
