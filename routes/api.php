<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Passport\Passport;

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
Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
Route::post('/oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);
Route::delete('/oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);


Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function(){
    Route::get('user',[AuthController::class, 'user']);
    Route::post('logout',[AuthController::class, 'logout']);
    Route::post('createPost',[AuthController::class,'createPost']);
    Route::get('viewPosts',[AuthController::class,'viewPosts']);
    Route::delete('removePost/{id}',[AuthController::class,'removePost']);
    Route::put('updatePost/{id}',[AuthController::class,'updatePost']);
    Route::get('getPosts',[AuthController::class, 'getPosts']);
});

