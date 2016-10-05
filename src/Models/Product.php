<?php

namespace ShopifyApi\Models;

use DateTime;

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
     * @return DateTime|null
     */
    public function getPublishedAt()
    {
        return is_null($date = $this->getOriginal('published_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setPublishedAt($stringOrDateTime)
    {
        $this->data['published_at'] = $stringOrDateTime instanceof DateTime
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

}