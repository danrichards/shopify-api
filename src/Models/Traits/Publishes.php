<?php

namespace ShopifyApi\Models\Traits;

use DateTime;
use DateTimeZone;
use ShopifyApi\Models\Product;
use ShopifyApi\Models\CustomCollection;

/**
 * Trait Publishes
 *
 * @method $this setPublishedScope(string $published_scope)
 * @method string getPublishedScope()
 * @method bool hasPublishedScope()
 */
trait Publishes
{
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
     * @param bool $published
     * @return $this
     */
    public function setPublished($published)
    {
        $this->data['published'] = $published;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPublished()
    {
        return ! empty($this->data['published_at'])
            && ! empty($this->data['published_scope']);
    }

    /**
     * Sugar for publishing
     *
     * @return $this
     */
    public function publish()
    {
        if (get_class($this) == CustomCollection::class) {
            return $this->setPublished(true)->save();
        }

        /** @var Product|CustomCollection $this */
        return $this->setPublishedAt(new DateTime())->save();
    }

    /**
     * Sugar for unpublishing
     *
     * @return $this
     */
    public function unpublish()
    {
        if (get_class($this) == CustomCollection::class) {
            return $this->setPublished(false)->save();
        }

        /** @var Product|CustomCollection $this */
        return $this->setPublishedAt(null)
            ->save();
    }

}