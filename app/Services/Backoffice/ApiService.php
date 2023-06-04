<?php

namespace App\Services\Backoffice;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ApiService
{
    protected function request(): PendingRequest
    {
        return Http::baseUrl(config('app.backoffice_api_url'))
            ->withOptions([
                'verify' => false
            ])
            ->withHeaders([
                'accept' => 'application/json',
            ]);
    }
}
