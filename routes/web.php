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

Route::middleware(['guest:shop'])->group(function () {
    # Route Shop Login
    Route::get('/auth/login', App\Http\Livewire\ShopLogin::class)
        ->name('auth.login');

    # Route Shop Logout
    Route::get('/auth/logout', App\Http\Livewire\ShopLogout::class)
        ->name('auth.logout');
});
