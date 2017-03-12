<?php

namespace ShopifyApi\Models;

use DateTime;
use DateTimeZone;
use ShopifyApi\Models\Traits\OwnsMetafields;

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
 * @method string setTitle(string $title)
 * @method string setBodyHtml(string $body)
 * @method string setVendor(string $vendor)
 * @method string setProductType(string $product_type)
 * @method string setHandle(string $handle)
 * @method string setTemplateSuffix(string $template_suffix)
 * @method string setPublishedScope(string $published_scope)
 * @method array setVariants(array $variants)
 * @method array setOptions(array $options)
 * @method array setImages(array $images)
 * @method array setMetafields(array $metafields)
 * @method array setImage(array $image)
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

    use OwnsMetafields;

    /** @var string $api_name */
    protected static $api_name = 'product';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * @return array
     */
    public function getTags()
    {
        return explode(',', $this->getOriginal('tags'));
    }

    /**
     * @param array|string $tags
     * @return $this
     */
    public function setTags($tags)
    {
        if (is_array($tags)) {
            $this->setOriginal('tags', implode(',', $tags));
        } elseif (is_string($tags)) {
            $this->setOriginal('tags', $tags);
        }
        return $this;
    }

    /**
     * @param DateTimeZone $time_zone
     * @return DateTime|null
     */
    public function getPublishedAt(DateTimeZone $time_zone = null)
    {
        return is_null($date = $this->getOriginal('published_at'))
            ? $date : new DateTime($date, $time_zone);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setPublishedAt($stringOrDateTime)
    {
        $this->data['published_at'] = $stringOrDateTime instanceof DateTime || $stringOrDateTime instanceof \Carbon\Carbon
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    /**
     * Sugar for publishing
     *
     * @return $this
     */
    public function publish()
    {
        return $this->setPublishedAt(new DateTime())->save();
    }

    /**
     * Sugar for unpublishing
     *
     * @return $this
     */
    public function unpublish()
    {
        return $this->setPublishedAt(null)->save();
    }

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

}