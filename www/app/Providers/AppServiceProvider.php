<?php

namespace Biker\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private $simpleBindings = [
        \Biker\Contracts\Repositories\DivvyStationRepository::class => \Biker\Repositories\EloquentDivvyStationRepository::class,
        \Biker\Contracts\GeocodingService::class => \Biker\Services\Data\GoogleGeocodingService::class,
    ];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->simpleBindings as $contract => $service) {
            $this->app->bind($contract, $service);
        }
        //
    }
}
