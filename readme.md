# Shopify API

An object-oriented approach towards using the Shopify API. It is currently a work in progress and only supports:

* Orders
* Products

## Composer

    $ composer require dan/shopify-api
    
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

$mgr->getProduct(123); // returns ShopifyApi/Models/Product

$mgr->getOrder(12345); // returns ShopifyApi/Models/Order
```

## Usage with Laravel

In your `config/app.php`

### Add the following to your `providers` array:

    `ShopifyApi\Providers\ShopifyServiceProvider::class,`
    
### Add the following to your `aliases` array:

    `'Shopify' => ShopifyApi\Support\ShopifyFacade::class,`
    
### Using the Facade gives you `Manager`

```
Shopify::getProduct(123);               // returns ShopifyApi/Models/Product

Shopify::getOrder(12345);               // returns ShopifyApi/Models/Order

Shopify::api('products')->show(123);    // returns ShopifyApi/Models/Product
```

Methods called on `Manager` will cascade down onto `Client` via the `__call` method.

## Special Thanks

This repository's structure was modeled after the robust [`cdaguerre/php-trello-api`](https://github.com/cdaguerre/php-trello-api).