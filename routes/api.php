<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LandController;
use App\Http\Controllers\API\TransactionController;

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


Route::namespace('App\Http\Controllers\API')->group(function (){
    Route::get('users',[UserController::class, 'getList']);
    Route::get('user/{id?}',[UserController::class, 'getUsers']);
    Route::get('sort', [UserController::class, 'showUserSortedByLand']);
    Route::post('add-user',[UserController::class, 'addUsers']);
    Route::put('update-user/{id}',[UserController::class, 'updateUsers']);
    Route::put('delete-user/{id}',[UserController::class, 'deleteUsers']);
    Route::put('restore-user/{id}',[UserController::class, 'restoreUsers']);
});


Route::namespace('App\Http\Controllers\API')->group(function (){
    Route::get('land-sale',[LandController::class , 'getBy']);
    Route::get('land-all',[LandController::class , 'getAll']);
    Route::get('land/search', [ LandController::class, 'search']);
    Route::get('land-sort-desc', [ LandController::class, 'sortLandPriceDesc']);
    Route::get('land-sort-asc', [ LandController::class, 'sortLandPriceAsc']);
    Route::post('add-land',[LandController::class, 'store']);
    Route::put('update-land/{id}',[LandController::class, 'update']);
    Route::put('delete-land/{id}',[LandController::class, 'destroy']);
    Route::put('restore-land/{id}',[LandController::class, 'restoreLand']);
    Route::put('sale-land/{id}',[LandController::class, 'saleLand']);

});

Route::namespace('App\Http\Controllers\API')->group(function (){
    Route::get('transaction',[TransactionController::class , 'index']);
    Route::post('add-transaction',[TransactionController::class, 'store']);
    Route::put('delete-transaction/{id}',[TransactionController::class, 'destroy']);
    Route::put('restore-transaction/{id}',[TransactionController::class, 'restoreTransaction']);
});
