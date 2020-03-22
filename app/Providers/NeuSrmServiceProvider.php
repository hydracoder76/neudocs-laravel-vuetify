<?php

namespace NeubusSrm\Providers;

use Illuminate\Support\ServiceProvider;
use NeubusSrm\Lib\Facades\Impl\Neulog;

/**
 * Class NeuSrmServiceProvider
 * @package NeubusSrm\Providers
 */
class NeuSrmServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('neulog', static function() {
            return new Neulog();
        });
    }
}
