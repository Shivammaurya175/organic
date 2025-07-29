<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\MergeCartOnLogin::class,
    ],
];

}
