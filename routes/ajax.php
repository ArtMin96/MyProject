<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Ajax and Dropzone Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'prefix' => 'admin',
        'middleware' => ['auth','admin.auth']
    ], function() {

    /*====================================================Dropzone Routes=================================================================*/

    Route::post('/additional/images', 'ProductController@additionalImages')->name('additional.images');

    Route::post('/additional/image/remove', 'ProductController@additionalImagesRemove')->name('additional.remove');

    Route::post('/additional-info/images', 'InfoController@additionalImages')->name('additional_info.images');

    Route::post('/additional-info/image/remove', 'InfoController@additionalImagesRemove')->name('additional_info.remove');

    Route::get('/order/details/modal', 'HomeController@orderModal')->name('order.modal');

});


/*====================================================Ajax Routes=================================================================*/

Route::post('/cart-details', 'ViewController@cartSend')->name('cart.send');

Route::post('/cart-manipulate', 'ViewController@cartManipulate')->name('cart.cart.manipulate');

Route::post('/order-status-change', 'OrderController@statusChange')->name('order_status.change');

Route::post('/work-like', 'ViewController@worksLike')->name('works.like');

Route::post('/sale-remove', 'ViewController@saleRemove')->name('cart_sale.remove');

Route::get('/filter-category', 'ViewController@ajaxProducts')->name('ajax.filter.products');

Route::get('/get-cart-ajax', 'ViewController@ajaxCartWidget')->name('ajax.get.cart');
Route::post('/add-wishlist-ajax', 'ViewController@addWishlistAjax')->name('wishlist.add');
Route::post('/remove-wishlist-ajax', 'ViewController@removeWishlistAjax')->name('wishlist.remove');

Route::get('/get-products-ajax', 'ViewController@getAjaxProducts')->name('ajax.get.products');