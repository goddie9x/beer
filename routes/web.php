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
Route::group(['prefix'=>'admin','namespace'=>'Admin'/* ,'middleware'=>'loginAdmin' */],function(){
    Route::get('/', 'IndexController@index')->name('admin');
    Route::post('/', 'IndexController@postAllUnit')->name('admin.postAllUnit');
});
Route::group(['namespace'=>'Frontend'],function(){
    Route::get('/', 'IndexController@index');
    Route::post('/', 'IndexController@getAnalog')->name('frontend.getAnalog');
});

Auth::routes();