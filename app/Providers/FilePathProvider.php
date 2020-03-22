<?php

namespace NeubusSrm\Providers;

use Illuminate\Support\ServiceProvider;
use NeubusSrm\Lib\Builders\FileSystem\Drivers\FilePathDriver;
use NeubusSrm\Lib\Builders\FileSystem\Drivers\Impl\DiskFilePathDriver;
use NeubusSrm\Lib\Builders\FileSystem\PathBinders\PathBinder;

/**
 * Class FilePathProvider
 * Provider implementation for Laravel
 * Package is not framework dependant
 * @package NeubusSrm\Providers
 */
class FilePathProvider extends ServiceProvider
{
    protected $driverClasses = [
        'local' => DiskFilePathDriver::class
    ];
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        // currently only supports one file path driver at a type

        $this->bindDriver();

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Drivers are bound by a name instead of by a class. Adding a driver name here that corresponds to
     * something in the config file will allow the proper implementation to be loaded.
     * @return void
     */
    public function bindDriver() : void {

        $this->app->singleton(PathBinder::class, function($app) {
            $binderClass = config('srm.file_storage.local.binder_class');
            return new $binderClass;
        });
        $driverClasses = $this->driverClasses;
        $this->app->singleton(FilePathDriver::class, function($app) use ($driverClasses) {
            $driver = config('srm.file_storage.driver');
            return new $driverClasses[$driver];
        });
    }
}
