<?php

namespace ShopifyApi\Api;

use BadMethodCallException;
use ShopifyApi\Client;

/**
 * Class Webhook
 *
 * API calls that can be carried out on a Webhook
 * @link https://help.shopify.com/api/reference/webhook
 */
class Webhook extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'webhooks';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'webhooks';

    /** @var string $path */
    protected static $path = '/admin/webhooks/#id#.json';

    /** @var array $fields */
    public static $fields = [
        'address',
        'created_at',
        'fields',
        'format',
        'id',
        'metafield_namespaces',
        'topic',
        'updated_at',
    ];


    const CARTS_CREATE = 'carts/create';
    const CARTS_UPDATE = 'carts/update';
    const CHECKOUTS_CREATE = 'checkouts/create';
    const CHECKOUTS_DELETE = 'checkouts/delete';
    const CHECKOUTS_UPDATE = 'checkouts/update';
    const COLLECTION_LISTINGS_ADD = 'collection_listings/add';
    const COLLECTION_LISTINGS_REMOVE = 'collection_listings/remove';
    const COLLECTION_LISTINGS_UPDATE = 'collection_listings/update';
    const COLLECTIONS_CREATE = 'collections/create';
    const COLLECTIONS_DELETE = 'collections/delete';
    const COLLECTIONS_UPDATE = 'collections/update';
    const CUSTOMER_GROUPS_CREATE = 'customer_groups/create';
    const CUSTOMER_GROUPS_DELETE = 'customer_groups/delete';
    const CUSTOMER_GROUPS_UPDATE = 'customer_groups/update';
    const CUSTOMERS_CREATE = 'customers/create';
    const CUSTOMERS_DELETE = 'customers/delete';
    const CUSTOMERS_DISABLE = 'customers/disable';
    const CUSTOMERS_ENABLE = 'customers/enable';
    const CUSTOMERS_UPDATE = 'customers/update';
    const DISPUTES_CREATE = 'disputes/create';
    const DISPUTES_UPDATE = 'disputes/update';
    const DRAFT_ORDERS_CREATE = 'draft_orders/create';
    const DRAFT_ORDERS_DELETE = 'draft_orders/delete';
    const DRAFT_ORDERS_UPDATE = 'draft_orders/update';
    const FULFILLMENT_EVENTS_CREATE = 'fulfillment_events/create';
    const FULFILLMENT_EVENTS_DELETE = 'fulfillment_events/delete';
    const FULFILLMENTS_CREATE = 'fulfillments/create';
    const FULFILLMENTS_UPDATE = 'fulfillments/update';
    const ORDER_TRANSACTIONS_CREATE = 'order_transactions/create';
    const ORDERS_CANCELLED = 'orders/cancelled';
    const ORDERS_CREATE = 'orders/create';
    const ORDERS_DELETE = 'orders/delete';
    const ORDERS_FULFILLED = 'orders/fulfilled';
    const ORDERS_PAID = 'orders/paid';
    const ORDERS_PARTIALLY_FULFILLED = 'orders/partially_fulfilled';
    const ORDERS_UPDATED = 'orders/updated';
    const PRODUCT_LISTINGS_ADD = 'product_listings/add';
    const PRODUCT_LISTINGS_REMOVE = 'product_listings/remove';
    const PRODUCT_LISTINGS_UPDATE = 'product_listings/update';
    const PRODUCTS_CREATE = 'products/create';
    const PRODUCTS_DELETE = 'products/delete';
    const PRODUCTS_UPDATE = 'products/update';
    const REFUNDS_CREATE = 'refunds/create';
    const SHOP_UPDATE = 'shop/update';
    const THEMES_CREATE = 'themes/create';
    const THEMES_DELETE = 'themes/delete';
    const THEMES_PUBLISH = 'themes/publish';
    const THEMES_UPDATE = 'themes/update';

    /** @var array $topics*/
    public static $topics = [
        self::CARTS_CREATE, self::CARTS_UPDATE,
        self::CHECKOUTS_CREATE, self::CHECKOUTS_DELETE, self::CHECKOUTS_UPDATE,
        self::COLLECTION_LISTINGS_ADD, self::COLLECTION_LISTINGS_REMOVE, self::COLLECTION_LISTINGS_UPDATE,
            self::COLLECTIONS_CREATE, self::COLLECTIONS_DELETE, self::COLLECTIONS_UPDATE,
        self::CUSTOMER_GROUPS_CREATE, self::CUSTOMER_GROUPS_DELETE, self::CUSTOMER_GROUPS_UPDATE,
            self::CUSTOMERS_CREATE, self::CUSTOMERS_DELETE, self::CUSTOMERS_DISABLE, self::CUSTOMERS_ENABLE, self::CUSTOMERS_UPDATE,
        self::DISPUTES_CREATE, self::DISPUTES_UPDATE,
        self::DRAFT_ORDERS_CREATE, self::DRAFT_ORDERS_DELETE, self::DRAFT_ORDERS_UPDATE,
        self::FULFILLMENT_EVENTS_CREATE, self::FULFILLMENT_EVENTS_DELETE, self::FULFILLMENTS_CREATE, self::FULFILLMENTS_UPDATE,
        self::ORDER_TRANSACTIONS_CREATE, self::ORDERS_CANCELLED, self::ORDERS_CREATE, self::ORDERS_DELETE,
            self::ORDERS_FULFILLED, self::ORDERS_PAID, self::ORDERS_PARTIALLY_FULFILLED, self::ORDERS_UPDATED,
        self::PRODUCT_LISTINGS_ADD, self::PRODUCT_LISTINGS_REMOVE, self::PRODUCT_LISTINGS_UPDATE,
        self::PRODUCTS_CREATE, self::PRODUCTS_DELETE, self::PRODUCTS_UPDATE,
        self::REFUNDS_CREATE,
        self::SHOP_UPDATE,
        self::THEMES_CREATE,
        self::THEMES_DELETE, self::THEMES_PUBLISH, self::THEMES_UPDATE
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * @param Client $client
     * @param int|null $webhook_id
     */
    public function __construct($client, $webhook_id = null)
    {
        parent::__construct($client);
        $this->webhook_id = $webhook_id;
    }

    /**
     * Retrieve all Webhooks
     *
     * @link https://help.shopify.com/api/reference/webhook#index
     *
     * @param array $params
     *      limit: default=15, max = 200,
     *      page: default=1
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function all(array $params = [])
    {
        return $this->get('/admin/webhooks.json', $params);
    }

    /**
     * Get Count
     *
     * @link https://help.shopify.com/api/reference/webhook#count
     *
     * @param array $params
     *      address: filter by URI of the webhooks post request,
     *      topic: default=all
     * @return \Guzzle\Http\EntityBodyInterface|mixed|integer
     */
    public function count(array $params = [])
    {
        $count = $this->get('/admin/webhooks/count.json', $params);
        return isset($count['count']) ? $count['count'] : 0;
    }

    /**
     * Create a Webhook
     *
     * @link https://help.shopify.com/api/reference/webhook#create
     *
     * @param array  $webhook optional attributes
     * @return array webhook info
     */
    public function create(array $webhook = array())
    {
        return $this->post("/admin/webhooks.json", compact('webhook'));
    }

    /**
     * Find a webhook
     *
     * @link https://help.shopify.com/api/reference/webhook#show
     *
     * @param string $id     the webhook's id
     * @param array  $params optional attributes
     * @return array webhook
     */
    public function show($id, array $params = [])
    {
        return $this->get("/admin/webhooks/{$id}.json", $params);
    }


    /**
     * Update a Webhook
     *
     * @link https://help.shopify.com/api/reference/webhook#update
     *
     * @param string $id     the webhook's id
     * @param array  $params optional attributes
     * @return array webhook
     */
    public function update($id, array $params = [])
    {
        return $this->put("/admin/webhooks/{$id}.json", $params);
    }

    /**
     * Delete a webhook (delete or remove do the same)
     *
     * @link https://help.shopify.com/api/reference/webhook#delete
     * @param string $id the webhook's id
     *
     * @return array empty
     */
    public function remove($id)
    {
        return parent::delete("/admin/webhooks/{$id}.json");
    }

    /**
     * Delete a webhook (delete or remove do the same)
     *
     * @link https://help.shopify.com/api/reference/webhook#delete
     * @param string $id the webhook's id
     *
     * @param array $parameters
     * @param array $request_headers
     * @return array empty
     */
    public function delete($id, array $parameters = [], $request_headers = [])
    {
        return parent::delete("/admin/webhooks/{$id}.json");
    }

}