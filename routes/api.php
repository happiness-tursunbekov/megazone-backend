<?php

use App\Http\Controllers\Store\CategoryController as StoreCategoryController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
Route::get('search', [\App\Http\Controllers\HomeController::class, 'search'])->name('search.index');

Route::group(['prefix' => 'auth'], function () {
    Route::get('user', [\App\Http\Controllers\AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('auth.register');
    Route::get('google', [\App\Http\Controllers\AuthController::class, 'googleUrl'])->name('auth.google.url');
    Route::post('google', [\App\Http\Controllers\AuthController::class, 'google'])->name('auth.google');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('tree', [\App\Http\Controllers\CategoryController::class, 'tree'])->name('categories.tree');
    Route::get('{category}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
});

Route::group(['prefix' => 'fields'], function () {
    Route::get('/', [\App\Http\Controllers\FieldController::class, 'index'])->name('categories.index')
        ->middleware('auth:sanctum');
});
Route::group(['prefix' => 'stores'], function () {
    Route::get('create', [StoreController::class, 'create'])->name('stores.create');
    Route::get('/', [StoreController::class, 'index'])->name('stores.index');
    Route::post('/', [StoreController::class, 'store'])->name('stores.store')->middleware('auth:sanctum');
    Route::get('{store}', [StoreController::class, 'show'])->name('stores.show');
    Route::get('{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
    Route::put('{store}', [StoreController::class, 'update'])->name('stores.update')->middleware('auth:sanctum');
    Route::post('{store}/social-media', [StoreController::class, 'storeSocialMedia'])->name('stores.update')->middleware('auth:sanctum');
    Route::group(['prefix' => '{store}/settings/categories'], function () {
        Route::get('/', [StoreCategoryController::class, 'index'])->name('stores.settings.categories.index');
        Route::post('/sort', [StoreCategoryController::class, 'sort'])->name('stores.categories.sort');
        Route::post('/', [StoreCategoryController::class, 'store'])->name('stores.categories.create');
        Route::put('{storeCategory}', [StoreCategoryController::class, 'update'])->name('stores.categories.update');
        Route::delete('{storeCategory}', [StoreCategoryController::class, 'destroy'])->name('stores.categories.destroy');
        Route::get('{storeCategory}/edit', [StoreCategoryController::class, 'edit'])->name('stores.categories.edit');
        Route::get('{storeCategory}/fields', [StoreCategoryController::class, 'fields'])->name('stores.categories.fields');
        Route::post('{storeCategory}/fields', [StoreCategoryController::class, 'fieldStore'])->name('stores.categories.groups.store');
        Route::get('{storeCategory}/groups', [StoreCategoryController::class, 'groups'])->name('stores.categories.groups');
        Route::post('{storeCategory}/groups', [StoreCategoryController::class, 'groupStore'])->name('stores.categories.groups.store');
    })->middleware('auth:sanctum');
    Route::group(['prefix' => '{store}/categories'], function () {
        Route::get('{storeCategory}', [StoreCategoryController::class, 'show'])->name('stores.categories.show');
        Route::get('{storeCategory}/brand-models', [StoreCategoryController::class, 'brandModels'])->name('stores.categories.brand-models');
    });
    Route::group(['prefix' => '{store}/products'], function () {
        Route::get('/', [StoreProductController::class, 'index'])->name('stores.products.index');
        Route::post('/', [StoreProductController::class, 'store'])->name('stores.products.store')->middleware('auth:sanctum');
        Route::get('/create', [StoreProductController::class, 'create'])->name('stores.products.create');
        Route::get('{storeProduct}', [StoreProductController::class, 'show'])->name('stores.products.show');
        Route::get('{storeProduct}/related', [StoreProductController::class, 'related'])->name('stores.products.related');
        Route::get('{storeProduct}/reviews', [StoreProductController::class, 'reviews'])->name('stores.products.show');
        Route::post('{storeProduct}/reviews', [StoreProductController::class, 'storeReview'])->name('stores.products.show')->middleware('auth:sanctum');
        Route::post('{storeProduct}/reviews/{review}/reaction', [StoreProductController::class, 'reviewReaction'])->name('stores.products.show');
    });
});
