# Shopify API

An object-oriented approach towards using the Shopify API. It is currently a work in progress and only supports:

* [Order](https://help.shopify.com/api/reference/order)
* [Product](https://help.shopify.com/api/reference/product)
* [Variant](https://help.shopify.com/api/reference/product_variant)
* [Metafield](https://help.shopify.com/api/reference/metafield)
* [Discount](https://help.shopify.com/api/reference/discount) - Shopify Plus
* [Webhook](https://help.shopify.com/api/reference/webhook)

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

#### These are all effectively the same thing

* `$p = (new Manager($shopify_client))->getProduct(123); // see above for $shopify_client`
* `$p = new \ShopifyApi\Models\Product($shopify_client, 123);`
* `$p = new \ShopifyApi\Models\Product($shopify_client, Shopify::api('products')->show(123));`
* `$p = Shopify::getProduct(123);`

#### Examples of getting data.

```
Shopify::getProduct($product_id = 123);     // returns ShopifyApi/Models/Product

Shopify::getAllProducts();                  // returns Collection of ShopifyApi/Models/Product

Shopify::getVariant($variant_id = 456);     // returns ShopifyApi/Models/Variant

Shopify::getAllVariants($product_id = 123); // returns Collection of ShopifyApi/Models/Variant

Shopify::getOrder($order_id = 789);         // returns ShopifyApi/Models/Order

Shopify::getAllOrders();                    // returns a Collection of ShopifyApi/Models/Order

Shopify::getMetafield($metafield_id = 123); // returns ShopifyApi/Models/Metafield

Shopify::getDiscount($dicount_id = 123);    // returns ShopifyApi/Models/Discount

Shopify::getAllDiscounts();                 // returns Collection of ShopifyApi/Models/Discount

Shopify::getAllWebhooks();                  // returns Collection of ShopifyApi/Models/Webhook

// Alternatively, we may call methods on the API object.

Shopify::api('products')->show($product_id = 123);           // returns array

Shopify::api('products')->all();                             // returns array

Shopify::api('products')->count();                           // returns int

Shopify::api('variants')->show($variant_id = 456);           // returns array

Shopify::api('variants')->product($product_id = 123)->all(); // returns array

Shopify::api('orders')->show($order_id = 123);               // returns array

Shopify::api('orders')->all();                               // returns array

Shopify::api('orders')->count();                             // returns int

Shopify::api('discounts')->show($discount_id = 123);         // returns array

Shopify::api('discounts')->all();                            // returns array

Shopify::api('webhooks')->show($webhook_id = 123);           // returns array

Shopify::api('webhooks')->all();                             // returns array

Shopify::api('webhooks')->count();                           // returns int
```

#### Examples of saving data.

##### Creating a product using a model

```
$product = Shopify::getProduct();
$product->setTitle('Burton Custom Freestyle 151');
$product->setBodyHtml('<strong>Good snowboard!<\/strong>');
$product->setVendor('Burton');
$product->setProductType('Snowboard');
$product->setTags(['Barnes & Noble', 'John\'s Fav', '"Big Air"']);
$product->save();
```

##### Updating a product using a model

```
$product = Shopify::getProduct(123);
$product->setTitle('Burton Freestyle 152');
$product->save();
```

##### Creating and updating metafields for resources is more intuitive using key / namespace.

```
// The 'value_type' property will be determined automatically if omitted
$product->createMetafield('in_stock', 'inventory', ['value' => 123]); 

$product->updateMetafield('in_stock', 'inventory', ['value' => 122]);

$product->updateOrCreateMetafield('in_stock', 'inventory', ['value' => 200]);

// Support is included for arrays and objects (json encodable) and null
$product->createMetafield('board_specs', 'criteria', ['value' => new MyJsonSerializbleObject()]);
```

Methods called on `Manager` will cascade down onto `Client` via the `__call` method.

## Embedded Apps

#### Get a token for a redirect response.

```
Shopify::getAppInstallResponse(
    'your_app_client_id', 
    'your_app_client_secret',
    'shop_from_request',
    'code_from_request'
);

// returns (object) ['access_token' => '...', 'scopes' => '...']
```

#### Verify App Hmac (works for callback or redirect)

```
ShopifyApi\Util::validAppHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    ['shop' => '...', 'timestamp' => '...', ...])
```

#### Verify App Webhook Hmac

```
ShopifyApi\Util::validWebhookHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    file_get_contents('php://input')
)
```

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
            // If the cache is empty, the resource will be fetched from the api
            // as normal.
            $data = Cache::get($key);
            
            return $data ? new Product($client, $data) : null;
        });
    }
}
```

## Contributors

- [Diogo Gomes](https://github.com/diogogomeswww)

## Special Thanks

This repository's structure was modeled after the robust [`cdaguerre/php-trello-api`](https://github.com/cdaguerre/php-trello-api).

## Todo

* Migrate from `guzzle/guzzle` to `guzzlehttp/guzzle`, bump version.
* Model support for deletion. A `remove()` method.

## License

MIT.