<?php

use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\AdminAuth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\PlanController;
use App\Http\Controllers\Dashboard\PlanFeatureController;
use App\Http\Controllers\Dashboard\PlanUpgradeRequestController;
use App\Http\Controllers\Dashboard\MainController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\PartnerController;
use App\Http\Controllers\Dashboard\AgentController;
use App\Http\Controllers\Dashboard\PeopleSayController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\ProvinceController;
use App\Http\Controllers\Dashboard\DirectorateController;
use App\Http\Controllers\Dashboard\VillageController;
use App\Http\Controllers\Dashboard\CountryController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\BenefitController;
use App\Http\Controllers\Dashboard\FaqsController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\PolicyController;
use App\Http\Controllers\Dashboard\FeatureCategoryController;
use App\Http\Controllers\Dashboard\PropertyFeatureController;
use App\Http\Controllers\Dashboard\PropertyFacilityController;
use App\Http\Controllers\Dashboard\PropertyController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\Dashboard\CKEditorController;
use App\Http\Controllers\Site\SiteController;
 use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');

    //Roles
    Route::as('roles.')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('index');
        Route::get('/roles/data', [RoleController::class, 'data'])->name('data');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('create');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('store');
        Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::post('/roles/update', [RoleController::class, 'update'])->name('update');
        Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('delete');

    });

    //staff
    Route::as('staff.')->group(function () {
        Route::get('/staff', [AdminController::class, 'index'])->name('index');
        Route::get('/staff/profile/{id}', [AdminController::class, 'profile'])->name('profile');
        Route::get('/staff/get_staff', [AdminController::class, 'get_staff'])->name('get_staff');
        Route::get('/staff/create', [AdminController::class, 'create'])->name('create');
        Route::post('/staff/store', [AdminController::class, 'store'])->name('store');
        Route::get('/staff/edit/{id}', [AdminController::class, 'edit'])->name('edit');
        Route::post('/staff/status/{id}', [AdminController::class, 'status'])->name('status');
        Route::post('/staff/update_password/{id}', [AdminController::class, 'update_password'])->name('update_password');
        Route::post('/staff/update/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/staff/delete/{id}', [AdminController::class, 'delete'])->name('delete');


    });
    //users
    Route::as('users.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('index');
        Route::get('/users/get_users', [UserController::class, 'get_users'])->name('get_users');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::get('/user/login/{id}', [UserController::class, 'login'])->name('login');

        Route::get('/user/profile/{id}', [UserController::class, 'profile'])->name('profile');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/user/create', [UserController::class, 'create'])->name('create');
        Route::post('/user/store', [UserController::class, 'store'])->name('store');
        Route::post('/user/verify/{id}', [UserController::class, 'verify'])->name('verify');
        Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->name('delete');
        Route::post('/user/update_password/{id}', [UserController::class, 'update_password'])->name('update_password');
        Route::get('/email_to_user/{user_id}', [UserController::class, 'email_to_user'])->name('email_to_user');
        Route::post('/send_email_to_user/{user_id}', [UserController::class, 'send_email_to_user'])->name('send_email_to_user');
        Route::post('user/send_email_all', [UserController::class, 'send_email_all'])->name('send_email_all');

        Route::get('send-email-all-users', [UserController::class, 'send_email_all_users'])->name('send_email_all_users');


    });
    //partners
    Route::as('partners.')->group(function () {
        Route::get('partners', [PartnerController::class, 'index'])->name('index');
        Route::get('get_partners', [PartnerController::class, 'get_partners'])->name('get_partners');
        Route::get('/partner/edit/{id}', [PartnerController::class, 'edit'])->name('edit');
        Route::post('/partner/update/{id}', [PartnerController::class, 'update'])->name('update');
        Route::get('/partner/create', [PartnerController::class, 'create'])->name('create');
        Route::post('/partner/store', [PartnerController::class, 'store'])->name('store');
        Route::delete('/partner/delete/{id}', [PartnerController::class, 'delete'])->name('delete');

    });
