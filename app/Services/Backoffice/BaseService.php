<?php

namespace App\Services\Backoffice;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class BaseService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct($token = null)
    {
        $this->baseUrl = env('BACKOFFICE_API_BASE_URL');

        $this->setToken($token);
    }

    public function token()
    {
        return $this->token;
    }

    protected function setToken($token = null)
    {
        if ($token) {
            $this->token = $token;
        } else {
            $this->token = auth()->user()->authorization_token;
        }

        return $this;
    }

    protected function setUrl($path): string
    {
        $path = Str::replaceFirst('/', '', $path);
        return "{$this->baseUrl}/{$path}";
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)->withOptions([
            'verify' => false,
        ])
            ->withToken($this->token);
    }
}
