<?php

namespace FindMeABike\Providers;

use App;
use FindMeABike\Device;
use Illuminate\Support\ServiceProvider;
use Validator;
use Response;
use FindMeABike\Validators\DeviceCodeValidator;

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
        $this->registerEloquentListeners();
		$this->registerValidators();
		$this->registerErrorHandlers();
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

	private function registerErrorHandlers(){
	}

	private function registerEloquentListeners()
	{
		Device::creating(function (Device $device) {
			$device->generateDeviceCode();
			return true;
		});
	}

	private function registerValidators()
	{
		Validator::extend('device_code', DeviceCodeValidator::class . '@validate');
	}
}
