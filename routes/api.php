<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PostController;



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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});

Route::get('open',[DataController::class,'open']);
Route::post('closed',[DataController::class,'closed'])->middleware('checkjwt');
Route::post('invalidateToken',[DataController::class,'invalidateToken']);


// POST Controller testing authorization

Route::middleware('auth')->group(function(){
    Route::post('posts/throwerror',[PostController::class,'throwerror']);
    Route::get('posts',[PostController::class,'index']);
    Route::post('posts',[PostController::class,'store']);
    Route::post('posts/{post}',[PostController::class,'show']);
    Route::put('posts/{post}',[PostController::class,'update']);
    Route::delete('posts/{post}',[PostController::class,'destroy']);
    
});



