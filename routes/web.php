<?php


use App\Http\Controllers\UserDashboard\MainController;
use App\Http\Controllers\UserDashboard\PropertyController;
use App\Http\Controllers\UserDashboard\UserController;
use App\Http\Controllers\UserDashboard\SocialAuthController;

use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Site\FavoriteController;
use App\Models\Dashboard\Property;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
     Route::as('site.')->group(function () {
         ;
         Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

         Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('auth.social');
         Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

        Route::get('/', [SiteController::class, 'main_page'])->name('index');
        Route::get('/privacy-policy', [SiteController::class, 'privacy_policy'])->name('privacy-policy');
        Route::post('/contact-seller/{seller_id?}', [SiteController::class, 'send_email_to_seller'])->name('contact-seller');
        Route::post('/contact-site', [SiteController::class, 'send_email_to_site'])->name('contact-site');
         Route::get('social-share', [SiteController::class, 'share']);
         Route::get('/update-links', function () {
             DB::statement("
            UPDATE property_images
            SET img = REPLACE(img, 'http://localhost/properties/public/', 'https://aqar.taqat-gaza.com/public/')
            WHERE img LIKE 'http://localhost/properties/public/%'
        ");

             return 'Links updated successfully!';
         });
         Route::get('/insert-setting', function () {
             DB::insert("INSERT INTO `settings` (id, `key`, `value`, `value_ar`, `name`, `name_ar`, `page`, `created_at`, `updated_at`)
        VALUES (NULL, 'main_logo', NULL, NULL, 'main_logo', 'main_logo', NULL, NULL, NULL)");
             DB::insert("INSERT INTO `settings` (id, `key`, `value`, `value_ar`, `name`, `name_ar`, `page`, `created_at`, `updated_at`)
        VALUES (NULL, 'secondary_logo', NULL, NULL, 'secondary_logo', 'secondary_logo', NULL, NULL, NULL)");
             DB::insert("INSERT INTO `settings` (`id`, `key`, `value`, `value_ar`, `name`, `name_ar`, `page`, `created_at`, `updated_at`) VALUES (NULL, 'address', NULL, NULL, 'address_en', 'address_ar', NULL, NULL, NULL);");
             return "Setting inserted successfully!";
         });
             Route::get('properties', [SiteController::class, 'properties'])->name('properties');
             Route::get('properties/city/{slug_city}', [SiteController::class, 'properties_city'])->name('properties.city');
             Route::get('property/{slug}', [SiteController::class, 'show_properties'])->name('property.show');
          Route::middleware('auth:web')->post('/favorites/toggle', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');

         Route::get('services', [SiteController::class, 'services'])->name('services');
         Route::get('faqs', [SiteController::class, 'faqs'])->name('faqs');
         Route::get('about-us', [SiteController::class, 'about_us'])->name('about-us');
         Route::get('contact-us', [SiteController::class, 'contact_us'])->name('contact');
         Route::get('pricing-plans', [SiteController::class, 'pricing_plans'])->name('pricing-plans');

         Route::get('blogs', [SiteController::class, 'blogs'])->name('blogs');
         Route::get('blog/{slug}', [SiteController::class, 'show_blogs'])->name('blog.show');
     });
    Route::middleware('auth:web')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');
        Route::post('/property/store-reviews/{property_id}', [PropertyController::class, 'store_reviews'])->name('store_reviews');

        Route::as('profile.')->prefix('profile')->group(function () {
            Route::get('/', [UserController::class, 'profile'])->name('index');
            Route::post('/update', [UserController::class, 'update'])->name('update');
            Route::post('/update/password', [UserController::class, 'update_password'])->name('update_password');
            Route::delete('/delete', [UserController::class, 'delete_account'])->name('delete_account');
        });

        Route::as('properties.')->group(function () {
            Route::get('properties', [PropertyController::class, 'index'])->name('index');
             Route::get('/property/edit/{id}', [PropertyController::class, 'edit'])->name('edit');
            Route::post('/property/update/{id}', [PropertyController::class, 'update'])->name('update');
            Route::get('/property/create', [PropertyController::class, 'create'])->name('create');
            Route::post('/property/store', [PropertyController::class, 'store'])->name('store');
            Route::delete('/property/delete/{id}', [PropertyController::class, 'delete'])->name('delete');
            Route::get('/property/sold/{id}', [PropertyController::class, 'sold'])->name('sold');
            Route::post('/property/generate-slug', [PropertyController::class, 'generateSlug'])->name('generate.slug');

        });

        Route::as('favorites.')->group(function () {
            Route::get('favorites', [FavoriteController::class, 'index'])->name('index');
             Route::delete('/favorite/delete/{id}', [FavoriteController::class, 'delete'])->name('delete');

        });
        Route::get('/reviews', [PropertyController::class, 'reviews'])->name('reviews.index');
        Route::put('reviews/update-status/{review}', [PropertyController::class, 'update_status_review'])->name('reviews.update_status');
             Route::delete('/reviews/delete/{id}', [PropertyController::class, 'delete_review'])->name('reviews.delete');
    });


    Route::get('/states/{country_id}', [MainController::class, 'getStates'])->name('admin.get_states');
    Route::get('/cities/{state_id}', [MainController::class, 'getCities'])->name('admin.get_cities');
    Route::post('/store/images', [MainController::class, 'saveProjectImages'])->name('admin.images.store');
    Route::get('/accounts', [MainController::class, 'get_users'])->name('admin.get_users');
    Route::post('/check-slug', [MainController::class, 'checkSlug'])->name('admin.check.slug');

});
require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
