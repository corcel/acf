<?php

namespace Corcel\Acf\Laravel;

use Illuminate\Support\ServiceProvider;

/**
 * Class CorcelAcfServiceProvider
 * @package Corcel\Acf\Laravel
 */
class CorcelAcfServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('corcel.php'),
        ], 'config');
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
}
