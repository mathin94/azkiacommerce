<?php

namespace App\Services;

use App\Services\Backoffice\ApiService;

class ForgotPasswordService extends ApiService
{
    public function __construct(
        public readonly string $email
    ) {
    }

    public function submit()
    {
        return $this->request()->post('/password/forgot', [
            'email' => $this->email
        ]);
    }
}
