<?php

namespace App\Providers;

use App\Contracts\DataProviderInterface;
use App\Contracts\ExchangeRateServiceInterface;
use App\Services\CbrService;
use App\Services\CbrXmlDataProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
	public function register(): void
	{
		$this->app->bind(ExchangeRateServiceInterface::class, CbrService::class);
		$this->app->bind(DataProviderInterface::class, CbrXmlDataProvider::class);
	}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
