<?php namespace Biker\Providers;

use Biker\Device;
use Illuminate\Support\ServiceProvider;
use Biker\Validators\DeviceCodeValidator;

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
        \Validator::extend('device_code', DeviceCodeValidator::class . '@validate');
    }
}
