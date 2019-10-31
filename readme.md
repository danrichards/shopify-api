# Shopify API (Deprecated, uses Guzzle 3.9)

> Please note: the **new version** using Guzzle 6.2 is [actively maintained here](https://github.com/danrichards/shopify)

An object-oriented approach towards using the Shopify API. 

## Supported Objects / Endpoints:

* [CustomCollection](https://help.shopify.com/api/reference/customcollection)
* [Discount](https://help.shopify.com/api/reference/discount) - Shopify Plus
* [Fulfillment](https://help.shopify.com/api/reference/fulfillment) - via Order
* [FulfillmentEvent](https://help.shopify.com/api/reference/fulfillmentevent) - via Order
* [Metafield](https://help.shopify.com/api/reference/metafield)
* [Order](https://help.shopify.com/api/reference/order)
* [OrderRisk](https://help.shopify.com/api/reference/order_risks) - via Order
* [Product](https://help.shopify.com/api/reference/product)
* [ProductImage](https://help.shopify.com/api/reference/product_image)
* [ProductVariant](https://help.shopify.com/api/reference/product_variant)
* [Webhook](https://help.shopify.com/api/reference/webhook)

## Composer

    $ composer require dan/shopify-api v0.9.9.*
    
## Usage without Laravel

```
// Assumes setup of client with access token.
$mgr = ShopifyApi\Manager::init($shop, $token);
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
    
### Replace following variables in your `.env`
    
```
SHOPIFY_DOMAIN=your-shop-name.myshopify.com
SHOPIFY_TOKEN=your-token-here
```
    
### Using the Facade gives you `Manager`

Methods called on `Manager` will cascade down onto `Client` via the `__call` method.

> If you're using Laravel, `Models` will return `\Illuminate\Support\Collection` instead of `array`.

```
Shopify::getProduct($product_id = 123);     // returns ShopifyApi/Models/Product
Shopify::getAllProducts();                  // returns Collection|array of ShopifyApi/Models/Product

Shopify::getVariant($variant_id = 456);     // returns ShopifyApi/Models/Variant
Shopify::getAllVariants($product_id = 123); // returns Collection|array of ShopifyApi/Models/Variant

Shopify::getOrder($order_id = 789);         // returns ShopifyApi/Models/Order
Shopify::getAllOrders();                    // returns a Collection|array of ShopifyApi/Models/Order

Shopify::getMetafield($metafield_id = 123); // returns ShopifyApi/Models/Metafield

Shopify::getDiscount($dicount_id = 123);    // returns ShopifyApi/Models/Discount
Shopify::getAllDiscounts();                 // returns Collection|array of ShopifyApi/Models/Discount

Shopify::getAllWebhooks();                  // returns Collection|array of ShopifyApi/Models/Webhook

// Alternatively, we may call methods on the API object.

Shopify::api('products')->show($product_id = 123);           // returns array
Shopify::api('products')->all();                             // returns array
Shopify::api('products')->count();                           // returns int

Shopify::api('products')->metafields($product_id = '1234')   // returns array

Shopify::api('variants')->show($variant_id = 456);           // returns array
Shopify::api('variants')->product($product_id = 123)->all(); // returns array

Shopify::api('orders')->show($order_id = 123);               // returns array
Shopify::api('orders')->all();                               // returns array
Shopify::api('orders')->count();                             // returns int

Shopify::api('custom_collections')->show($cc_id = 123);      // returns array
Shopify::api('custom_collections')->all();                   // returns array
Shopify::api('custom_collections')->count();                 // returns int

Shopify::api('discounts')->show($discount_id = 123);         // returns array
Shopify::api('discounts')->all();                            // returns array               
Shopify::api('discounts')->count();                          // returns int

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

##### Add a product to a collection

```
$collection = Shopify::getCustomCollection(123);
$collection->add(456);
```

or

```
$collection = Shopify::getCustomCollection(123);
$collection->add([456,789]);
```

##### Creating and updating metafields for resources is more intuitive using key / namespace.

```
// The 'value_type' property will be determined automatically if omitted
$product->createMetafield('in_stock', 'inventory', ['value' => 123]); 

$product->updateMetafield('in_stock', 'inventory', ['value' => 122]);

$product->updateOrCreateMetafield('in_stock', 'inventory', ['value' => 200]);

// Support is included for arrays and objects (json encodable) and null
$product->createMetafield('board_specs', 'criteria', ['value' => new MyJsonSerializableObject()]);
```

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
    ['shop' => '...', 'timestamp' => '...', ...]
);
```

#### Verify App Webhook Hmac

```
ShopifyApi\Util::validWebhookHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    file_get_contents('php://input')
);
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
* Publish files for Laravel setup
* Artisan Command to create token

## License

MIT.
