<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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



Route::post('/login', [UserController::class, 'loginUser']);
Route::post('/register', [UserController::class, 'registerUser']);
Route::get('/logout', [UserController::class, 'logout']);

Route::group(['middleware'=>['auth:sanctum']], function(){

    Route::apiResource('posts', PostController::class);
    Route::post('/update/{id}', [PostController::class, 'updatePost']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/all-users', [UserController::class, 'allUsers']);
//    Route::post('/create-post',[PostController::class,'createPost']);
//    Route::get('/posts',[PostController::class,'viewPosts']);
//    Route::get('/post/{id}',[PostController::class,'viewPost']);
//    Route::put('/update/{id}',[PostController::class,'updatePost']);
//    Route::delete('/detele/{id}', [PostController::class, 'deletePost']);

});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
