<?php

namespace App\Providers;

use App\Components\Decoder\Decoder;
use App\Components\Decoder\DecoderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DecoderInterface::class, function ($app) {
            return new Decoder(config('decoder.index'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
