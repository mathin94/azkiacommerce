<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkir
{
    protected $apiUrl;
    protected $apiKey;
    protected $client;
    protected $response;

    protected $supportedCouriers = [
        'jne',
        'pos',
        'tiki',
        'rpx',
        'pandu',
        'wahana',
        'sicepat',
        'jnt',
        'pahala',
        'sap',
        'jet',
        'indah',
        'dse',
        'slis',
        'first',
        'ncs',
        'star',
        'ninja',
        'lion',
        'idl',
        'rex',
        'ide',
        'sentral'
    ];

    protected $supportedWayBills = [
        'jne',
        'pos',
        'tiki',
        'pcp',
        'rpx',
        'wahana',
        'sicepat',
        'j&t',
        'sap',
        'jet',
        'dse',
        'first',
    ];

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY', 'apikey');
        $this->apiUrl = env('RAJAONGKIR_API_URL', 'https://pro.rajaongkir.com/api');
    }

    public function getProvinces()
    {
        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/province')->json();

        return $data['rajaongkir']['results'];
    }

    public function getProvince($idProvince)
    {
        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/province', ['id' => $idProvince])->json();

        return $data['rajaongkir']['results'];
    }

    public function getCities($idProvince = null)
    {
        $params = [];

        if (!is_null($idProvince)) {
            $params['province'] = $idProvince;
        }

        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/city', $params)->json();

        return $data['rajaongkir']['results'];
    }

    public function getCity($idCity)
    {
        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/city', ['id' => $idCity])->json();

        return $data['rajaongkir']['results'];
    }

    public function getSubdistricts($idCity)
    {
        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/subdistrict', ['city' => $idCity])->json();

        return $data['rajaongkir']['results'];
    }

    public function getSubdistrict($idSubdistrict)
    {
        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/subdistrict', ['id' => $idSubdistrict])->json();

        return $data['rajaongkir']['results'];
    }

    public function getInternationalOrigins($idProvince = null)
    {
        $params = [];

        if (isset($idProvince)) {
            $params['province'] = $idProvince;
        }

        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/v2/internationalOrigin', $params)->json();

        return $data['rajaongkir']['results'];
    }

    public function getInternationalOrigin($idCity = null, $idProvince = null)
    {
        if (isset($idCity)) {
            $params['id'] = $idCity;
        }

        if (isset($idProvince)) {
            $params['province'] = $idProvince;
        }

        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/v2/internationalOrigin', $params)->json();

        return $data['rajaongkir']['results'];
    }

    public function getInternationalDestinations()
    {
        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/v2/internationalDestination')->json();

        return $data['rajaongkir']['results'];
    }

    public function getInternationalDestination($idCountry = null)
    {
        $params = [];

        if (isset($idCountry)) {
            $params['id'] = $idCountry;
        }

        $data = Http::withHeaders([
            'key' => $this->apiKey
        ])
            ->get($this->apiUrl . '/v2/internationalDestination', $params)->json();

        return $data['rajaongkir']['results'];
    }

    protected function isSupportedCourier($courier)
    {
        if (in_array($courier, $this->supportedCouriers))
            return true;
        else
            return false;
    }

    public function getCost(array $origin, array $destination, $metrics, $courier)
    {
        if ($this->isSupportedCourier(strtolower($courier))) {
            $params['courier'] = strtolower($courier);

            $params['originType'] = strtolower(key($origin));
            $params['destinationType'] = strtolower(key($destination));

            if ($params['originType'] !== 'city') {
                $params['originType'] = 'subdistrict';
            }

            if (!in_array($params['destinationType'], ['city', 'country'])) {
                $params['destinationType'] = 'subdistrict';
            }

            if (is_array($metrics)) {
                if (
                    !isset($metrics['weight']) and
                    isset($metrics['length']) and
                    isset($metrics['width']) and
                    isset($metrics['height'])
                ) {
                    $metrics['weight'] = (($metrics['length'] * $metrics['width'] * $metrics['height']) / 6000) * 1000;
                } elseif (
                    isset($metrics['weight']) and
                    isset($metrics['length']) and
                    isset($metrics['width']) and
                    isset($metrics['height'])
                ) {
                    $weight = (($metrics['length'] * $metrics['width'] * $metrics['height']) / 6000) * 1000;

                    if ($weight > $metrics['weight']) {
                        $metrics['weight'] = $weight;
                    }
                }

                foreach ($metrics as $key => $value) {
                    $params[$key] = $value;
                }
            } elseif (is_numeric($metrics)) {
                $params['weight'] = $metrics;
            }

            $params['origin'] = $origin[key($origin)];
            $params['destination'] = $destination[key($destination)];

            $path = key($destination) === 'country' ? 'internationalCost' : 'cost';

            $data = Http::withHeaders([
                'key' => $this->apiKey,
                'content-type' => 'application/json'
            ])
                ->post($this->apiUrl . '/' . $path, $params);

            if ($data->ok()) {
                $data = $data->json();
                return $data['rajaongkir']['results'][0];
            }
        }

        return false;
    }

    public function getWaybill($idWaybill, $courier)
    {
        $path = '';

        $courier = strtolower($courier);

        if (in_array($courier, $this->supportedWayBills)) {
            $data = Http::withHeaders([
                'key' => $this->apiKey,
                'content-type' => 'application/x-www-form-urlencoded'
            ])
                ->post($this->apiUrl . $path, [
                    'waybill' => $idWaybill,
                    'courier' => $courier,
                ])->json();

            if (!is_null($data['rajaongkir']['result'])) {
                return $data['rajaongkir']['result'];
            }

            return false;
        }

        return false;
    }

    public function getWeightTolerance(int $weight, string $code)
    {
        $weight        = $weight > 0 ? $weight : 1;
        $weightInKilos = $weight / 1000;
        $result        = 0;
        $inc           = 0;

        if (in_array($code, ['jne', 'jnt', 'sicepat'])) {
            $inc = 0.3;
        }

        if ($code == 'tiki') {
            $inc = 0.29;
        }

        if ($code == 'pos') {
            $inc = 0.2;
        }

        if ($code == 'sap') {
            $inc = 0.39;
        }

        for ($i = 1; $i <= 10; $i++) {
            $limit = $i + $inc;

            if ($weightInKilos <= $limit) {
                $result = $i;
                break;
            } else {
                $result = $weightInKilos;
            }
        }

        return $result < 1 ? 1 : $result;
    }
}
