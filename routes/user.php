<?php
use App\Role\UserRole;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes in User part for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect',
            'localeViewPath','auth','check.role:'.UserRole::ROLE_USER]
    ], function() {

    Route::get('/profile', 'ViewController@profile')->name('profile.page');

    Route::get('/checkout', 'ViewController@checkout')->name('checkout.page');

    Route::get('/order-success/{order_id}', 'ViewController@orderSuccess')->name('order.success');

    Route::post('/update-details', 'UserController@updateUser')->name('update.user');

    Route::post('/payment-store', 'ViewController@paymentStore')->name('payment.store_new');

    Route::post('/send-work', 'ViewController@workSend')->name('work.send');

    Route::get('/orders','ViewController@orders')->name('orders');

    Route::get('/order-details/{id}','ViewController@orderDetails')->name('order.details');

    Route::post('/add-address-ajax', 'ViewController@ajaxAddAddress')->name('ajax.add.address');

    Route::post('/remove-address-ajax', 'ViewController@ajaxRemoveAddress')->name('ajax.remove.address');
    Route::post('/remove-card-ajax', 'ViewController@ajaxRemoveCard')->name('ajax.remove.card');

    Route::post('/edit-address-ajax', 'ViewController@ajaxEditAddress')->name('ajax.edit.address');

    Route::post('/choose-address-ajax', 'ViewController@ajaxChooseAddress')->name('ajax.choose.address');

    Route::post('/update-address-ajax/{id}', 'ViewController@ajaxUpdateAddress')->name('ajax.update.address');

    Route::post('/store-address-ajax', 'ViewController@ajaxStoreAddress')->name('ajax.store.address');

});
