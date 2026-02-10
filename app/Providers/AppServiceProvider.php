<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema; // Bu satır mutlaka olmalı
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        /**
         * MySQL 5.7 ve altı veya eski MariaDB sürümlerinde 
         * "index key too long" hatasını önlemek için varsayılan 
         * string uzunluğunu 191 olarak belirliyoruz.
         */
        Schema::defaultStringLength(191);
    }
}