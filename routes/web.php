<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/facebook', [SocialController::class,'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialController::class,'loginWithFacebook']);
Route::get('upload/live-streaming/facebook-video', [SocialController::class,'uploadLiveSavedVideoToFacebook']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('/get-random-user-from-sheet', [UserController::class,'getRandomUserFromSheet'])->name('getRandomUserFromSheet');
});

Route::get('privacy-policy', function(){
    return view('privacy-policy');
});
Route::get('terms-of-service', function(){
    return view('terms-of-service');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
