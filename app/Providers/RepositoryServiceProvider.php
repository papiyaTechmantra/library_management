<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\UserInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\EventInterface;
use App\Interfaces\DepartmentInterface;
use App\Interfaces\ProductInterface;

use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\EventRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(EventInterface::class, EventRepository::class);
        $this->app->bind(DepartmentInterface::class, DepartmentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
