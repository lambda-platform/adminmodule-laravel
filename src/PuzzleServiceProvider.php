<?php

namespace Lambda\Puzzle;

use Illuminate\Support\ServiceProvider;

class PuzzleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__.'/routes.php';
    }

    public function register()
    {
        $this->app->singleton(Puzzle::class, function () {
            return new Puzzle();
        });

        $this->app->alias(VB::class, 'puzzle');
        $this->loadViewsFrom(__DIR__.'/views', 'puzzle');
    }
}
