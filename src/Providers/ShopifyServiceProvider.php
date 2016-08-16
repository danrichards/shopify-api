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
     *
     * @return void
     */
    public function register()
    {
        // By default, let's setup our main shopify shop.
        $config = [
            'base_url' => "https://" . env('SHOPIFY_DOMAIN') . "/",
            'request.options' => [
                'headers' => [
                    'X-Shopify-Access-Token' => env('SHOPIFY_TOKEN'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json; charset=utf-8;'
                ]
            ]
        ];

        $this->app->singleton('shopify', function ($app) use ($config) {
            // Guzzle does our REST client and is immutable
            $guzzle_http_client = new GuzzleClient($config['base_url'], $config);

            // ShopifyHttpClient decorates GuzzleClient
            $shopify_http_client = new ShopifyHttpClient($config, $guzzle_http_client);

            // ShopifyClient decorates our ShopifyHttpClient. We may swap in a
            // different client (ie. different shop) later, if need be.
            $shopify_client = new ShopifyClient($shopify_http_client);

            // Manager is a singleton we may pull down out of the IoC container
            // with the active ShopifyClient we're working with. There is also
            // a facade `Shopify` which provides the Manager.
            return new Manager($shopify_client);
        });
    }

}
