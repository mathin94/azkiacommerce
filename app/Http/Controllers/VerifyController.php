<?php

namespace App\Http\Controllers;

use App\Services\Backoffice\AccountVerificationService;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function index()
    {
        $token = request()->get('token');
        $email = request()->get('email');

        if (empty($token) || empty($email)) {
            abort(404);
        }

        $service = new AccountVerificationService($email, $token);

        if (!$service->verify()) {
            return $service->errors;
            abort(404);
        }

        return redirect()->route('login')->with('success', 'Akun anda berhasil di verifikasi, silahkan login.');
    }
}
