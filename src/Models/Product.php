<?php

namespace ShopifyApi\Models;

use DateTime;
use DateTimeZone;
use ShopifyApi\Models\Traits\OwnsMetafields;
use ShopifyApi\Models\Traits\Publishes;
use ShopifyApi\Models\Traits\Taggable;

/**
 * Class Product
 *
 * @method string getTitle()
 * @method string getBodyHtml()
 * @method string getVendor()
 * @method string getProductType()
 * @method string getHandle()
 * @method string getTemplateSuffix()
 * @method string getPublishedScope()
 * @method array getVariants()
 * @method array getOptions()
 * @method array getImages()
 * @method array getImage()
 * @method $this setTitle(string $title)
 * @method $this setBodyHtml(string $body)
 * @method $this setVendor(string $vendor)
 * @method $this setProductType(string $product_type)
 * @method $this setHandle(string $handle)
 * @method $this setTemplateSuffix(string $template_suffix)
 * @method $this setPublishedScope(string $published_scope)
 * @method $this setVariants(array $variants)
 * @method $this setOptions(array $options)
 * @method $this setImages(array $images)
 * @method $this setMetafields(array $metafields)
 * @method $this setImage(array $image)
 * @method bool hasTitle()
 * @method bool hasBodyHtml()
 * @method bool hasVendor()
 * @method bool hasProductType()
 * @method bool hasHandle()
 * @method bool hasTemplateSuffix()
 * @method bool hasPublishedScope()
 * @method bool hasVariants()
 * @method bool hasOptions()
 * @method bool hasImages()
 * @method bool hasImage()
 */
class Product extends AbstractModel
{

    use OwnsMetafields, Taggable, Publishes;

    /** @var string $api_name */
    protected static $api_name = 'product';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * @param $variant_id
     * @return Variant
     */
    public function variant($variant_id)
    {
        $all_variants = $this->getVariants();

        foreach($all_variants as $variant_data) {
            if ($variant_data['id'] == $variant_id) {
                return new Variant($this->client, $variant_data);
            }
        }

        // fail soft
        return null;
    }

    /**
     * Variants API
     *
     * @return array [\ShopifyApi\Models\Variant]
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
     * Retrieve all risks for an order
     *
     * @link https://help.shopify.com/api/reference/product_image#index
     *
     * @param array $params
     * @return array
     */
    public function images(array $params = [])
    {
        $response = $this->api->images($this->getId(), $params);
        return isset($response['images'])
            ? $response['images']
            : $response;
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#create
     *
     * @param array $params
     * @return array
     */
    public function createImage(array $params = [])
    {
        $response = $this->api->createImage($this->getId(), $params);
        return isset($response['image'])
            ? $response['image']
            : $response;
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#update
     *
     * @param $image_id
     * @param array $params
     * @return array
     */
    public function updateImage($image_id, array $params = [])
    {
        $response = $this->api->updateImage($this->getId(), $image_id, $params);
        return isset($response['image'])
            ? $response['image']
            : $response;
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#show
     *
     * @param $image_id
     * @param array $params
     * @return array
     */
    public function showImage($image_id, array $params = [])
    {
        $response = $this->api->showImage($this->getId(), $image_id, $params);
        return isset($response['image'])
            ? $response['image']
            : $response;
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#count
     *
     * @param array $params
     * @return integer
     */
    public function countImages(array $params = [])
    {
        $response = $this->api->countImages($this->getId(), $params);
        return isset($response['count'])
            ? $response['count']
            : $response;
    }

    /**
     * @link https://help.shopify.com/api/reference/product_image#destroy
     *
     * @param $image_id
     * @return array
     */
    public function deleteImage($image_id)
    {
        $response = $this->api->deleteImage($this->getId(), $image_id);
        return isset($response['image'])
            ? $response['image']
            : $response;
    }

}