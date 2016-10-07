# Shopify API

An object-oriented approach towards using the Shopify API. It is currently a work in progress and only supports:

* [Order](https://help.shopify.com/api/reference/order)
* [Product](https://help.shopify.com/api/reference/product)

## Composer

    $ composer require dan/shopify-api dev-master
    
## Usage without Laravel

```
// Assumes setup of client with access token.
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

// Guzzle does our REST and is immutable
$guzzle_http_client = new GuzzleClient($config['base_url'], $config);

// ShopifyHttpClient decorates GuzzleClient
$shopify_http_client = new ShopifyHttpClient($config, $guzzle_http_client);

// ShopifyClient decorates our ShopifyHttpClient. We may swap in a
// different client (ie. different shop) later, if need be.
$shopify_client = new ShopifyClient($shopify_http_client);

// Manager provide some conveniences and is available to bound to a container.
$mgr = new Manager($shopify_client);

$mgr->getProduct(123);              // returns ShopifyApi/Models/Product

$mgr->getAllProducts();             // returns Collection of ShopifyApi/Models/Product

$mgr->getOrder(12345);              // returns ShopifyApi/Models/Order

$mgr->getAllOrders();               // returns a Collection of ShopifyApi/Models/Order

// Alternatively, we may call methods on the API object.
$mgr->api('products')->show(123);    // returns ShopifyApi/Models/Product

$mgr->api('products')->all();        // returns Collection of ShopifyApi/Models/Product

$mgr->api('products')->count();      // returns # of products
```

## Usage with Laravel

In your `config/app.php`

### Add the following to your `providers` array:

    ShopifyApi\Providers\ShopifyServiceProvider::class,
    
### Add the following to your `aliases` array:

    'Shopify' => ShopifyApi\Support\ShopifyFacade::class,
    
### Using the Facade gives you `Manager`

```
Shopify::getProduct(123);               // returns ShopifyApi/Models/Product

Shopify::getAllProducts();              // returns Collection of ShopifyApi/Models/Product

Shopify::getOrder(12345);               // returns ShopifyApi/Models/Order

Shopify::getAllOrders();                // returns a Collection of ShopifyApi/Models/Order

// Alternatively, we may call methods on the API object.
Shopify::api('products')->show(123);    // returns ShopifyApi/Models/Product

Shopify::api('products')->all();        // returns Collection of ShopifyApi/Models/Product

Shopify::api('products')->count();      // returns # of products
```

Methods called on `Manager` will cascade down onto `Client` via the `__call` method.

## Caching

If you want to sprinkle a little caching in, setup a service provider and extend the `\ShopifyApi\Providers\ShopifyServiceProvider`.

#### Here is an example Provider:

```
<?php

namespace App\Providers;

use App;
use ShopifyApi\Manager;
use ShopifyApi\Models\Product;
use ShopifyApi\Providers\ShopifyServiceProvider as BaseServiceProvider;

/**
 * Class ShopifyServiceProvider
 */
class ShopifyServiceProvider extends BaseServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        /** @var Manager $shopify */
        $shopify = app('shopify');

        $shopify->setApiCache(Product::class, function($client, $params = null) {

            // No caching for collections.
            if (is_array($params)) {
                // Returning falsy will result in the default api behavior.
                return null;
            }
            
            $key = "shopify_product_".((string) $params);

            // For example: Using Laravel Cache Facade
            return Cache::remember($key, 15, function() use ($params) {
                return Shopify::getProduct((string) $params);
            });
        });
    }
}
```

## Special Thanks

This repository's structure was modeled after the robust [`cdaguerre/php-trello-api`](https://github.com/cdaguerre/php-trello-api).

## License

MIT.