//agents
    Route::as('agents.')->group(function () {
        Route::get('agents', [AgentController::class, 'index'])->name('index');
        Route::get('get_agents', [AgentController::class, 'get_agents'])->name('get_agents');
        Route::get('/agent/edit/{id}', [AgentController::class, 'edit'])->name('edit');
        Route::post('/agent/update/{id}', [AgentController::class, 'update'])->name('update');
        Route::get('/agent/create', [AgentController::class, 'create'])->name('create');
        Route::post('/agent/store', [AgentController::class, 'store'])->name('store');
        Route::delete('/agent/delete/{id}', [AgentController::class, 'delete'])->name('delete');

    });
    //plans
    Route::as('plans.')->group(function () {
        Route::get('plans', [PlanController::class, 'index'])->name('index');
        Route::get('get_plans', [PlanController::class, 'get_plans'])->name('get_plans');
        Route::get('/plan/edit/{id}', [PlanController::class, 'edit'])->name('edit');
        Route::post('/plan/update/{id}', [PlanController::class, 'update'])->name('update');
        Route::get('/plan/create', [PlanController::class, 'create'])->name('create');
        Route::post('/plan/store', [PlanController::class, 'store'])->name('store');
        Route::delete('/plan/delete/{id}', [PlanController::class, 'delete'])->name('delete');

    });
    //plan_features
    Route::as('plan_features.')->group(function () {
        Route::get('plan_features', [PlanFeatureController::class, 'index'])->name('index');
        Route::get('get_plan_features', [PlanFeatureController::class, 'get_plan_features'])->name('get_plan_features');
        Route::get('/plan_feature/edit/{id}', [PlanFeatureController::class, 'edit'])->name('edit');
        Route::post('/plan_feature/update/{id}', [PlanFeatureController::class, 'update'])->name('update');
        Route::get('/plan_feature/create', [PlanFeatureController::class, 'create'])->name('create');
        Route::post('/plan_feature/store', [PlanFeatureController::class, 'store'])->name('store');
        Route::delete('/plan_feature/delete/{id}', [PlanFeatureController::class, 'delete'])->name('delete');

    });
    // plan upgrade requests (طلبات الترقية)
    Route::as('plan-upgrade-requests.')->prefix('plan-upgrade-requests')->group(function () {
        Route::get('/', [PlanUpgradeRequestController::class, 'index'])->name('index');
        Route::get('/data', [PlanUpgradeRequestController::class, 'getRequests'])->name('data');
        Route::get('/{id}', [PlanUpgradeRequestController::class, 'show'])->name('show');
        Route::post('/{id}/accept', [PlanUpgradeRequestController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [PlanUpgradeRequestController::class, 'reject'])->name('reject');
    });
//agents
    Route::as('people_say.')->group(function () {
        Route::get('people_say', [PeopleSayController::class, 'index'])->name('index');
        Route::get('get_people_say', [PeopleSayController::class, 'get_people_say'])->name('get_people_say');
        Route::get('/people_say/edit/{id}', [PeopleSayController::class, 'edit'])->name('edit');
        Route::post('/people_say/update/{id}', [PeopleSayController::class, 'update'])->name('update');
        Route::get('/people_say/create', [PeopleSayController::class, 'create'])->name('create');
        Route::post('/people_say/store', [PeopleSayController::class, 'store'])->name('store');
        Route::delete('/people_say/delete/{id}', [PeopleSayController::class, 'delete'])->name('delete');

    });
    //benefits
    Route::as('benefits.')->group(function () {
        Route::get('benefits', [BenefitController::class, 'index'])->name('index');
        Route::get('get_benefits', [BenefitController::class, 'get_benefits'])->name('get_benefits');
        Route::get('/benefit/edit/{id}', [BenefitController::class, 'edit'])->name('edit');
        Route::post('/benefit/update/{id}', [BenefitController::class, 'update'])->name('update');
        Route::get('/benefit/create', [BenefitController::class, 'create'])->name('create');
        Route::post('/benefit/store', [BenefitController::class, 'store'])->name('store');
        Route::delete('/benefit/delete/{id}', [BenefitController::class, 'delete'])->name('delete');

    });
    //services
    Route::as('services.')->group(function () {
        Route::get('services', [ServiceController::class, 'index'])->name('index');
        Route::get('get_services', [ServiceController::class, 'get_services'])->name('get_services');
        Route::get('/service/edit/{id}', [ServiceController::class, 'edit'])->name('edit');
        Route::post('/service/update/{id}', [ServiceController::class, 'update'])->name('update');
        Route::get('/service/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/service/store', [ServiceController::class, 'store'])->name('store');
        Route::delete('/service/delete/{id}', [ServiceController::class, 'delete'])->name('delete');

    });
    //provinces
    Route::as('provinces.')->group(function () {
        Route::get('provinces', [ProvinceController::class, 'index'])->name('index');
        Route::get('get_provinces', [ProvinceController::class, 'get_provinces'])->name('get_provinces');
        Route::get('/province/edit/{id}', [ProvinceController::class, 'edit'])->name('edit');
        Route::post('/province/update/{id}', [ProvinceController::class, 'update'])->name('update');
        Route::get('/province/create', [ProvinceController::class, 'create'])->name('create');
        Route::post('/province/store', [ProvinceController::class, 'store'])->name('store');
        Route::delete('/province/delete/{id}', [ProvinceController::class, 'delete'])->name('delete');

    });
    //directorates
    Route::as('directorates.')->group(function () {


        Route::get('directorates/{province_id}', [DirectorateController::class, 'index'])->name('index');
        Route::get('get_directorates/{province_id}', [DirectorateController::class, 'get_directorates'])->name('get_directorates');

        Route::get('directorate/{province_id}/create', [DirectorateController::class, 'create'])->name('create');
        Route::post('directorate/{province_id}/store', [DirectorateController::class, 'store'])->name('store');
        Route::get('directorate/edit/{directorate_id}', [DirectorateController::class, 'edit'])->name('edit');
        Route::post('directorate/update/{directorate_id}', [DirectorateController::class, 'update'])->name('update');
        Route::delete('directorate/delete/{directorate_id}', [DirectorateController::class, 'delete'])->name('delete');
    });
    //directorates
    Route::as('villages.')->group(function () {


        Route::get('villages/{directorate_id}', [VillageController::class, 'index'])->name('index');
        Route::get('get_villages/{directorate_id}', [VillageController::class, 'get_villages'])->name('get_villages');

        Route::get('village/{directorate_id}/create', [VillageController::class, 'create'])->name('create');
        Route::post('village/{directorate_id}/store', [VillageController::class, 'store'])->name('store');
        Route::get('village/edit/{village_id}', [VillageController::class, 'edit'])->name('edit');
        Route::post('village/update/{village_id}', [VillageController::class, 'update'])->name('update');
        Route::delete('village/delete/{village_id}', [VillageController::class, 'delete'])->name('delete');
    });
    //countries
    Route::as('countries.')->group(function () {
        Route::get('countries', [CountryController::class, 'index'])->name('index');
        Route::get('get_countries', [CountryController::class, 'get_countries'])->name('get_countries');
        Route::get('/country/edit/{id}', [CountryController::class, 'edit'])->name('edit');

        Route::post('/country/update/{id}', [CountryController::class, 'update'])->name('update');
        Route::get('/country/create', [CountryController::class, 'create'])->name('create');
        Route::post('/country/store', [CountryController::class, 'store'])->name('store');
        Route::delete('/country/delete/{id}', [CountryController::class, 'delete'])->name('delete');

    });
    //cities
    Route::as('cities.')->group(function () {


        Route::get('cities/{country_id}', [CityController::class, 'index'])->name('index');
        Route::get('get_cities/{country_id}', [CityController::class, 'get_cities'])->name('get_cities');

        Route::get('city/{country_id}/create', [CityController::class, 'create'])->name('create');
        Route::post('city/{country_id}/store', [CityController::class, 'store'])->name('store');
        Route::get('city/edit/{city_id}', [CityController::class, 'edit'])->name('edit');
        Route::post('city/update/{city_id}', [CityController::class, 'update'])->name('update');
        Route::delete('city/delete/{city_id}', [CityController::class, 'delete'])->name('delete');
    });
    //FAQS

    Route::as('faqs.')->group(function () {


        Route::get('faqs', [FaqsController::class, 'index'])->name('index');
        Route::get('get_faqs', [FaqsController::class, 'get_faqs'])->name('get_faqs');

        Route::get('faqs/create', [FaqsController::class, 'create'])->name('create');
        Route::post('faqs/store', [FaqsController::class, 'store'])->name('store');
        Route::get('faqs/edit/{id}', [FaqsController::class, 'edit'])->name('edit');
        Route::post('faqs/update/{id}', [FaqsController::class, 'update'])->name('update');
        Route::post('faqs/status/{id}', [FaqsController::class, 'status'])->name('status');
        Route::delete('faqs/delete/{id}', [FaqsController::class, 'delete'])->name('delete');
    });
    Route::as('policies.')->group(function () {


        Route::get('policies', [PolicyController::class, 'index'])->name('index');
        Route::get('get_policies', [PolicyController::class, 'get_policies'])->name('get_policies');

        Route::get('policies/create', [PolicyController::class, 'create'])->name('create');
        Route::post('policies/store', [PolicyController::class, 'store'])->name('store');
        Route::get('policies/edit/{id}', [PolicyController::class, 'edit'])->name('edit');
        Route::post('policies/update/{id}', [PolicyController::class, 'update'])->name('update');
        Route::post('policies/status/{id}', [PolicyController::class, 'status'])->name('status');
        Route::delete('policies/delete/{id}', [PolicyController::class, 'delete'])->name('delete');
    });
    Route::group([
        'as'=>'settings.'
    ], function () {

        Route::get('settings', [SettingController::class, 'index'])->name('index');
        Route::get('settings/{page_name}', [SettingController::class, 'page'])->name('page');
        Route::get('settings/slider/page', [SettingController::class, 'page_slider'])->name('page_slider');
        Route::post('settings/update_about_us/{page_name}', [SettingController::class, 'update_settings'])->name('page.update_about_us');
        Route::post('settings/update_slider', [SettingController::class, 'update_slider'])->name('page.update_slider');
        Route::post('settings/update_settings/{page_name}', [SettingController::class, 'update_settings'])->name('page.update_settings');
        Route::post('settings/update/{page_name}', [SettingController::class, 'page_update'])->name('page.update');
        Route::post('setting/update', [SettingController::class, 'update'])->name('update');



    });
//Categories
    Route::as('categories.')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('index');
        Route::get('get_categories', [CategoryController::class, 'get_categories'])->name('get_categories');
        Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('store');
        Route::delete('/category/delete/{id}', [CategoryController::class, 'delete'])->name('delete');

    });
    //Categories
    Route::as('feature_categories.')->group(function () {
        Route::get('feature_categories', [FeatureCategoryController::class, 'index'])->name('index');
        Route::get('get_feature_categories', [FeatureCategoryController::class, 'get_feature_categories'])->name('get_feature_categories');
        Route::get('/feature_category/edit/{id}', [FeatureCategoryController::class, 'edit'])->name('edit');
        Route::post('/feature_category/update/{id}', [FeatureCategoryController::class, 'update'])->name('update');
        Route::get('/feature_category/create', [FeatureCategoryController::class, 'create'])->name('create');
        Route::post('/feature_category/store', [FeatureCategoryController::class, 'store'])->name('store');
        Route::delete('/feature_category/delete/{id}', [FeatureCategoryController::class, 'delete'])->name('delete');

    });
    //Categories
    Route::as('property_features.')->group(function () {
        Route::get('property_features', [PropertyFeatureController::class, 'index'])->name('index');
        Route::get('get_property_features', [PropertyFeatureController::class, 'get_property_features'])->name('get_property_features');
        Route::get('/property_feature/edit/{id}', [PropertyFeatureController::class, 'edit'])->name('edit');
        Route::post('/property_feature/update/{id}', [PropertyFeatureController::class, 'update'])->name('update');
        Route::get('/property_feature/create', [PropertyFeatureController::class, 'create'])->name('create');
        Route::post('/property_feature/store', [PropertyFeatureController::class, 'store'])->name('store');
        Route::delete('/property_feature/delete/{id}', [PropertyFeatureController::class, 'delete'])->name('delete');
    });
    //facilities
    Route::as('property_facilities.')->group(function () {
        Route::get('property_facilities', [PropertyFacilityController::class, 'index'])->name('index');
        Route::get('get_property_facilities', [PropertyFacilityController::class, 'get_property_facilities'])->name('get_property_facilities');
        Route::get('/property_facility/edit/{id}', [PropertyFacilityController::class, 'edit'])->name('edit');
        Route::post('/property_facility/update/{id}', [PropertyFacilityController::class, 'update'])->name('update');
        Route::get('/property_facility/create', [PropertyFacilityController::class, 'create'])->name('create');
        Route::post('/property_facility/store', [PropertyFacilityController::class, 'store'])->name('store');
        Route::delete('/property_facility/delete/{id}', [PropertyFacilityController::class, 'delete'])->name('delete');

    });
    //properties
    Route::as('properties.')->group(function () {
        Route::get('properties/{status}', [PropertyController::class, 'index'])->name('index');
        Route::get('get_properties/{status}', [PropertyController::class, 'get_properties'])->name('get_properties');
        Route::get('/property/edit/{id}', [PropertyController::class, 'edit'])->name('edit');
        Route::post('/property/approve-featured/{id}', [PropertyController::class, 'approveFeatured'])->name('approve-featured');
        Route::get('/featured-listings', [PropertyController::class, 'featuredListings'])->name('featured-listings');
        Route::get('/featured-3d-tours', [PropertyController::class, 'featured3dTours'])->name('featured-3d-tours');
        Route::post('/property/approve-featured-3d/{id}', [PropertyController::class, 'approveFeatured3dTour'])->name('approve-featured-3d');
        Route::post('/property/reject-featured-3d/{id}', [PropertyController::class, 'rejectFeatured3dTour'])->name('reject-featured-3d');
        Route::post('/property/update-featured-3d-iframe/{id}', [PropertyController::class, 'updateFeatured3dIframe'])->name('update-featured-3d-iframe');
        Route::post('/property/updateModerationStatus', [PropertyController::class, 'updateModerationStatus'])->name('updateModerationStatus');
        Route::post('/property/update/{id}', [PropertyController::class, 'update'])->name('update');
        Route::get('/property/create', [PropertyController::class, 'create'])->name('create');
        Route::post('/property/store', [PropertyController::class, 'store'])->name('store');
        Route::delete('/property/delete/{id}', [PropertyController::class, 'delete'])->name('delete');
        Route::post('/property/generate-slug', [PropertyController::class, 'generateSlug'])->name('generate.slug');

    });
    //Blogs
    Route::as('blogs.')->group(function () {
        //
        Route::get('blogs', [BlogController::class, 'index'])->name('index');
        Route::get('get_blogs', [BlogController::class, 'get_blogs'])->name('get_blogs');
        Route::get('blogs/edit/{id}', [BlogController::class, 'edit'])->name('edit');
        Route::post('blogs/update/{id}', [BlogController::class, 'update'])->name('update');
        Route::get('blogs/create', [BlogController::class, 'create'])->name('create');
        Route::post('blogs/store', [BlogController::class, 'store'])->name('store');
        Route::post('blogs/status/{id}', [BlogController::class, 'status'])->name('status');
        Route::delete('blogs/delete/{id}', [BlogController::class, 'delete'])->name('delete');

    });
//notification
    Route::as('notification.')->group(function () {
        //
        Route::get('notifications', [NotificationController::class, 'notifications'])->name('notifications');
        Route::get('show/{notify_id}', [NotificationController::class, 'show'])->name('show');
        Route::get('read_all', [NotificationController::class, 'read_all'])->name('read_all');

    });


    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.image-upload');


    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
