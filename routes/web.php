<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', App\Http\Livewire\Pages\Home::class)->name('home');

Route::get('/page/{slug}', App\Http\Livewire\Pages\StaticPage::class)->name('page');

# Route Blog
Route::get('/blogs', App\Http\Livewire\Blogs\Index::class)->name('blogs.index');
Route::get('/blog/{slug}', App\Http\Livewire\Blogs\Show::class)->name('blogs.show');

# Route Product
Route::get('/products', App\Http\Livewire\Products\Index::class)->name('products.index');
Route::get('/product/{slug}', App\Http\Livewire\Products\Show::class)->name('products.show');

# Route Category
Route::get('/category/{slug}', App\Http\Livewire\Category\Show::class)->name('category.show');

# Route Contact Us
Route::get('/contact-us', App\Http\Livewire\ContactUs::class)->name('contact-us');

# Coming Soon
try {
    Route::get(soon()->link_slug, App\Http\Livewire\ComingSoonPage::class)->name('comingsoon');
} catch (\Throwable $th) {
}

# FAQ
Route::get('/faqs', App\Http\Livewire\FAQPage::class)->name('faqs');

Route::middleware(['auth:shop'])->group(function () {
    Route::middleware('customer.valid.address')->group(function () {
        # Route Cart
        Route::get('/cart', App\Http\Livewire\CartDetail::class)->name('cart');
        Route::get('/cart/checkout', App\Http\Livewire\CartCheckout::class)->name('cart.checkout');
        Route::get('/cart/instant-order', App\Http\Livewire\Account\InstantOrder::class)
            ->name('cart.instant-order')
            ->middleware('customer.type:agen,distributor');

        # Route Order Payment
        Route::get('/order/payment/{payment_uuid}', App\Http\Livewire\OrderPayment::class)->name('order.payment');

        # Customer Routes
        Route::prefix('/customer')->group(function () {
            Route::get('/dashboard', App\Http\Livewire\Account\CustomerDashboard::class)->name('customer.dashboard');
            Route::get('/profile', App\Http\Livewire\Account\CustomerProfile::class)->name('customer.profile');

            Route::get('/addresses', App\Http\Livewire\Account\CustomerAddresses::class)
                ->name('customer.addresses')
                ->withoutMiddleware('customer.valid.address');

            Route::get('/wishlist', App\Http\Livewire\Account\Wishlist::class)->name('customer.wishlist');
            Route::get('/orders', App\Http\Livewire\Account\OrderList::class)->name('customer.orders');
        });
    });

    Route::get('/partner-locations', App\Http\Livewire\Pages\PartnerLocation::class)
        ->name('partner-location');

    Route::prefix('/web-api')->group(function () {
        Route::get('/partners', [App\Http\Controllers\API\PartnerController::class, 'index']);
        Route::get('/subdistricts', [App\Http\Controllers\API\SubdistrictController::class, 'index']);
        Route::get('/products', [App\Http\Controllers\API\ProductController::class, 'index']);
    });
});

Route::middleware(['guest:shop'])->group(function () {
    # Route Shop Login
    Route::get('/login', App\Http\Livewire\ShopLogin::class)
        ->name('login');

    Route::get('verify', [App\Http\Controllers\VerifyController::class, 'index']);

    # Route Shop Forgot
    Route::get('/password/forgot', App\Http\Livewire\ShopForgotPassword::class)
        ->name('password.forgot');

    # Route Reset
    Route::get('/password/reset', App\Http\Livewire\ShopResetPassword::class)
        ->name('password.reset');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/shop/orders/{id}/shipping-label', App\Http\Livewire\ShippingLabel::class)->name('admin.orders.shipping-label');
});
