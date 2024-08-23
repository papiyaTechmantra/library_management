<?php
namespace App\Http\Controllers\Api;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// login
Route::name('api.')->group(function() {
    Route::prefix('user')->name('user.')->group(function() {
        Route::middleware('guest:web', 'PreventBackHistory')->group(function() {
            // user
            // Route::view('/login', 'front.auth.login')->name('login');
            // Route::view('/register', 'front.auth.register')->name('register');
            Route::post('/create', [AuthController::class, 'create'])->name('create');
            Route::post('/check', [AuthController::class, 'check'])->name('check');
            Route::post('/check/mobile', [AuthController::class, 'checkMobile'])->name('check.mobile');
        });
    });
});

// pincode
Route::get('/pincode/status', [PincodeController::class, 'status'])->name('pincode.status');
Route::post('/pincode/store', [PincodeController::class, 'store'])->name('pincode.store');

// coupon
Route::get('/coupon/status', [CouponController::class, 'status'])->name('coupon.status');
Route::get('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');

// address
Route::post('address/store', [AddressController::class, 'store'])->name('address.store');
Route::post('address/default', [AddressController::class, 'default'])->name('address.default');
Route::post('address/detail', [AddressController::class, 'detail'])->name('address.detail');
Route::post('address/update', [AddressController::class, 'update'])->name('address.update');

// cart
Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');

// wishlist
Route::post('wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// review interact
Route::post('review/interact', [ReviewController::class, 'check'])->name('review.toggle');

// guest cart
// Route::post('guest/cart/add', [GuestCartController::class, 'add'])->name('guest.cart.add');
