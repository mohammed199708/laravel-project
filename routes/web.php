<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', 'auth_sys@dashboard')->middleware(['checkSessionMiddleware']);
 Route::get('/login', 'auth_sys@login');
// Route::post('/login', 'auth_sys@customLogin')->name('login.custom'); 
 Route::get('/register','auth_sys@registeration');
// Route::post('/register', 'auth_sys@customRegistration')->name('register.custom'); 

// Route::get('/logout', 'auth_sys@signOut')->name('signout');
// Route::post('/addProduct','productsController@addProduct');
// Route::get('/getProducts','productsController@getProducts');
// Route::get('/myProducts', 'productsController@getUserProducts');
// Route::get('/deleteProduct', 'productsController@deleteProduct');
// Route::post('/updateProduct', 'productsController@updateProduct');
// Route::get('/search','productsController@search');
// Route::get('/addView', 'productsController@addView');
// Route::post('/addComment', 'productsController@addComment');
//Route::get('/test', 'productsController@testFuncs');


/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';*/
