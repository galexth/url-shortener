<?php

namespace App\Providers;

use App\Components\Decoder\Decoder;
use App\Components\Decoder\DecoderInterface;
use App\Models\Url;
use App\Repositories\UrlRepository;
use App\Repositories\UrlRepositoryInterface;
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

        $this->app->singleton(UrlRepositoryInterface::class, function ($app) {
            return new UrlRepository(new Url(), app(DecoderInterface::class));
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
