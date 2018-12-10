<?php

namespace ShopifyApi\Providers;

use ShopifyApi\Manager;
use Guzzle\Http\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use ShopifyApi\Client as ShopifyClient;
use ShopifyApi\HttpClient as ShopifyHttpClient;

/**
 * Class ShopifyServiceProvider
 *
 * Service Provider for the Laravel Framework
 */
class ShopifyServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('shopify-api', function ($app) {
            return Manager::init(env('SHOPIFY_DOMAIN'), env('SHOPIFY_TOKEN'));
        });
    }

}
