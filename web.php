<?php

use App\Classes\Bills\Bills;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GeneralController;
use App\Models\TransactionFees;
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
Route::get('/states', [GeneralController::class, 'states']);
Route::get('/', function () {
    return redirect('CoFighter/login');
});

Route::group(['middleware' => ['web']], function () {
    Route::prefix('/CoFighter')->group(function() {

        Route::get('/login', [AuthUserController::class, 'cofighterLogin']);
        Route::get('/logout', [AuthUserController::class, 'logout']);
        Route::post('/loginCheck', [AuthUserController::class, 'doLogin']);

        Route::prefix('reset/')->group(function() {
            Route::get('initiate', [AuthUserController::class, 'getResetPassword']);
            Route::post('initiate', [AuthUserController::class, 'ResetPassword']);
            Route::get('verify', [AuthUserController::class, 'getResetPasswordVerify']);
            Route::post('verify', [AuthUserController::class, 'ResetPasswordVerify']);
            Route::get('complete', [AuthUserController::class, 'getResetPasswordChangePassword']);
            Route::post('complete', [AuthUserController::class, 'ResetPasswordChangePassword']);
        });
    });
});

Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['verify']], function () {

        Route::group([
            'middleware' => 'authenticateDoctor',
            'prefix' => '/CoFighter/doctor/'
        ], function ($router) {
            Route::get('dashboard', [DoctorsController::class, 'index']);
            Route::post('/cases/update', [DoctorsController::class, 'reportCase']);
            Route::get('/reported-cases', [DoctorsController::class, 'reportedCases']);
            Route::post('/reported-cases', [DoctorsController::class, 'reportedCasesPost']);
            Route::get('/reported-complications', [DoctorsController::class, 'reportedComplications']);
            Route::post('/reported-complications', [DoctorsController::class, 'reportedComplicationsPost']);
            Route::get('/booked-vaccination', [DoctorsController::class, 'bookedVaccination']);
            Route::get('/cases/add', [DoctorsController::class, 'addCases']);
            Route::post('/cases/add', [DoctorsController::class, 'addCasePost']);
            Route::get('/reported-cases/more/{id}', [DoctorsController::class, 'moreReportedCases']);
            Route::get('/reported-complications/more/{id}', [DoctorsController::class, 'moreReportedComplications']);
            Route::post('/reported-cases-status', [DoctorsController::class, 'reportedCasesStatusPost']);
            Route::get('/reported-cases/status/{id}', [DoctorsController::class, 'statusReportedCases']);
            Route::get('/manage-vaccination', [DoctorsController::class, 'manageVaccination']);
            Route::get('/booked-vaccination/action/{action}/{id}', [DoctorsController::class, 'editBookings']);

            Route::get('/cases/positive', [DoctorsController::class, 'positiveCases']);
            Route::get('/cases/discharged', [DoctorsController::class, 'dischargedCases']);
            Route::get('/cases/negative', [DoctorsController::class, 'negativeCases']);
            Route::get('/cases/death', [DoctorsController::class, 'deathCases']);

            Route::group(['middleware' => ['authenticateAdmin']], function () {
                Route::get('/add-user', [DoctorsController::class, 'addUser']);
                Route::post('/users/add', [DoctorsController::class, 'register']);
                Route::get('/manage-user', [DoctorsController::class, 'manageUser']);
                Route::get('/users/edit/{id}', [DoctorsController::class, 'editUser']);
            });

        });

        Route::group([
            'middleware' => 'authenticateAdmin',
            'prefix' => '/CoFighter/admin/'
        ], function ($router) {
            Route::get('/dashboard', [AdminController::class, 'index']);
            Route::post('/cases/update', [AdminController::class, 'reportCase']);
            Route::get('/add-user', [AdminController::class, 'addUser']);
            Route::post('/users/add', [AdminController::class, 'register']);
            Route::get('/manage-user', [AdminController::class, 'manageUser']);
            Route::get('/users/edit/{id}', [AdminController::class, 'editUser']);

            Route::get('/reported-cases', [AdminController::class, 'reportedCases']);
            Route::get('/reported-complications', [AdminController::class, 'reportedComplications']);
            Route::get('/booked-vaccination', [AdminController::class, 'bookedVaccination']);
            Route::get('/manage-vaccination', [AdminController::class, 'manageVaccination']);
            Route::get('/booked-vaccination/action/{action}/{id}', [AdminController::class, 'editBookings']);
        });

        Route::group([
            'middleware' => 'authenticateSuperAdmin',
            'prefix' => '/CoFighter/super-admin/'
        ], function ($router) {
            Route::get('dashboard', [SuperAdminController::class, 'manageUser']);
            Route::post('/users/add', [SuperAdminController::class, 'register']);
            Route::get('/add-user', [SuperAdminController::class, 'addUser']);
            Route::get('/manage-user', [SuperAdminController::class, 'manageUser']);
            Route::get('/users/edit/{id}', [SuperAdminController::class, 'editUser']);
            Route::get('/center', [SuperAdminController::class, 'getCenter']);
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
