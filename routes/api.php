<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Shop\ShopController;
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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group(['middleware' => 'api'], function ($router) {
    //auth
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });
    //shop
    Route::group(['middleware' => 'jwt.verify', 'namespace' => 'Shop', 'prefix' => 'shop'], function () {
        Route::group(['prefix' => 'mainCategory'], function () {
            Route::get('index', [ShopController::class, 'index']);
            Route::get('get-by-id/{id}', [ShopController::class, 'indexMainCategory']);
        });
        Route::group(['prefix' => 'subCategory',], function () {
            Route::get('get-by-id/{id}', [ShopController::class, 'indexSubCategory']);
        });
        Route::group(['prefix' => 'vendor',], function () {
            Route::get('get-by-id/{id}', [ShopController::class, 'indexVendor']);
        });
        Route::group(['prefix' => 'product',], function () {
            Route::get('get-by-id/{id}', [ShopController::class, 'indexProduct']);
        });
    });

});


