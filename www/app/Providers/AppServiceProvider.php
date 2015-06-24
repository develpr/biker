<?php

namespace FindMeABike\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	private $simpleBindings = [
		\FindMeABike\Contracts\Repositories\DivvyStationRepository::class => \FindMeABike\Repositories\EloquentDivvyStationRepository::class,
		\FindMeABike\Contracts\GeocodingService::class => \FindMeABike\Services\Data\GoogleGeocodingService::class,
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
    }
}
