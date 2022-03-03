<?php


namespace Modules\Core\Repositories;


use App\Providers\AppServiceProvider;
use Modules\Core\Repositories\Auth\ModuleRepository;
use Modules\Core\Repositories\Auth\SubModuleRepository;
use Modules\Core\Repositories\Auth\UserRepository;
use Modules\Core\Repositories\Contracts\Auth\ModuleInterface;
use Modules\Core\Repositories\Contracts\Auth\SubModuleInterface;
use Modules\Core\Repositories\Contracts\Auth\UserInterface;
use Modules\Core\Repositories\Contracts\RetailUserRepositoryInterface;


class RepositoriesServiceProvider extends AppServiceProvider
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

        $this->app->bind(ModuleInterface::class, ModuleRepository::class);
        $this->app->bind(SubModuleInterface::class, SubModuleRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);

//        $this->app->bind(RetailUserRepositoryInterface::class, RetailUserRepository::class);
    }

}
