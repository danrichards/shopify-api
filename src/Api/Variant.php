<?php

namespace ShopifyApi\Api;

/**
 * Class Product
 *
 * API calls that can be carried out on a Product
 */
class Variant extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'variant';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'variant';

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
    public static $ignore_on_update_fields = [];

    /**
     * @param int $product_id
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function all($product_id, array $params = [])
    {
        return $this->get(sprintf('/admin/products/%d/variants.json', $product_id), $params);
    }

    /**
     * @param int $product_id
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function count($product_id, array $params = [])
    {
        $count = $this->get(sprintf('/admin/products/%d/variants/count.json', $product_id), $params);
        return isset($count['count'])
            ? $count['count'] : 0;
    }

    /**
     * Find a Product
     *
     * @link https://help.shopify.com/api/reference/product#show
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
     * Update a Product
     *
     * @link https://help.shopify.com/api/reference/product#update
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
     * @param int $product_id
     * @param string $path
     * @param array $parameters
     * @param array $request_headers
     * @return mixed
     */
    public function post($product_id, $path, array $parameters = array(), $request_headers = array())
    {
        return $this->postRaw(
            sprintf('admin/products/%d/variants.json', $product_id),
            $this->createParametersBody($parameters),
            $request_headers
        );
    }

}