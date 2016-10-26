<?php

namespace ShopifyApi\Api;

use BadMethodCallException;
use ShopifyApi\Client;

/**
 * Class Product
 *
 * API calls that can be carried out on a Product
 */
class Variants extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'variant';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'variants';

    /** @var string $path */
    protected static $path = '/admin/variants/#id#.json';

    /** @var array $fields */
    public static $fields = [
        'id',
        'product_id',
        'title',
        'price',
        'sku',
        'position',
        'grams',
        'inventory_policy',
        'compare_at_price',
        'fulfillment_service',
        'inventory_management',
        'option1',
        'option2',
        'option3',
        'created_at',
        'updated_at',
        'taxable',
        'barcode',
        'image_id',
        'inventory_quantity',
        'weight',
        'weight_unit',
        'old_inventory_quantity',
        'requires_shipping'
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [
        'created_at',
        'updated_at',
    ];

    /** @var int|null $product_id */
    protected $product_id = null;

    /** @var int|null $variant_id */
    protected $variant_id = null;

    /**
     * @param Client $client
     * @param int|null $product_id
     * @param int|null $variant_id
     */
    public function __construct($client, $variant_id = null, $product_id = null)
    {
        parent::__construct($client);
        $this->variant_id = $variant_id;
        $this->product_id = $product_id;
    }

    /**
     * @param $product_id
     * @return $this
     */
    public function product($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * Retrieve all Product Variants (api limit is 250)
     *
     * @link https://help.shopify.com/api/reference/product_variant#index
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function all(array $params = [])
    {
        $product_id = $this->product_id;

        if (empty($product_id)) {
            throw new BadMethodCallException('Please specify a product id.');
        }

        return $this->get($this->getRelatedPath($product_id), $params);
    }

    /**
     * Retrieve the number of variants
     *
     * @link https://help.shopify.com/api/reference/product_variant#count
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function count(array $params = [])
    {
        if (empty($this->product_id)) {
            throw new BadMethodCallException('Please specify a product id.');
        }

        $count = $this->get(sprintf('/admin/products/%d/variants/count.json', $this->product_id), $params);

        return isset($count['count']) ? $count['count'] : 0;
    }

    /**
     * Find a Product Variant
     *
     * @link https://help.shopify.com/api/reference/product_variant#show
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
     * Update a Product Variant
     *
     * @link https://help.shopify.com/api/reference/product_variant#update
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
     * Create a Product Variant
     *
     * @link https://help.shopify.com/api/reference/product_variant#create
     *
     * @param array  $params optional attributes
     *
     * @return array card info
     */
    public function create(array $params = array())
    {
        $product_id = isset($params['product_id'])
            ? $params['product_id']
            : $this->product_id;

        if (empty($product_id)) {
            throw new BadMethodCallException('Please specify a product id.');
        }

        $option_required = array_fill_keys(['option1', 'option2', 'option2'], null);

        if (empty(array_intersect_key($params, $option_required))) {
            throw new BadMethodCallException('Please specify a option.');
        }

        $variant = $params;

        return $this->post($this->getRelatedPath($product_id), compact('variant'));
    }

    /**
     * @param $product_id
     * @return string
     */
    private function getRelatedPath($product_id)
    {
        return sprintf('/admin/products/%d/variants.json', $product_id);
    }

}