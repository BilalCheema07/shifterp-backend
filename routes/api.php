<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TenantAuth\{LoginController, TwilioSmsController, VerifyEmailController, ForgotPasswordController, AuthenticatorController};

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

Route::post('/verify-sms', [TwilioSmsController::class, 'verifyMsg']);
Route::post('/resend-sms', [TwilioSmsController::class, 'resendMsg']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::post('register', [RegisterController::class, 'registerAdmin']);
    Route::group(['middleware' => ['super_admin'], 'prefix' => 'provision', 'as' => 'provision.'], function () {
        Route::get('subscription', [TenantController::class, 'subscription']);
        Route::any('list', [TenantController::class, 'list']);
        Route::post('store', [TenantController::class, 'store']);
        Route::post('sub-history', [TenantController::class, 'subscriptionHistory']);
        Route::post('upload-sow', [TenantController::class, 'sowUploads']); 
    });
    Route::post('provision/update', [TenantController::class, 'update'])->name('provision.update');
    Route::post('provision/change-status', [TenantController::class, 'changeStatus'])->name('provision.change-status');
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::post('send-sms', [TwilioSmsController::class, 'getCode']);
    Route::post('update-sms', [TwilioSmsController::class, 'updateSms']);
    Route::post('verify-updated-number', [TwilioSmsController::class, 'verifyUpdateNumber']);
    
    Route::post('send-verification-mail', [VerifyEmailController::class, 'sendVerificationEmail']);
    
    Route::get('reset-auth',[AuthenticatorController::class, 'resetAuth']);
    Route::post('verify-reset-auth',[AuthenticatorController::class, 'verifyResetAuth']);
    
    Route::get('refresh',[AuthenticatorController::class,'refresh']);
});


Route::post('/login', [LoginController::class, 'login']);

Route::post('forget-password', [ForgotPasswordController::class, 'sendEmailLink']);
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm']);

Route::post('verify-email', [VerifyEmailController::class, 'verifyEmail']);