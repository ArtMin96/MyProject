<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web View Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes in View part for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('set-locale', 'ViewController@setLocale')->name('set.locale');
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function() {

    Route::get('/', function () {
        return view('welcome');
    });


    Route::view('admin','front.pages.auth.login');

    Route::get('/product/{slug}', 'ViewController@product')->name('product.page');

    Route::get('/products', 'ViewController@allProduct')->name('product.all');

    Route::get('/best-buy', 'ViewController@bestBuy')->name('best.buy');

    Route::get('/faq', 'ViewController@faq')->name('faq.page');

    Route::get('/category/{slug?}', 'ViewController@shop')->name('category.page');

    Route::get('/search', 'ViewController@search')->name('search.page');

    Route::get('/shop-cart', 'ViewController@cartpage')->name('cart.page');

    Route::get('/policy', 'ViewController@policy')->name('policy.page');

    Route::get('/arca/ineco','ViewController@checkPayment')->name('arca.check');

    Route::post('/wallets/idram/result','ViewController@checkPayment')->name('idram.check');

    Route::get('/wallets/idram/success','ViewController@successPayment')->name('idram.success');

    Route::get('/wallets/idram/fail','ViewController@failPayment')->name('idram.fail');

    Route::get('/blog', 'ViewController@blogs')->name('blog');

    Route::get('/article/{slug}', 'ViewController@blogPost')->name('blog.post');

    Route::get('pagination/fetch_data', 'ViewController@fetchBlog');

    Route::post('/store-product-review', 'ViewController@productReview')->name('product.review.post');

    Route::get('/test', 'ViewController@test')->name('test');

    Route::get('/test/result/{coin}', 'ViewController@testResult')->name('test_result.page');

    Route::get('/works', 'ViewController@works')->name('works');

    Route::get('/works-start', 'ViewController@worksStart')->name('works.start');

    Route::get('/works-register', 'ViewController@worksRegister')->name('works.register');

    Route::post('/coupon', 'ViewController@coupon')->name('coupon');

    Route::get('/shop','ViewController@shop')->name('shop');

    Route::get('/partners','ViewController@partners')->name('partners');

    Route::get('/page/{slug}','ViewController@page')->name('page');

    Route::post('/find-store','ViewController@findStore')->name('find-store');

    Route::get('/contact','ViewController@contact')->name('contact');
    Route::post('/contact-message','ViewController@contactMessage')->name('contact.message');
    Route::get('/wishlist','ViewController@getUserWishlist')->name('view.wishlist');
    Route::get('/ajax-live-search','ViewController@ajaxLiveSearch')->name('ajax.live.search');
    Route::get('/get-promotions-list','ViewController@getPromotions')->name('get.promotion.list');
    Route::get('/get-countries-ajax','ViewController@getCountriesAjax')->name('get.countries.ajax');
    Route::get('/get-states-ajax','ViewController@getStatesByCountryAjax')->name('get.states.ajax');
    Route::get('/get-cities-ajax','ViewController@getCitiesByStateAjax')->name('get.cities.ajax');

});

Route::get('/login/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
