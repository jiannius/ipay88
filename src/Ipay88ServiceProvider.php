<?php

namespace Jiannius\Ipay88;

use Illuminate\Support\ServiceProvider;

class Ipay88ServiceProvider extends ServiceProvider
{
    // register
    public function register() : void
    {
        //
    }

    // boot
    public function boot() : void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ipay88');
        $this->app->bind('ipay88', fn($app) => new \Jiannius\Ipay88\Ipay88());
    }
}