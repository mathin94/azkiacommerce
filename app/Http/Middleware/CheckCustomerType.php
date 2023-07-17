<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $customer = $request->user();

        if ($customer && $this->hasAnyTypes($customer, $types)) {
            return $next($request);
        }

        abort(401, 'Unauthorized action.');
    }

    private function hasAnyTypes($customer, $types)
    {
        $customerType = strtolower($customer->customer_type_name);

        foreach ($types as $type) {
            if (Str::contains($customerType, $type)) {
                return true;
            }
        }

        return false;
    }
}
