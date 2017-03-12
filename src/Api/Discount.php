<?php

namespace ShopifyApi\Api;

use BadMethodCallException;
use ShopifyApi\Client;

/**
 * Class Discount
 *
 * API calls that can be carried out on a Discount
 */
class Discount extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'discounts';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'discounts';

    /** @var string $path */
    protected static $path = '/admin/discounts/#id#.json';

    /** @var array $fields */
    public static $fields = [
        'id',
        'discount_type',
        'code',
        'value',
        'ends_at',
        'starts_at',
        'status',
        'minimum_order_amount',
        'usage_limit',
        'applies_to_id',
        'applies_once',
        'applies_once_per_customer',
        'applies_to_resource',
        'times_used'
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * @param Client $client
     * @param int|null $discount_id
     */
    public function __construct($client, $discount_id = null)
    {
        parent::__construct($client);
        $this->discount_id = $discount_id;
    }

    /**
     * Create a Discount
     *
     * @link https://help.shopify.com/api/reference/discount#create
     *
     * @param array  $discount optional attributes
     * @return array discount info
     */
    public function create(array $discount = array())
    {
        return $this->post("/admin/discounts.json", compact('discount'));
    }

    /**
     * Retrieve all Discounts
     *
     * @link https://help.shopify.com/api/reference/discount#index
     *
     * @param array $params
     *      limit: default=15, max = 200,
     *      page: default=1
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function all(array $params = [])
    {
        return $this->get('/admin/discounts.json', $params);
    }

    /**
     * Find a discount
     *
     * @link https://help.shopify.com/api/reference/discount#show
     *
     * @param string $id     the discount's id
     * @param array  $params optional attributes
     * @return array discount
     */
    public function show($id, array $params = [])
    {
        return $this->get("/admin/discounts/{$id}.json", $params);
    }

    /**
     * Disable a discount
     *
     * @link https://help.shopify.com/api/reference/discount#disable
     * @param string $id     the discount's id
     *
     * @return array discount
     */
    public function disable($id)
    {
        return $this->post("/admin/discounts/{$id}/disable.json");
    }

    /**
     * Enable a discount
     *
     * @link https://help.shopify.com/api/reference/discount#enable
     * @param string $id     the discount's id
     *
     * @return array discount
     */
    public function enable($id)
    {
        return $this->post("/admin/discounts/{$id}/enable.json");
    }

    /**
     * Delete a discount (delete or remove do the same)
     *
     * @link https://help.shopify.com/api/reference/discount#delete
     * @param string $id the discount's id
     *
     * @return array empty
     */
    public function remove($id)
    {
        return parent::delete("/admin/discounts/{$id}.json");
    }

    /**
     * Delete a discount (delete or remove do the same)
     *
     * @link https://help.shopify.com/api/reference/discount#delete
     * @param string $id the discount's id
     *
     * @param array $parameters
     * @param array $request_headers
     * @return array empty
     */
    public function delete($id, array $parameters = [], $request_headers = [])
    {
        return parent::delete("/admin/discounts/{$id}.json");
    }

    /**
     * Update a Discount is not possible
     *
     * @param $id
     * @param array $params
     * @return BadMethodCallException
     */
    public function update($id, array $params = [])
    {
        throw new BadMethodCallException("Discount can't be updated. The can only by enabled, disabled and removed.");
    }
}