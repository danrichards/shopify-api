<?php

namespace ShopifyApi\Api;

use BadMethodCallException;
use ShopifyApi\Client;

/**
 * Class Metafield
 *
 * API calls that can be carried out on a Product
 */
class Metafield extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'metafield';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'metafields';

    /** @var string $path */
    protected static $path = '/admin/metafields/#id#.json';

    /** @var array $fields */
    public static $fields = [
        'id',
        'namespace',
        'key',
        'value',
        'value_type',
        'description',
        'owner_id',
        'owner_resource',
        'created_at',
        'updated_at'
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /** @var string $fluent_resource */
    protected $fluent_resource;

    /** @var int $fluent_resource_id */
    protected $fluent_resource_id;

    /**
     * @param Client $client
     * @param int|null $metafield_id
     */
    public function __construct($client, $metafield_id = null)
    {
        parent::__construct($client);
        $this->metafield_id = $metafield_id;
    }

    /**
     * Retrieve all Metafields (api limit is 250)
     *
     * @link https://help.shopify.com/api/reference/metafield#index
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function all(array $params = [])
    {
        $url = '/admin/metafields.json';

        if ($this->fluent_resource) {
            $url = "/admin/{$this->fluent_resource}/{$this->fluent_resource_id}/metafields.json";
            $this->fluent_resource = $this->fluent_resource_id = null;
        }

        return $this->get($url, $params);
    }

    /**
     * Retrieve the number of variants
     *
     * @link https://help.shopify.com/api/reference/metafield#count
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function count(array $params = [])
    {
        $url = '/admin/metafields.json';

        if ($this->fluent_resource) {
            $url = "/admin/{$this->fluent_resource}/{$this->fluent_resource_id}/metafields/count.json";
            $this->fluent_resource = $this->fluent_resource_id = null;
        }

        $count = $this->get($url, $params);

        return isset($count['count']) ? $count['count'] : 0;
    }

    /**
     * Find a metafield
     *
     * @link https://help.shopify.com/api/reference/metafield#show
     *
     * @param string $id     the board's id
     * @param array  $params optional attributes
     *
     * @return array board info
     */
    public function show($id, array $params = [])
    {
        $url = "/admin/metafields/{$id}.json";

        if ($this->fluent_resource) {
            $url = "/admin/{$this->fluent_resource}/{$this->fluent_resource_id}/metafields/{$id}.json";
            $this->fluent_resource = $this->fluent_resource_id = null;
        }

        return $this->get($url, $params);
    }

    /**
     * Update a Metafield
     *
     * @link https://help.shopify.com/api/reference/metafield#update
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function update($id, array $params = [])
    {
        $url = "/admin/metafields/{$id}.json";

        if ($this->fluent_resource) {
            $url = "/admin/{$this->fluent_resource}/{$this->fluent_resource_id}/metafields/{$id}.json";
            $this->fluent_resource = $this->fluent_resource_id = null;
        }

        return $this->put($url, $params);
    }

    /**
     * Delete a Metafield
     *
     * @link https://help.shopify.com/api/reference/metafield#destroy
     *
     * @param string $id
     * @param array $params
     * @param array $request_headers
     * @return array
     */
    public function delete($id, array $params = [], $request_headers = [])
    {
        $url = "/admin/metafields/{$id}.json";

        if ($this->fluent_resource) {
            $url = "/admin/{$this->fluent_resource}/{$this->fluent_resource_id}/metafields/{$id}.json";
            $this->fluent_resource = $this->fluent_resource_id = null;
        }

        return parent::delete($url, $params);
    }

    /**
     * Create a Metafield
     *
     * @link https://help.shopify.com/api/reference/metafield#create
     *
     * @param array  $params optional attributes
     *
     * @return array card info
     */
    public function create(array $params = array())
    {
        $url = "/admin/metafields.json";

        if ($this->fluent_resource) {
            $url = "/admin/{$this->fluent_resource}/{$this->fluent_resource_id}/metafields.json";
            $this->fluent_resource = $this->fluent_resource_id = null;
        }

        $metafield = $params;

        return $this->post($url, compact('metafield'));
    }

    /**
     * @param $product_id
     * @return $this
     */
    public function product($product_id)
    {
        $this->fluent_resource = 'products';
        $this->fluent_resource_id = $product_id;
        return $this;
    }

    /**
     * @param $variant_id
     * @return $this
     */
    public function variant($variant_id)
    {
        $this->fluent_resource = 'variants';
        $this->fluent_resource_id = $variant_id;
        return $this;
    }

    /**
     * @param $order_id
     * @return $this
     */
    public function order($order_id)
    {
        $this->fluent_resource = 'orders';
        $this->fluent_resource_id = $order_id;
        return $this;
    }

}