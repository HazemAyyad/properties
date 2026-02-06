<?php

namespace App\Providers;
use App\Http\View\Composers\MainData;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Jorenvh\Share\ShareFacade;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Share', ShareFacade::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['*'],MainData::class);
    }
}
