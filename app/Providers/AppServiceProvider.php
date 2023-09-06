<?php

namespace App\Providers;

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
        // $api = new LicenseBoxExternalAPI();
        // if ( !app()->runningInConsole() ) {
        //     $res = $api->verify_license();

        // if($res['status']!=true){
        //     header( "refresh:5;url=". url('/activate/index.php') );
        //     die('Your license is invalid, please contact support.');
        // }
        // }
        if (Schema::hasTable('departments')) {
            View::share('departments', Department::all());
            View::share('generalSettings', GeneralSettings::first());
        }
        Http::macro('hesabat', function () {
            return Http::acceptJson()->withOptions([
                'verify' => false,
            ])->baseUrl(Config('external.hesabat_base_url'));
        });
        require app_path('helpers/helper.php');
    }
}
