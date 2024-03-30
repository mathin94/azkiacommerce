<?php

namespace App\Services\RajaOngkir;

use Illuminate\Support\Facades\Http;

class BaseService
{
    protected $client;

    public $response, $errors;

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
        'jnt',
        'sap',
        'jet',
        'dse',
        'first',
    ];

    public function __construct()
    {
        $this->client = Http::baseUrl(config('app.rajaongkir.base_url'))
            ->withHeaders([
                'key' => config('app.rajaongkir.api_key')
            ]);
    }

    public function success(): bool
    {
        if (!$this->response) {
            return false;
        }

        if (empty($this->response['rajaongkir'])) {
            return false;
        }

        return $this->response['rajaongkir']['status']['code'] === 200;
    }

    protected function isSupportedCourier($courier)
    {
        if (in_array($courier, $this->supportedCouriers))
            return true;
        else
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
