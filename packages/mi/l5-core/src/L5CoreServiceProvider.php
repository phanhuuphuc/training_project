<?php

namespace Mi\L5Core;

use Illuminate\Support\ServiceProvider;
use Mi\L5Core\Commands\MakeRepository;
use Mi\L5Core\Commands\MakeService;
use Mi\L5Core\Commands\MakeFilter;
use Mi\L5Core\Commands\MakeCriteria;

class L5CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepository::class,
                MakeService::class,
                MakeFilter::class,
                MakeCriteria::class
            ]);
        }
    }
}
