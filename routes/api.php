<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ServiceController;

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


/* AuthController  */

Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::post('/password/email', [AuthController::class, "sendPasswordResetLink"]);
Route::post('/password/reset', [AuthController::class, "resetPassword"])->name('password.reset');
Route::post('/activateAccount', [AuthController::class, 'activateAccount']);

Route::group(['middleware' => ['auth:api']], function () {

    /* AuthController  */
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/user', [AuthController::class, 'show']);
});


Route::post('/cars', [CarController::class, 'store']);
Route::get('/listofcars', [CarController::class, 'index']);
Route::delete('/cars/{id}', [CarController::class, 'destroy']);
Route::post('/cars/{id}', [CarController::class, 'update']);
Route::get('/cars/{id}/show', [CarController::class, 'getCars']);
Route::get('/listofcarbookings', [CarController::class, 'listofbookings']);
Route::delete('/bookings/{id}', [CarController::class, 'destroyBookings']);
Route::post('/bookings/{id}', [CarController::class, 'updateBookings']);
Route::get('/bookings/{id}/show', [CarController::class, 'getBookings']);


Route::post('/payment/initiate', [CarController::class, 'checkout'])->name('payment.initiate');
Route::get('/payment/success', [CarController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [CarController::class, 'paymentCancel'])->name('payment.cancel');

Route::post('/stripe-payment', [CarController::class, 'stripePaymentt']);
Route::get('/payment/success', [CarController::class, 'stripePaymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [CarController::class, 'stripePaymentCancel'])->name('payment.cancel');


Route::post('/blogs', [BlogController::class, 'create']);
Route::get('/listofblogs', [BlogController::class, 'index']);
Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
Route::get('/blogs/{id}/show', [BlogController::class, 'getBlogs']);
Route::post('/blogs/{id}', [BlogController::class, 'update']);


Route::post('/services', [ServiceController::class, 'create']);
Route::get('/listofservices', [ServiceController::class, 'index']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
Route::get('/services/{id}/show', [ServiceController::class, 'getServices']);
Route::post('/services/{id}', [ServiceController::class, 'update']);
