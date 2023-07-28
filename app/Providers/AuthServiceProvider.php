<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Auth\RemoteUserProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\RemoteUserGuard;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Shop\Product' => 'App\Policies\Shop\ProductPolicy',
        'App\Models\Shop\Category' => 'App\Policies\Shop\CategoryPolicy',
        'App\Models\Shop\Customer' => 'App\Policies\Shop\CustomerPolicy',
        'App\Models\Shop\Order' => 'App\Policies\Shop\OrderPolicy',
        'App\Models\Shop\ProductDiscount' => 'App\Policies\Shop\ProductDiscountPolicy',
        'App\Models\Shop\Voucher' => 'App\Policies\Shop\VoucherPolicy',
        'App\Models\Blog\Category' => 'App\Policies\Blog\CategoryPolicy',
        'App\Models\Blog\Post' => 'App\Policies\Blog\PostPolicy',
        'Spatie\Activitylog\Models\Activity' => 'App\Policies\ActivityPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
