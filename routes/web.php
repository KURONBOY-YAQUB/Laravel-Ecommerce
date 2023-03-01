<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [App\Http\Controllers\Web\WebController::class, 'index']);
Route::get('/collections', [App\Http\Controllers\Web\WebController::class, 'categories']);
Route::get('/collections/{category_slug}', [App\Http\Controllers\Web\WebController::class, 'products']);
Route::get('/collections/{category_slug}/{product_slug}', [App\Http\Controllers\Web\WebController::class, 'productView']);

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [App\Http\Controllers\Web\Wishlist::class, 'index']);
    Route::get('/cart', [App\Http\Controllers\Web\CartController::class, 'index']);
    Route::get('/checkout', [App\Http\Controllers\Web\CheckoutController::class, 'index']);
});

Route::get('/thank-you', [App\Http\Controllers\Web\WebController::class, 'thankyou']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

    // Slider Routes
    Route::controller(App\Http\Controllers\Admin\SliderController::class)->group(function () {
        Route::get('/sliders', 'index');
        Route::get('/sliders/create', 'create');
        Route::post('/sliders', 'store');
        Route::get('/sliders/{slider}/edit', 'edit');
        Route::put('/sliders/{slider}', 'update');
        Route::get('/sliders/{slider}/delete', 'destroy');
    });

    // Category Routes
    Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
        Route::get('/category', 'index');
        Route::get('/category/create', 'create');
        Route::post('/category', 'store');
        Route::get('/category/{category}/edit', 'edit');
        Route::put('/category/{category}', 'update');
    });

    // Brands
    Route::get('/brands', App\Http\Livewire\Admin\Brand\Index::class);

    // Product Routes
    Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/create', 'create');
        Route::post('/products', 'store');
        Route::get('/products/{product}/edit', 'edit');
        Route::put('/products/{product}', 'update');
        Route::get('/products/{product}/delete', 'destroy');

        Route::get('/product-image/{product_image_id}/delete', 'destroyImage');

        Route::post('/product-color/{product_color_id}', 'updateProdColorQty');
        Route::get('/product-color/{product_color_id}/delete', 'deleteProdColorQty');
    });

    // Colors Routes
    Route::controller(App\Http\Controllers\Admin\ColorController::class)->group(function () {
        Route::get('/colors', 'index');
        Route::get('/colors/create', 'create');
        Route::post('/colors', 'store');
        Route::get('/colors/{color}/edit', 'edit');
        Route::put('/colors/{color}', 'update');
        Route::get('/colors/{color}/delete', 'destroy');
    });
});

