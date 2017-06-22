<?php

namespace ShopifyApi\Api;

use ShopifyApi\Api\Traits\OwnsMetafields;

/**
 * Class CustomCollection
 *
 * API calls that can be carried out on a CustomCollection
 */
class CustomCollection extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'custom_collection';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'custom_collections';

    /** @var string $path */
    protected static $path = '/admin/custom_collections/#id#.json';

    /** @var array $fields */
    public static $fields = [
        'id',
        'title',
        'body_html',
        'handle',
        'created_at',
        'updated_at',
        'published_at',
        'template_suffix',
        'published_scope',
        'sort_order',
        'image'
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * Retrieve all products (api limit is 250)
     *
     * @link https://help.shopify.com/api/reference/custom_collections#index
     *
     * @param array $params
     * @return array
     */
    public function all(array $params = [])
    {
        return $this->get('/admin/custom_collections.json', $params);
    }

    /**
     * Retrieve the number of products
     *
     * @link https://help.shopify.com/api/reference/custom_collections#count
     *
     * @param array $params
     * @return integer
     */
    public function count(array $params = [])
    {
        $count = $this->get('/admin/custom_collections/count.json', $params);
        return isset($count['count'])
            ? $count['count'] : 0;
    }

    /**
     * Find a CustomCollection
     *
     * @link https://help.shopify.com/api/reference/customcollections#show
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
     * Create a CustomCollection
     *
     * @link https://help.shopify.com/api/reference/customcollections#create
     *
     * @param array  $params Attributes
     * @return array
     */
    public function create(array $params = array())
    {
        $custom_collection = $params;

        return $this->post('/admin/custom_collections.json', compact('custom_collection'));
    }

    /**
     * Update a CustomCollection
     *
     * @link https://help.shopify.com/api/reference/customcollections#update
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
     * Delete a CustomCollection
     *
     * @link https://help.shopify.com/api/reference/customcollections#destroy
     *
     * @param $id
     * @return array
     */
    public function remove($id)
    {
        return $this->delete($this->getPath(rawurlencode($id)));
    }

}