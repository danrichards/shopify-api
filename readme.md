# Shopify API

An object-oriented approach towards using the Shopify API. It is currently a work in progress and only supports:

* [Order](https://help.shopify.com/api/reference/order)
* [Product](https://help.shopify.com/api/reference/product)
* [Variant](https://help.shopify.com/api/reference/product_variant)

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

$mgr->getProduct($product_id = 123);              // returns ShopifyApi/Models/Product

// Alternatively, we may call methods on the API object.

$mgr->api('products')->show($product_id = 123);   // returns array

See Facade usages for other methods available.
```

## Usage with Laravel

In your `config/app.php`

### Add the following to your `providers` array:

    ShopifyApi\Providers\ShopifyServiceProvider::class,
    
### Add the following to your `aliases` array:

    'Shopify' => ShopifyApi\Support\ShopifyFacade::class,
    
### Using the Facade gives you `Manager`

```
Shopify::getProduct($product_id = 123);     // returns ShopifyApi/Models/Product

Shopify::getAllProducts();                  // returns Collection of ShopifyApi/Models/Product

Shopify::getVariant($variant_id = 456);     // returns ShopifyApi/Models/Variant

Shopify::getAllVariants($product_id = 123); // returns Collection of ShopifyApi/Models/Variant

Shopify::getOrder($order_id = 789);         // returns ShopifyApi/Models/Order

Shopify::getAllOrders();                    // returns a Collection of ShopifyApi/Models/Order

// Alternatively, we may call methods on the API object.

Shopify::api('products')->show($product_id = 123);           // returns array

Shopify::api('products')->all();                             // returns array

Shopify::api('products')->count();                           // returns int

Shopify::api('variants')->show($variant_id = 456);           // returns array

Shopify::api('variants')->product($product_id = 123)->all(); // returns array

Shopify::api('orders')->show($order_id = 123);               // returns array

Shopify::api('orders')->all();                               // returns array

Shopify::api('orders')->count();                             // returns int
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
            
            // Assuming you Cache::put($key, $product->getData()); elsewhere
            $data = Cache::get($key);
            
            return $data ? new Product($client, $data) : null;
        });
    }
}
```

## Special Thanks

This repository's structure was modeled after the robust [`cdaguerre/php-trello-api`](https://github.com/cdaguerre/php-trello-api).

## License

MIT.