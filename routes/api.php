<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\OrderController;

//Route::get('/getCategories', 'ApiController@getCategories')->name('get-categories');
Route::get('/getCategories', [ApiController::class, 'getCategories']);
Route::get('/getAllFeaturedCategory', [ApiController::class, 'getAllFeaturedCategory']);


Route::get('/getProductsByCategoryId/{cid}', [ApiController::class, 'getProductsByCategoryId']);

Route::get('/getProductsById/{pid}', [ApiController::class, 'getProductsById'])->name('get-products-by-id');

Route::get('/products/search', [ApiController::class, 'search']);

Route::post('/submitOrderDetails', [ApiController::class, 'submitOrderDetails']);


// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'initiatePayment']);
Route::post('/payment-success', [SslCommerzPaymentController::class, 'successPayment']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);