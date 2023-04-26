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

Route::middleware(['auth:shop'])->group(function () {
    # Route Cart
    Route::get('/cart', App\Http\Livewire\CartDetail::class)->name('cart');
});

Route::middleware(['guest:shop'])->group(function () {
    # Route Shop Login
    Route::get('/login', App\Http\Livewire\ShopLogin::class)
        ->name('login');

    # Route Shop Logout
    Route::get('/logout', App\Http\Livewire\ShopLogout::class)
        ->name('logout');
});
