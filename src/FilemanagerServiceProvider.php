<?php

namespace craigdavey\filemanger;

use Illuminate\Support\ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('craigdavey\filemanager\FilemanagerController');

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__.'/routes.php';
        $this->loadViewsFrom(__DIR__.'/views', 'filemanager');
        $this->publishes([
            __DIR__.'/config/filemanager.php' => config_path('filemanager.php'),
        ]);
    }
}
