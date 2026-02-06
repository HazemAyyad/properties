<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\MainController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear', function() {
    $exitCode = Artisan::call('optimize:clear');
    return 'Done';
});

Route::get('admin/dashboard', function () {
    return \Illuminate\Support\Facades\Auth::user();
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->as('roles.')->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('index');
    Route::get('/roles/data', [RoleController::class, 'data'])->name('data');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('store');
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('edit');
    Route::post('/roles/update', [RoleController::class, 'update'])->name('update');
    Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('delete');

});

 Route::group([

    'middleware' => ['auth','verified'],
    'namespace' => 'App\Http\Controllers',
],
    function () {



        //users
        Route::group([
            'as'=>'users.'
        ], function () {

            Route::get('users', 'Dashboard\UserController@index')->name('index');
            Route::get('get_users', 'Dashboard\UserController@get_users')->name('get_users');
            Route::get('get_user_referrals/{id}', 'Dashboard\UserController@get_user_referrals')->name('get_user_referrals');
            Route::get('user/edit/{id}', 'Dashboard\UserController@edit')->name('edit');
            Route::get('user/address/{id}/{type}', 'Dashboard\UserController@address')->name('address');
            Route::get('user/coins/{id}', 'Dashboard\UserController@coins')->name('coins');
            Route::get('user/invoices/{id}', 'Dashboard\UserController@invoices')->name('invoices');
            Route::get('user/login/{id}', 'Dashboard\UserController@login')->name('login');
            Route::get('user/profile/{id}', 'Dashboard\UserController@profile')->name('profile');
            Route::post('user/update/{id}', 'Dashboard\UserController@update')->name('update');
            Route::get('user/create', 'Dashboard\UserController@create')->name('create');
            Route::post('user/store', 'Dashboard\UserController@store')->name('store');
            Route::post('user/verify/{id}', 'Dashboard\UserController@verify')->name('verify');
            Route::delete('user/delete/{id}', 'Dashboard\UserController@delete')->name('delete');
            Route::get('user/security/{id}', 'Dashboard\UserController@security')->name('security');
            Route::get('user/referrals/{id}', 'Dashboard\UserController@referrals')->name('referrals');
            Route::post('user/update_password/{id}', 'Dashboard\UserController@update_password')->name('update_password');
            Route::get('get_user_coins/{user_id}', 'Dashboard\UserController@get_user_coins')->name('get_user_coins');
            Route::get('get_user_invoices/{user_id}', 'Dashboard\UserController@get_user_invoices')->name('get_user_invoices');
            Route::get('order/invoice/{package_id}/{invoice_id}', 'Dashboard\UserController@order_invoice')->name('order-invoice');

             Route::get('email_to_user/{user_id}', 'Dashboard\UserController@email_to_user')->name('email_to_user');
            Route::post('send_email_to_user/{user_id}', 'Dashboard\UserController@send_email_to_user')->name('send_email_to_user');
             Route::post('user/send_email_all', 'Dashboard\UserController@send_email_all')->name('send_email_all');


        });

//Sliders
        Route::get('sliders', 'Dashboard\SliderController@index')->name('sliders.index');
        Route::get('get_sliders', 'Dashboard\SliderController@get_sliders')->name('get_sliders');
        Route::get('slider/edit/{id}', 'Dashboard\SliderController@edit')->name('sliders.edit');
        Route::post('slider/update/{id}', 'Dashboard\SliderController@update')->name('sliders.update');
        Route::get('slider/create', 'Dashboard\SliderController@create')->name('sliders.create');
        Route::post('slider/store', 'Dashboard\SliderController@store')->name('sliders.store');
        Route::delete('slider/delete/{id}', 'Dashboard\SliderController@delete')->name('sliders.delete');
        //how-it-work
        Route::get('how-it-work', 'Dashboard\HowItWorkController@index')->name('how-it-work.index');
        Route::get('how-it-works', 'Dashboard\HowItWorkController@get_how_it_works')->name('how-it-works');
        Route::get('how-it-work/edit/{id}', 'Dashboard\HowItWorkController@edit')->name('how-it-work.edit');
        Route::post('how-it-work/update/{id}', 'Dashboard\HowItWorkController@update')->name('how-it-work.update');
        Route::get('how-it-work/create', 'Dashboard\HowItWorkController@create')->name('how-it-work.create');
        Route::post('how-it-work/store', 'Dashboard\HowItWorkController@store')->name('how-it-work.store');
//    Route::delete('how-it-work/delete/{id}', 'Dashboard\HowItWorkController@delete')->name('how-it-work.delete');
        //FAQS
        Route::get('faqs', 'Dashboard\FaqsController@index')->name('faqs.index');
        Route::get('get_faqs', 'Dashboard\FaqsController@get_faqs')->name('get_faqs');
        Route::get('faqs/edit/{id}', 'Dashboard\FaqsController@edit')->name('faqs.edit');
        Route::post('faqs/update/{id}', 'Dashboard\FaqsController@update')->name('faqs.update');
        Route::get('faqs/create', 'Dashboard\FaqsController@create')->name('faqs.create');
        Route::post('faqs/store', 'Dashboard\FaqsController@store')->name('faqs.store');
        Route::post('faqs/status/{id}', 'Dashboard\FaqsController@status')->name('faqs.status');
        Route::delete('faqs/delete/{id}', 'Dashboard\FaqsController@delete')->name('faqs.delete');
          Route::group([
            'as'=>'settings.'
        ], function () {

            Route::get('settings', 'Dashboard\SettingController@index')->name('index');
            Route::get('settings/{name}', 'Dashboard\SettingController@page')->name('page');
            Route::post('settings/update/{name}', 'Dashboard\SettingController@page_update')->name('page.update');
            Route::post('setting/update', 'Dashboard\SettingController@update')->name('update');


        });
        Route::group([
            'as'=>'contact.'
        ], function () {

            Route::get('contact', 'Dashboard\ContactController@index')->name('index');
            Route::get('get_contact', 'Dashboard\ContactController@get_contact')->name('get_contact');
            Route::get('contact/reply/{id}', 'Dashboard\ContactController@reply')->name('reply');
            Route::post('contact/reply/mail/{id}', 'Dashboard\ContactController@send_reply')->name('send_reply');
            Route::post('contact/status/{id}', 'Dashboard\ContactController@status')->name('status');
            Route::delete('contact/delete/{id}', 'Dashboard\ContactController@delete')->name('delete');


        });





    });




