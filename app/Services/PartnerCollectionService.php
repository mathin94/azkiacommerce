<?php

namespace App\Services;

use App\Http\Resources\PartnerResource;
use App\Models\Shop\Customer;
use Illuminate\Support\Facades\DB;

class PartnerCollectionService
{
    public function getCollections()
    {
        $data = [];

        foreach ($this->getDistributor() as $distributor) {
            $data['distributor'][] = new PartnerResource($distributor);
        }

        foreach ($this->getAgen() as $agen) {
            $data['agen'][] = new PartnerResource($agen);
        }

        return collect($data);
    }

    private function getDistributor()
    {
        return cache()->remember('partner_distributor', 24 * 60 * 60, function () {
            return Customer::with([
                'mainAddress'
            ])
                ->whereIsActive(true)
                ->where(DB::raw("LOWER(JSON_EXTRACT(customer_type, '$.name'))"), 'like', '%distributor%')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        });
    }

    private function getAgen()
    {
        return cache()->remember('partner_agen', 24 * 60 * 60, function () {
            return Customer::with([
                'mainAddress'
            ])
                ->whereIsActive(true)
                ->where(DB::raw("LOWER(JSON_EXTRACT(customer_type, '$.name'))"), 'like', '%agen%')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        });
    }
}
