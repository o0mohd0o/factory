<?php

namespace App\Providers;

use App\Models\Worker;
use App\Models\Department;
use App\Models\GeneralSettings;
use Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Providers\LicenseBoxExternalAPI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('departments')) {
            View::share('departments', Department::all());
            View::share('generalSettings', GeneralSettings::first());
        }
        View::share('workers', Worker::all());
        Http::macro('hesabat', function () {
            return Http::acceptJson()->withOptions([
                'verify' => false,
            ])->baseUrl(Config('external.hesabat_base_url'));
        });
        require app_path('Helpers/helper.php');
    }
}
