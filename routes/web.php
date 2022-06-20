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
Auth::routes();

Route::group(['prefix'=>'administrator','namespace'=>'Admin'/* ,'middleware'=>'loginAdmin' */],function(){
    Route::get('/', 'IndexController@index')->name('admin');
    Route::post('/initAllUnit', 'IndexController@initAllUnit')->name('admin.initAllUnit');
    Route::post('/initThreshold', 'IndexController@initThreshold')->name('admin.initThreshold');
});
Route::group(['namespace'=>'frontend'],function(){
    Route::get('/', 'IndexController@index');
    Route::get('/alert', 'AlertController@index')->name('frontend.alert');
    Route::post('/', 'IndexController@getAnalog')->name('frontend.getAnalog');
    Route::get('/emailsForAlert', 'AlertController@GetAllEmailsForAlert')->name('frontend.alert.emailsForAlert');
    Route::post('/emailsForAlert', 'AlertController@ChangeActiveEmail')->name('frontend.alert.emailsForAlert');
    Route::get('/emailsForAlert', 'AlertController@GetAllEmailsForAlert')->name('frontend.alert.emailsForAlert');
    Route::get('/addEmailForAlert', 'AlertController@getAddEmailView')->name('frontend.alert.addEmailForAlert');
    Route::post('/addEmailForAlert', 'AlertController@SetEmailsForAlert')->name('frontend.alert.setEmailsForAlert');
    Route::post('/deleteEmail', 'AlertController@deleteEmail')->name('frontend.alert.deleteEmail');
    Route::get('/threshold', 'AlertController@getAllThreshold')->name('frontend.alert.threshold');
    Route::post('/setThreshold', 'AlertController@setThreshold')->name('frontend.alert.setThreshold');
    Route::get('/report', 'ReportController@index')->name('frontend.report');
    Route::post('/report', 'ReportController@GetFileByPath')->name('frontend.report.getFileByPath');
});
