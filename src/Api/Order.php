<?php

namespace ShopifyApi\Api;

use ShopifyApi\Api\Traits\OwnsMetafields;

/**
 * Class Order
 *
 * API calls that can be carried out on an order.
 */
class Order extends AbstractApi
{

    use OwnsMetafields;

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
    protected static $path = '/orders/#id#.json';

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * Retrieve all orders (api limit is 250)
     *
     * @link https://help.shopify.com/api/reference/order#index
     *
     * @param array $params
     * @return array
     */
    public function all(array $params = [])
    {
        return $this->get('/orders.json', $params);
    }

    /**
     * Retrieve the number of orders
     *
     * @link https://help.shopify.com/api/reference/order#count
     *
     * @param array $params
     * @return array
     */
    public function count(array $params = [])
    {
        $count =  $this->get('/orders/count.json', $params);
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
     * @return array
     */
    public function show($id, array $params = [])
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Create an Order
     *
     * @link https://help.shopify.com/api/reference/order#create
     *
     * @param array  $params Attributes
     * @return array
     */
    public function create(array $params = array())
    {
        $order = $params;

        return $this->post('/orders.json', compact('order'));
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

    /**
     * @param $id
     * @param array $params
     * @return array
     */
    public function remove($id, array $params = [])
    {
        return $this->delete($this->getPath(rawurlencode($id)), $params);
    }

    // ------------------------------------------------------------------------
    //                          SUPPORT FOR ORDER RISKS
    // ------------------------------------------------------------------------

    /**
     * Retrieve all risks for an order
     *
     * @link https://help.shopify.com/api/reference/order_risks#index
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function risks($id, array $params = [])
    {
        $alt_path = '/orders/#id#/risks.json';
        return $this->get($this->getPath($id, $alt_path), $params);
    }

    /**
     * @link https://help.shopify.com/api/reference/order_risks#create
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function createRisk($id, array $params = [])
    {
        $alt_path = '/orders/#id#/risks.json';
        $risk = $params;
        return $this->post($this->getPath($id, $alt_path), compact('risk'));
    }

    /**
     * @link https://help.shopify.com/api/reference/order_risks#update
     *
     * @param $id
     * @param $risk_id
     * @param array $params
     * @return array
     */
    public function updateRisk($id, $risk_id, array $params = [])
    {
        $alt_path = "/orders/#id#/risks/{$risk_id}.json";
        $risk = $params;
        return $this->post($this->getPath($id, $alt_path), compact('risk'));
    }

    /**
     * @link https://help.shopify.com/api/reference/order_risks#show
     *
     * @param $id
     * @param $risk_id
     * @return array
     */
    public function showRisk($id, $risk_id)
    {
        $alt_path = "/orders/#id#/risks/{$risk_id}.json";
        return $this->get($this->getPath($id, $alt_path));
    }

    /**
     * @link https://help.shopify.com/api/reference/order_risks#destroy
     *
     * @param $id
     * @param $risk_id
     * @return array
     */
    public function deleteRisk($id, $risk_id)
    {
        $alt_path = "/orders/#id#/risks/{$risk_id}.json";
        return $this->delete($this->getPath($id, $alt_path));
    }

    // ------------------------------------------------------------------------
    //                       SUPPORT FOR ORDER FULFILLMENTS
    // ------------------------------------------------------------------------

    /**
     * Retrieve all fulfillments for an order
     *
     * @link https://help.shopify.com/api/reference/fulfillment#index
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function fulfillments($id, array $params = [])
    {
        $alt_path = '/orders/#id#/fulfillments.json';
        return $this->get($this->getPath($id, $alt_path), $params);
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#create
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function createFulfillment($id, array $params = [])
    {
        $alt_path = '/orders/#id#/fulfillments.json';
        $fulfillment = $params;
        return $this->post($this->getPath($id, $alt_path), compact('fulfillment'));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#update
     *
     * @param $id
     * @param $fulfillment_id
     * @param array $params
     * @return array
     */
    public function updateFulfillment($id, $fulfillment_id, array $params = [])
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}.json";
        $fulfillment = $params;
        return $this->post($this->getPath($id, $alt_path), compact('fulfillment'));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#show
     *
     * @param $id
     * @param $fulfillment_id
     * @return array
     */
    public function showFulfillment($id, $fulfillment_id)
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}.json";
        return $this->get($this->getPath($id, $alt_path));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#count
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function countFulfillments($id, array $params = [])
    {
        $alt_path = "/orders/#id#/fulfillments/count.json";
        return $this->get($this->getPath($id, $alt_path), $params);
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#open
     *
     * @param $id
     * @param $fulfillment_id
     * @return array
     */
    public function openFulfillment($id, $fulfillment_id)
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/open.json";
        return $this->post($this->getPath($id, $alt_path));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#complete
     *
     * @param $id
     * @param $fulfillment_id
     * @return array
     */
    public function completeFulfillment($id, $fulfillment_id)
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/complete.json";
        return $this->post($this->getPath($id, $alt_path));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillment#cancel
     *
     * @param $id
     * @param $fulfillment_id
     * @return array
     */
    public function cancelFulfillment($id, $fulfillment_id)
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/cancel.json";
        return $this->post($this->getPath($id, $alt_path));
    }

    // ------------------------------------------------------------------------
    //                   SUPPORT FOR ORDER FULFILLMENT EVENTS
    // ------------------------------------------------------------------------

    /**
     * Retrieve all fulfillment events for an order
     *
     * @link https://help.shopify.com/api/reference/fulfillmentevent#index
     *
     * @param $id
     * @param $fulfillment_id
     * @param array $params
     * @return array
     */
    public function fulfillmentEvents($id, $fulfillment_id, array $params = [])
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/events.json";
        return $this->get($this->getPath($id, $alt_path), $params);
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillmentevent#create
     *
     * @param $id
     * @param $fulfillment_id
     * @param array $params
     * @return array
     */
    public function createFulfillmentEvent($id, $fulfillment_id, array $params = [])
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/events.json";
        $event = $params;
        return $this->post($this->getPath($id, $alt_path), compact('event'));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillmentevent#show
     *
     * @param $id
     * @param $fulfillment_id
     * @param $event_id
     * @return array
     */
    public function showFulfillmentEvent($id, $fulfillment_id, $event_id)
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/events/{$event_id}.json";
        return $this->get($this->getPath($id, $alt_path));
    }

    /**
     * @link https://help.shopify.com/api/reference/fulfillmentevent#destroy
     *
     * @param $id
     * @param $fulfillment_id
     * @param $event_id
     * @return array
     */
    public function deleteFulfillmentEvent($id, $fulfillment_id, $event_id)
    {
        $alt_path = "/orders/#id#/fulfillments/{$fulfillment_id}/events/{$event_id}.json";
        return $this->delete($this->getPath($id, $alt_path));
    }

}
