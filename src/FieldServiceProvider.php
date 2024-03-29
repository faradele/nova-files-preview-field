<?php

namespace Faradele\Files;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('files', __DIR__.'/../dist/js/field.js');
            // Nova::style('files', __DIR__.'/../dist/css/field.css');
        });

        $this->publishes([
            __DIR__.'/../resources/images' => public_path('vendor/files/images'),
        ], 'files-nova-package-assets');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
