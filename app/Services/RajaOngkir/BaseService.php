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
}
