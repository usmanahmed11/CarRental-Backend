<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailConfigController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\NewUserController;
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
Route::post('/password/email', [AuthController::class, "sendPasswordResetLink"]);
Route::post('/password/reset', [AuthController::class, "resetPassword"])->name('password.reset');

/* NewUserController  */
Route::post('/activateAccount', [NewUserController::class, 'activateAccount']);
Route::post('/newuser', [NewUserController::class, 'register']);
Route::get('/user-all', [NewUserController::class, 'showUsers']);
Route::delete('users/{id}', [NewUserController::class, 'destroy']);
Route::post('users/{id}', [NewUserController::class, 'update']);
Route::post('/users/{id}/set-password-to-default', [NewUserController::class, 'setPasswordToDefault']);
Route::get('/roles', [NewUserController::class, 'index']);
Route::get('/users/{id}/email', [NewUserController::class, 'getEmail']);

/* GrowthController  */
Route::post('/growth', [GrowthController::class, 'store']);
Route::delete('growth/{id}', [GrowthController::class, 'destroy']);
Route::post('growth/{id}', [GrowthController::class, 'update']);
Route::get('showGrowth/{id}', [GrowthController::class, 'showCandidate']);
Route::post('email-config', [EmailConfigController::class, 'store']);
Route::get('email-config', [EmailConfigController::class, 'getEmailConfig']);
Route::get('/job-titles', [GrowthController::class, 'getJobTitles']);
Route::get('/team', [GrowthController::class, 'getTeam']);
Route::get('/location', [GrowthController::class, 'getLocation']);
Route::get('/status', [GrowthController::class, 'getStatus']);
Route::get('/skillSet', [GrowthController::class, 'getSkillSet']);
Route::get('/get-candidate-info/{titleId}', [GrowthController::class, 'candidateInfo']);
Route::get('/growthStatus', [GrowthController::class, 'growthStatus']);
Route::get('/testemail', [GrowthController::class, 'testEmail']);


Route::group(['middleware' => ['auth:api']], function () {

    /* AuthController  */
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/user', [AuthController::class, 'show']);

    /* GrowthController  */
    Route::get('/growth-users', [GrowthController::class, 'show']);
});
