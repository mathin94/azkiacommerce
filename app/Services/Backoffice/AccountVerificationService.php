<?php

namespace App\Services\Backoffice;

class AccountVerificationService extends ApiService
{
    public $errors = [];

    public function __construct(
        public string $email,
        public string $token
    ) {
    }

    public function verify()
    {
        $request = $this->request()
            ->get('auth/customer/verify', [
                'email' => $this->email,
                'token' => $this->token
            ]);

        if ($request->failed()) {
            $this->errors = $request->body();
            return false;
        }

        return $request->json();
    }
}
