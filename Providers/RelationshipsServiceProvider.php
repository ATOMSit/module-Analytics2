<?php

namespace Modules\Analytics\Providers;

use App\Customer;
use App\Website;
use Illuminate\Support\ServiceProvider;
use Modules\Analytics\Entities\Google;

class RelationshipsServiceProvider extends ServiceProvider
{
    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        Website::addExternalMethod('analytics_google', function () {
            return $this->hasOne(Google::class);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
