<?php

namespace Faradele\Files;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    public static Closure|null $logImageViewHistoryCallback = null;

    public static function setLogImageViewHistoryCallback(Closure $callback): void
    {
        static::$logImageViewHistoryCallback = $callback;
    }

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
        // * Register the URL for logging image view
        Route::middleware(config('nova.api_middleware'))
            ->post('/nova-vendor/nova-files-preview-field/log-view', function (Request $request) {
                if (static::$logImageViewHistoryCallback) {
                    $request->validate([
                        'attachable_id' => ['required', 'integer'],
                        'attachable_type' => ['required', 'string'],
                        'media_id' => ['required', 'integer'],
                        'viewed_at' => ['required', 'date'],
                    ]);

                    return call_user_func(static::$logImageViewHistoryCallback, request());
                }
            });
            // // * block help ensure that the requests come in one after the other
            // // * to account for when the lightbox is opened multiple times for the same image.
            // ->block(10, 60 * 5);
    }
}
