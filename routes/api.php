<?php

use Illuminate\Http\Request;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/dashboard', 'auth_sys@dashboard')->middleware(['checkSessionMiddleware']);

Route::post('/login', 'auth_sys@customLogin')->name('login.custom'); 

Route::post('/register', 'auth_sys@customRegistration')->name('register.custom'); 

Route::get('/logout', 'auth_sys@signOut')->name('signout');
Route::post('/addProduct','productsController@addProduct')->middleware(['checkSessionMiddleware']);
Route::get('/getProducts','productsController@getProducts')->middleware(['checkSessionMiddleware']);
Route::get('/myProducts', 'productsController@getUserProducts')->middleware(['checkSessionMiddleware']);
Route::get('/deleteProduct', 'productsController@deleteProduct');//->middleware(['checkSessionMiddleware']);
Route::post('/updateProduct', 'productsController@updateProduct')->middleware(['checkSessionMiddleware']);
Route::get('/search','productsController@search');
Route::get('/addView', 'productsController@addView');
Route::post('/addComment', 'productsController@addComment')->middleware(['checkSessionMiddleware']);
