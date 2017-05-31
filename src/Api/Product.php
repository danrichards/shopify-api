<?php

namespace ShopifyApi\Api;

use ShopifyApi\Models\Variant;
use ShopifyApi\Api\Traits\OwnsMetafields;

/**
 * Class Product
 *
 * API calls that can be carried out on a Product
 */
class Product extends AbstractApi
{

    use OwnsMetafields;

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'product';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'products';

    /** @var string $path */
    protected static $path = '/admin/products/#id#.json';

    /** @var array $fields */
    public static $fields = [
        'id',
        'title',
        'body_html',
        'vendor',
        'product_type',
        'created_at',
        'handle',
        'updated_at',
        'published_at',
        'template_suffix',
        'published_scope',
        'tags',
        'variants',
        'options',
        'images',
        'image'
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * Retrieve all products (api limit is 250)
     *
     * @link https://help.shopify.com/api/reference/product#index
     *
     * @param array $params
     * @return array
     */
    public function all(array $params = [])
    {
        return $this->get('/admin/products.json', $params);
    }

    /**
     * Retrieve the number of products
     *
     * @link https://help.shopify.com/api/reference/product#count
     *
     * @param array $params
     * @return integer
     */
    public function count(array $params = [])
    {
        $count = $this->get('/admin/products/count.json', $params);
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
     * @return array
     */
    public function show($id, array $params = [])
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Create a Product
     *
     * @link https://help.shopify.com/api/reference/product#create
     *
     * @param array  $params Attributes
     * @return array
     */
    public function create(array $params = array())
    {
        $product = $params;

        return $this->post('/admin/products.json', compact('product'));
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
     * Delete a Product
     *
     * @link https://help.shopify.com/api/reference/product#update
     *
     * @param $id
     * @return array
     */
    public function remove($id)
    {
        return $this->delete($this->getPath(rawurlencode($id)));
    }

    /**
     * Variants API
     *
     * @return array [Variant]
     */
    public function variants()
    {
        $variants = $this->getVariants();
        return array_map(function($variant) {
            return new Variant($this->client, $variant);
        }, $variants);
    }

    // ------------------------------------------------------------------------
    //                      SUPPORT FOR PRODUCT IMAGES
    // ------------------------------------------------------------------------

    /**
     * Retrieve all images for a product
     *
     * @link https://help.shopify.com/api/reference/product_image#index
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function images($id, array $params = [])
    {
        $alt_path = '/admin/products/#id#/images.json';
        return $this->get($this->getPath($id, $alt_path), $params);
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#create
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function createImage($id, array $params = [])
    {
        $alt_path = '/admin/products/#id#/images.json';
        $image = $params;
        return $this->post($this->getPath($id, $alt_path), compact('image'));
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#update
     *
     * @param $id
     * @param $image_id
     * @param array $params
     * @return array
     */
    public function updateImage($id, $image_id, array $params = [])
    {
        $alt_path = "/admin/orders/#id#/fulfillments/{$image_id}.json";
        $image = $params;
        return $this->post($this->getPath($id, $alt_path), compact('image'));
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#show
     *
     * @param $id
     * @param $image_id
     * @return array
     */
    public function showImage($id, $image_id)
    {
        $alt_path = "/admin/products/#id#/images/{$image_id}.json";
        return $this->get($this->getPath($id, $alt_path));
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#count
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function countImages($id, array $params = [])
    {
        $alt_path = "/admin/products/#id#/images/count.json";
        return $this->get($this->getPath($id, $alt_path), $params);
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#destroy
     *
     * @param $id
     * @param $fulfillment_id
     * @return array
     */
    public function deleteImage($id, $image_id)
    {
        $alt_path = "/admin/products/#id#/images/{$image_id}.json";
        return $this->delete($this->getPath($id, $alt_path));
    }

}