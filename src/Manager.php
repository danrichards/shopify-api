<?php

namespace ShopifyApi;

use BadMethodCallException;
use ShopifyApi\Models\Discount;
use ShopifyApi\Models\Metafield;
use ShopifyApi\Models\Order;
use ShopifyApi\Models\Product;
use ShopifyApi\Models\Shop;
use ShopifyApi\Models\Variant;
use ShopifyApi\Models\Webhook;

/**
 * Class Manager
 *
 * @method Client product()
 */
class Manager
{

    use ClientTrait;

    /** @var array $api_cache */
    protected $api_cache = [];

    /**
     * Constructor.
     *
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the instance with the Facade. Shopify::getInstance();
     *
     * @return $this
     */
    public function getInstance()
    {
        return $this;
    }

    /**
     * Get a product by id or create a new one
     *
     * @param int $id the Product id
     *
     * @return Product
     */
    public function getShop()
    {
        return $this->fetchFromApiCache(Shop::class)
            ?: new Shop($this->client);
    }

    /**
     * Get a product by id or create a new one
     *
     * @param int $id the Product id
     *
     * @return Product
     */
    public function getProduct($id = null)
    {
        return $this->fetchFromApiCache(Product::class, $id)
            ?: new Product($this->client, $id);
    }

    /**
     * Get all the products from a response as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param array $params
     * @return \Illuminate\Support\Collection|array
     */
    public function getAllProducts(array $params = [])
    {
        $products = $this->fetchFromApiCache(Product::class, $params)
            ?: (new Product($this->client))->all($params);

        return defined('LARAVEL_START') ? collect($products) : $products;
    }

    /**
     * Get an order by id or create a new one
     *
     * @param int $id the Order id
     *
     * @return Order
     */
    public function getOrder($id = null)
    {
        return $this->fetchFromApiCache(Order::class, $id)
            ?: new Order($this->client, $id);
    }

    /**
     * Get all the orders from a response as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param array $params
     * @return \Illuminate\Support\Collection|array
     */
    public function getAllOrders(array $params = [])
    {
        $orders = $this->fetchFromApiCache(Order::class, $params)
            ?: (new Order($this->client))->all($params);

        return defined('LARAVEL_START') ? collect($orders) : $orders;
    }

    /**
     * Get an order by id or create a new one
     *
     * @param int $id the Webhook id
     * @return Webhook
     */
    public function getWebhook($id = null)
    {
        return $this->fetchFromApiCache(Webhook::class, $id)
            ?: new Webhook($this->client, $id);
    }

    /**
     * Get all the webhooks from a response as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param array $params
     * @return \Illuminate\Support\Collection|array
     */
    public function getAllWebhooks(array $params = [])
    {
        $webhooks = $this->fetchFromApiCache(Webhook::class, $params)
            ?: (new Webhook($this->client))->all($params);

        return defined('LARAVEL_START') ? collect($webhooks) : $webhooks;
    }

    /**
     * Get a variant by id or create a new one
     *
     * @param int $id Variant id
     * @return Variant
     */
    public function getVariant($id = null)
    {
        return $this->fetchFromApiCache(Variant::class, $id)
            ?: new Variant($this->client, $id);
    }

    /**
     * Get all the variants from a response as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param $product_id
     * @return array|\Illuminate\Support\Collection
     */
    public function getAllVariants($product_id)
    {
        $variants = $this->getProduct($product_id)->variants();
        return defined('LARAVEL_START') ? collect($variants) : $variants;
    }

    /**
     * Get a variant by id or create a new one
     *
     * @param int $id Metafield id
     * @return Metafield
     */
    public function getMetafield($id = null)
    {
        return $this->fetchFromApiCache(Metafield::class, $id)
            ?: new Metafield($this->client, $id);
    }

    /**
     * @param string $model
     * @param callable $callback
     */
    public function setApiCache($model, callable $callback) {
        $this->api_cache[$model] = $callback;
    }

    /**
     * @param $model
     * @param $params
     * @return mixed
     */
    public function fetchFromApiCache($model, $params = null)
    {
        if (isset($this->api_cache[$model])) {
            return $this->api_cache[$model]($this->getClient(), $params);
        }

        return null;
    }

    /**
     * @param string $method method name
     * @param array  $args arguments
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (method_exists($this->client, $method)) {
            return call_user_func_array([$this->client, $method], $args);
        } else {
            throw new BadMethodCallException("The Manager __call method fires methods that exist on ".get_class($this->client));
        }
    }

    /**
     * Get a discount by id or create a new one
     *
     * @param int $id the Discount id
     *
     * @return Discount
     */
    public function getDiscount($id = null)
    {
        return $this->fetchFromApiCache(Discount::class, $id)
            ?: new Discount($this->client, $id);
    }

    /**
     * Get all the discounts as an array of Models or a
     * Collection of Models for Laravel.
     *
     * @param array $params
     * @return \Illuminate\Support\Collection|array
     */
    public function getAllDiscounts(array $params = [])
    {
        $discounts = $this->fetchFromApiCache(Discount::class, $params)
            ?: (new Discount($this->client))->all($params);

        return defined('LARAVEL_START') ? collect($discounts) : $discounts;
    }

}
