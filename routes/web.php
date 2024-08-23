<?php
namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     // return view('welcome');
//     return view('front.index');
// });

Auth::routes();
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return "Cache cleared successfully!";
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Front


// Admin
Route::prefix('admin')->group(function() {
    require 'custom/admin.php';
});
Route::get('/{slug}', [IndexController::class, 'dynamicPage'])->name('dynamicPage');
