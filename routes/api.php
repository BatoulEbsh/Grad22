<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProposedSystemController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PreviousJobsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group([
        'middleware' => ['auth_user:api'],
    ], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'Team'
], function ($router) {
    Route::get('showAll', [TeamController::class, 'index']);
    Route::post('creatTeam', [TeamController::class, 'store'])->middleware('admin');
    Route::post('getTeamDate/{id}', [TeamController::class, 'getTeamDate']);
});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'PreviousJobs'
], function ($router) {
    Route::get('showAll', [PreviousJobsController::class, 'index']);
    Route::get('show/{id}', [PreviousJobsController::class, 'show']);
    Route::post('creatPreviousJob', [PreviousJobsController::class, 'store'])->middleware('admin');
    Route::get('searchByTitle/{title}', [PreviousJobsController::class, 'searchByTitle']);

});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'Products'
], function ($router) {
    Route::get('showAll', [ProductController::class, 'index'])->middleware('user');
    Route::get('showAllAdmin', [ProductController::class, 'showAll'])->middleware('admin');
    Route::get('show/{id}', [ProductController::class, 'show']);
    Route::post('addProduct', [ProductController::class, 'store'])->middleware('admin');
    Route::get('search/{id}', [ProductController::class, 'search']);
    Route::get('updateProductAvailable/{id}', [ProductController::class, 'updateProductAvailable'])->middleware('admin');
});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'ProposedSystem'
], function ($router) {
    Route::get('showAll', [ProposedSystemController::class, 'index']);
    Route::post('store', [ProposedSystemController::class, 'store'])->middleware('admin');
});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'Orders'
], function ($router) {
    Route::get('showAll', [OrderController::class, 'index'])->middleware('admin');
    Route::get('showAllMyOrder', [OrderController::class, 'showAllMyOrder'])->middleware('user');
    Route::post('store', [OrderController::class, 'store'])->middleware('user');
    Route::post('reject/{id}', [OrderController::class, 'reject'])->middleware('admin');
});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'appointments'
], function ($router) {
    Route::get('showAll', [AppointmentController::class, 'index']);
    Route::post('store', [AppointmentController::class, 'store'])->middleware('admin');
    Route::post('updateProducts/{id}', [AppointmentController::class, 'update']);
    Route::post('done/{id}', [AppointmentController::class, 'done']);
    Route::get('teamApp/{id}', [AppointmentController::class, 'teamApp']);
});

Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'records'
], function ($router) {
    Route::get('showAll', [RecordController::class, 'index']);
    Route::get('showMyRecord', [RecordController::class, 'showMyRecord']);
});
Route::group([
    'middleware' => ['auth_user:api'],
    'prefix' => 'invoices'
], function ($router) {
    Route::post('store', [InvoicesController::class, 'store'])->middleware('admin');
    Route::get('show/{id}', [InvoicesController::class, 'show'])->middleware('admin');
    Route::get('showAll', [InvoicesController::class, 'index'])->middleware('admin');
    
});

Route::get('types', [AppointmentController::class, 'getType']);
Route::get('statuses', [AppointmentController::class, 'getStatus']);


Route::post('/store', [NotificationController::class, 'updateDeviceToken']);
Route::post('/send', [NotificationController::class, 'sendNotification']);


