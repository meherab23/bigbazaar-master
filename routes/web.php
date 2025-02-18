<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\CommonController;

use App\Http\Middleware\TestMiddleware;

Auth::routes();

Route::namespace('App\Http\Controllers')->group(function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', function () {
        return view('welcome');
    });

    // Route::get('/about-us', function () {
    //     return view('about-us');
    // });

    //Route::view('/about-us', 'about-us', ['name' => 'Taylor', 'age' => '32']);

    
    // Route::get('/about-us', [AboutUsController::class, 'index']);
    // Route::get('/{pages}', CommonController::class)->name('pages')->where('pages','about|contact|terms');

    Route::get('/about-us', 'AboutUsController@index')->middleware('testMiddleware');
    Route::get('/{pages}', 'CommonController')->name('pages')->where('pages','about|contact|terms');

    Route::get('/send-mail', function () {

        $details = [
    
            'title' => 'Mail from CDIP',
            'body' => 'This is for testing email using smtp'
    
        ];

        \Mail::to('romeoasif@gmail.com')->send(new \App\Mail\CategoryEmail($details));
    
       
    
        dd("Email is Sent.");
    
    });

});

Route::namespace("App\Http\Controllers\Admin")->prefix('admin')->group(function(){

    Route::namespace('Auth')->group(function(){

        Route::get('/login', 'LoginController@showloginform')->name('admin.login');
        Route::post('/login', 'LoginController@login');
        Route::post('/logout', 'LoginController@loggedout')->name('admin.logout');

    });

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('admin.home');

        Route::resource('/category', 'CategoryController');
        Route::resource('/attributes', 'AttributesController');
        Route::resource('/products', 'ProductController');
        Route::post('/get-product-details', 'ProductController@getProductDetails')->name('get-product-details');

        Route::resource('roles', 'RoleController');
        Route::get('/roles/{roleId}/give-permissions', 'RoleController@addPermissionToRole');
        Route::put('/roles/{roleId}/give-permissions', 'RoleController@givePermissionToRole');

        Route::resource('users', 'UserController');
        Route::resource('permissions', 'PermissionController');
    });

});
