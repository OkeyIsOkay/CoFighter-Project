<?php

use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('/test', [AuthUserController::class, 'test']);
        Route::post('/login', [AuthUserController::class, 'login']);
        Route::prefix('reset')->group(function() {
            Route::post('/initiate', [AuthUserController::class, 'ResetPassword']);
            Route::post('/verify', [AuthUserController::class, 'ResetPasswordVerify']);
            Route::post('/complete', [AuthUserController::class, 'ResetPasswordChangePassword']);
        });
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['middleware' => ['web']], function () {

        Route::group([
            'middleware' => 'authenticateAdmin',
            'prefix' => 'admin'
        ], function ($router) {
            Route::post('/register', [AdminController::class, 'register']);
        });

        Route::group([
            'middleware' => 'authenticateDoctors',
            'prefix' => 'doctor'
        ], function ($router) {
            Route::post('/report/case', [DoctorsController::class, 'reportCase']);
        });

    });

});

Route::prefix('general')->group(function() {
    Route::post('/book-vaccination', [GeneralController::class, 'bookVaccination']);
    Route::post('/check-status', [GeneralController::class, 'vaccinationStatus']);
    Route::get('/states', [GeneralController::class, 'states']);
    Route::get('/centers/{id?}', [GeneralController::class, 'centers']);
    Route::get('/cases', [GeneralController::class, 'getCases']);
    Route::prefix('report')->group(function() {
        Route::post('/case', [GeneralController::class, 'reportCases']);
        Route::post('/complication', [GeneralController::class, 'reportComplications']);
    });

});



