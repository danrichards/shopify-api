<?php

namespace ShopifyApi\Api;

/**
 * Class Order
 *
 * API calls that can be carried out on an order.
 */
class Order extends AbstractApi
{

    /** @var string $api_name */
    protected static $api_name = 'order';

    /** @var array $load_params */
    protected static $load_params = [];

    /** @var array $fields */
    public static $fields = [
        'id',
        'email',
        'closed_at',
        'created_at',
        'updated_at',
        'number',
        'note',
        'token',
        'gateway',
        'test',
        'total_price',
        'subtotal_price',
        'total_weight',
        'total_tax',
        'taxes_included',
        'currency',
        'financial_status',
        'confirmed',
        'total_discounts',
        'total_line_items_price',
        'cart_token',
        'buyer_accepts_marketing',
        'name',
        'referring_site',
        'landing_site',
        'cancelled_at',
        'cancel_reason',
        'total_price_usd',
        'checkout_token',
        'reference',
        'user_id',
        'location_id',
        'source_identifier',
        'source_url',
        'processed_at',
        'device_id',
        'browser_ip',
        'landing_site_ref',
        'order_number',
        'discount_codes',
        'note_attributes',
        'payment_gateway_names',
        'processing_method',
        'checkout_id',
        'source_name',
        'fulfillment_status',
        'tax_lines',
        'tags',
        'contact_email',
        'order_status_url',
        'line_items',           // @todo Api, Model(s)
        'shipping_lines',
        'billing_address',
        'fulfillments',         // @todo Api, Model(s)
        'client_details',
        'refunds',
        'payment_details'       // @todo Api, Model
    ];

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'order';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'orders';

    /** @var string $path */
    protected static $path = '/admin/orders/#id#.json';

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * Retrieve all orders (api limit is 250)
     *
     * @link https://help.shopify.com/api/reference/order#index
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function all(array $params = [])
    {
        return $this->get('/admin/orders.json', $params);
    }

    /**
     * Retrieve the number of orders
     *
     * @link https://help.shopify.com/api/reference/order#count
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function count(array $params = [])
    {
        $count =  $this->get('/admin/orders/count.json', $params);
        return isset($count['count'])
            ? $count['count'] : 0;
    }

    /**
     * Find an Order
     *
     * @link https://help.shopify.com/api/reference/order#show
     *
     * @param string $id     the board's id
     * @param array  $params optional attributes
     *
     * @return array board info
     */
    public function show($id, array $params = [])
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Update an Order
     *
     * @link https://help.shopify.com/api/reference/order#update
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function update($id, array $params = [])
    {
        return $this->put($this->getPath(rawurlencode($id)), $params);
    }

}