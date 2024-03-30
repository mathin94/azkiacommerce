<?php

namespace App\Services\RajaOngkir;

use App\Models\Backoffice\Courier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;

class GetCostSevice extends BaseService
{
    protected string $courier_code;
    protected int $origin_city_id;

    /**
     * Example
     * [
     *    'province_id' => 6,
     *    'city_id' => 1,
     *    'subdistrict_id' => 2,
     * ]
     *
     * @var array
     */
    protected array $destination;

    protected float $weight;

    public function __construct(
        string $courier_code,
        int $origin_city_id,
        array $destination,
        float $weight,
    ) {
        parent::__construct();

        $this->origin_city_id = $origin_city_id;
        $this->destination = $destination;
        $this->courier_code = Str::lower($courier_code);
        $this->weight = $weight;
    }

    private function getCustomCosts()
    {
        $province_id = $this->destination['province_id'];

        $cache_key = "custom_cost--$this->courier_code}-{$province_id}";

        return cache()->remember($cache_key, 24 * 60 * 60, function () use (
            $province_id,
        ) {
            return Courier::join('courier_services as cs', 'cs.courier_id', 'couriers.id')
                ->join('courier_service_shipping_costs as cssc', 'cssc.courier_service_id', 'cs.id')
                ->selectRaw('couriers.id,
                    couriers.code as courier_code,
                    cs.code as service_code,
                    cssc.cost,
                    cssc.is_fixed,
                    cssc.province_id,
                    cssc.city_id,
                    cssc.subdistrict_id')
                ->where('couriers.code', $this->courier_code)
                ->where('cssc.outlet_id', 1)
                ->where('cssc.active_at', '<=', now())
                ->where(function ($query) {
                    $query->where('cssc.inactive_at', null)
                        ->orWhere('cssc.inactive_at', '>=', now());
                })
                ->where('cssc.province_id', $province_id)
                ->get();
        });
    }

    public function getCosts()
    {
        $province_id = $this->destination['province_id'];
        $city_id = $this->destination['city_id'];
        $subdistrict_id = $this->destination['subdistrict_id'];

        if (!$this->isSupportedCourier($this->courier_code))
            return [];

        $custom_costs = $this->getCustomCosts();

        $request_body = [
            'courier' => $this->courier_code,
            'originType' => 'city',
            'destinationType' => 'subdistrict',
            'origin' => (int) $this->origin_city_id,
            'destination' => (int) $subdistrict_id,
            'weight' => $this->weight,
        ];

        $request = Http::withHeaders([
            'key' => config('app.rajaongkir.api_key'),
            'content-type' => 'application/json'
        ])
            ->post(config('app.rajaongkir.base_url') . '/cost', $request_body);

        if ($request->failed())
            return [];

        $resp = $request->json();
        $result = $resp['rajaongkir']['results'][0];

        $data = [];

        if (empty($result))
            return $data;

        $weight_tolerance = $this->getWeightTolerance($this->weight, $this->courier_code);

        foreach ($result['costs'] as $row) {
            $etd = $row['cost'][0]['etd'];
            $etd_label = !empty($etd) ? "$etd Hari" : '';
            $service_code = $row['service'];

            $cost = $row['cost'][0]['value'];

            $custom_cost = $custom_costs->where('service_code', $service_code)
                ->where('subdistrict_id', $subdistrict_id)
                ->first();

            if (empty($custom_cost)) {
                $custom_cost = $custom_costs->where('service_code', $service_code)
                    ->where('city_id', $city_id)
                    ->where('subdistrict_id', null)
                    ->first();
            }

            if (empty($custom_cost)) {
                $custom_cost = $custom_costs->where('service_code', $service_code)
                    ->where('province_id', $province_id)
                    ->where('city_id', null)
                    ->where('subdistrict_id', null)
                    ->first();
            }

            if (!empty($custom_cost)) {
                $cost = $custom_cost->cost;

                if (!$custom_cost->is_fixed) {
                    $cost = $cost * $weight_tolerance;
                }

                $row['cost'][0]['value'] = $cost;
            }

            $data[] = [
                'name'  => $service_code,
                'cost'  => $cost,
                'etd'   => $etd_label,
                'value' => json_encode($row),
            ];
        }

        return $data;
    }
}
