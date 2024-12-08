<?php

namespace App\Providers;

use App\Repositories\CbrRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
	public function register(): void
	{
		$this->app->singleton(CbrRepository::class, function ($app) {
			return new CbrRepository();
		});
	}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
