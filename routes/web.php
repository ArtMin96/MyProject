<?php

use App\Role\UserRole;
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
Route::group(
    [
        'prefix' => 'admin',
        'middleware' => ['auth','admin.auth']
    ], function() {



    Route::get('/dashboard', 'HomeController@index')->name('home');

    Route::post('/location-filter', 'PartnerController@filter')->name('location.filter');

    Route::post('/attribute-type', 'AttributeGroupController@type')->name('attribute.type');

    Route::post('/attribute-group_type', 'AttributeGroupController@groupType')->name('attribute_group.type');



    /*========================================CRUD Routes=================================================================*/



//    ---------------------------------------Role Super Admin-----------------------------




//                  ---------------------------------------Role Admin-----------------------------

        Route::group(
            [
                'middleware' => 'check.role:'.UserRole::ROLE_ADMIN
            ], function() {


            Route::get('/settings', 'SettingsController@index')->name('settings.index');

            Route::Post('/settings/{type}/store', 'SettingsController@store')->name('settings.store');


            Route::resource('nav_menu', 'NavigationMenuController')->parameters([
                'nav_menu' => 'navigationMenu'
            ]);


            Route::resource('slider', 'SliderController')->parameters([
                'slider' => 'slider'
            ]);

            Route::resource('faq', 'FaqController')->parameters([
                'faq' => 'faq'
            ]);

            Route::resource('register_settings', 'RegisterSettingsController')->parameters([
                'register_settings' => 'registerSettings'
            ]);

            Route::resource('test', 'TestController')->parameters([
                'test' => 'test'
            ]);

            Route::resource('test_result', 'TestResultController')->parameters([
                'test_result' => 'testResult'
            ]);


            Route::resource('info', 'InfoController')->parameters([
                'info' => 'info'
            ]);


            Route::resource('partner', 'PartnerController')->parameters([
                'partner' => 'partner'
            ]);

        });

    //                  ---------------------------------------Role Blog Moderator-----------------------------

        Route::group(
            [
                'middleware' => 'check.role:'.UserRole::ROLE_BLOG_MODERATOR
            ], function() {

            Route::resource('blog', 'BlogController')->parameters([
                'blog' => 'blog'
            ]);

            Route::resource('blog_category', 'BlogCategoryController')->parameters([
                'blog_category' => 'blogCategory'
            ]);



        });

    //                  ---------------------------------------ROLE_SHOP_MANAGER-----------------------------

        Route::group(
            [
                'middleware' => 'check.role:'.UserRole::ROLE_SHOP_MANAGER
            ], function() {


            Route::get('/users', 'HomeController@allUsers')->name('users.index');

            Route::get('/users/edit/{id}', 'HomeController@userEdit')->name('users.edit');

            Route::post('/users/update/{id}', 'UserController@changeUser')->name('users.update');

            Route::get('/users/orders/{id}', 'HomeController@userOrders')->name('user.orders');


            Route::resource('product', 'ProductController')->parameters([
                'product' => 'product'
            ]);


            Route::resource('components', 'ComponentsController')->parameters([
                'components' => 'components'
            ]);

            Route::resource('category', 'CategoryController')->parameters([
                'category' => 'category'
            ]);


            Route::resource('brand', 'BrandController')->parameters([
                'brand' => 'brand'
            ]);

            Route::resource('coupons', 'CouponsController')->parameters([
                'coupons' => 'coupons'
            ]);

            Route::resource('sale_coupon', 'SaleCouponController')->parameters([
                'sale_coupon' => 'saleCoupon'
            ]);

            Route::resource('promo', 'PromoController')->parameters([
                'promo' => 'promo'
            ]);
            Route::get('/get-promo-item-form', 'PromoController@getPromoItemForm')->name('get.promo.item.form');


            Route::resource('attribute_group', 'AttributeGroupController')->parameters([
                'attribute_group' => 'attributeGroup'
            ]);

            Route::resource('attribute', 'AttributeController')->parameters([
                'attribute' => 'attribute'
            ]);

            Route::resource('tag', 'TagController')->parameters([
                'tag' => 'tag'
            ]);

        });

    //                  ---------------------------------------Role ROLE_WAREHOUSE-----------------------------

        Route::group(
            [
                'middleware' => 'check.role:'.UserRole::ROLE_SUPPORT .'|'. UserRole::ROLE_WAREHOUSE
            ], function() {

            Route::get('/orders/{id}/show/', 'OrderController@show')->name('order.show');

            Route::get('/orders/{filter?}', 'OrderController@index')->name('order.index');
        });

        Route::get('/duplicate/item/{id}', 'ProductController@duplicate')->name('product.duplicate');

    });




























Route::resource('new', 'NewController')->parameters([
     'new' => 'new'
]);