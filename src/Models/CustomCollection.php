<?php

namespace ShopifyApi\Models;

use DateTime;
use DateTimeZone;
use ShopifyApi\Models\Traits\OwnsMetafields;
use ShopifyApi\Models\Traits\Publishes;
use ShopifyApi\Models\Traits\Taggable;

/**
 * Class CustomCollection
 *
 * @method string getHandle()
 * @method string getTitle()
 * @method string getBodyHtml()
 * @method string getSortOrder()
 * @method string getTemplateSuffix()
 * @method string getProductCount()
 * @method array getImage()
 * @method array setMetafields()
 * @method $this setTitle(string $title)
 * @method $this setBodyHtml(string $body)
 * @method $this setHandle(string $handle)
 * @method $this setSortOrder(string $sort_order)
 * @method $this setTemplateSuffix(string $template_suffix)
 * @method $this setImage(array $image)
 * @method bool hasHandle()
 * @method bool hasTitle()
 * @method bool hasBodyHtml()
 * @method bool hasSortOrder()
 * @method bool hasTemplateSuffix()
 * @method bool hasImage()
 */
class CustomCollection extends AbstractModel
{

    use Publishes;

    /** @var string $api_name */
    protected static $api_name = 'custom_collection';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * Provide a product_id or array of product_ids to add to the collection
     *
     * @param string|int|array $product_ids
     */
    public function add($product_ids = [])
    {
        $product_ids = (array) $product_ids;
        $product_ids = array_map(function($product_id) {
            $product_id = (string) $product_id;
            return compact('product_id');
        }, $product_ids);

        return $this->setCollects($product_ids)->save();
    }

}