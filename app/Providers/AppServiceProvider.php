<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Observers\UserObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Schedule $schedule): void
    {
        Paginator::useTailwind();

        User::observe(UserObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);

        View::composer('shared.header', function ($view) {
        $view->with('categories', Category::all());
    });
    }
}