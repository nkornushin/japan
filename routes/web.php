<?php

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

Route::group(['middleware' => 'myAuth'], function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('index');
        
        Route::get('logout', [
            'uses' => 'SiteController@logout',
            'as' => 'logout'
        ]);
});

Route::group(['middleware' => 'myGuest'], function () {
        Route::get('signin', [
            'uses' => 'SiteController@signIn',
            'as' => 'signin'
        ]);
        
        Route::get('signup', [
            'uses' => 'SiteController@signUp',
            'as' => 'signup'
        ]);
        
        Route::post('signup', [
            'uses' => 'SiteController@postSignUp',
            'as' => 'signup.post'
        ]);
        
        Route::post('signin', [
            'uses' => 'SiteController@postLogin',
            'as' => 'signin.post'
        ]);
        
        Route::get('activate/{code}', [
            'uses' => 'SiteController@activate',
            'as' => 'signup.activate'
        ]);
        
});

