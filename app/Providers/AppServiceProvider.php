<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        view()->composer('*', function($view) {

            $current_url = url()->current();
            $re_url = str_replace(url('/'),'',$current_url);
            $re_url = ltrim($re_url,'/');
            $slug = explode('/',$re_url);
            $module_slug = null;
            if(isset($slug[0])){
                $module_slug =  $slug[0];
            }

            $view->with('url_slug', $slug);
            $view->with('module_slug', $module_slug);

            Blade::directive('auth_access', function ($expression) {
                return "<?php if (in_array($expression,auth()->user()->getDirectPermissions()->pluck('name')->toArray())) { ?>";
            });

            Blade::directive('end_auth_access', function () {
                return '<?php } ?>';
            });
        });
    }
}
