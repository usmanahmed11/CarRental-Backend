<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;

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


Route::post('/templates', [TemplateController::class, 'store']);
Route::get('/show', [TemplateController::class, 'index']);