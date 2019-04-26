<?php

namespace App\Providers;

use Auth;
use App\User;
use App\Book;
use App\Magazine;
use App\Purchase;
use App\Order;
use App\Policies\UserPolicy;
use App\Policies\BookPolicy;
use App\Policies\MagazinePolicy;
use App\Policies\PurchasePolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
   protected $policies = [
        User::class => UserPolicy::class,
        Book::class => BookPolicy::class,
        Magazine::class => MagazeinPolicy::class,
        Purchase::class => PurchasePolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
