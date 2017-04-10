<?php

namespace ShopifyApi\Models\Traits;

/**
 * Class Taggable
 */
trait Taggable
{
    /**
     * @return array
     */
    public function getTags()
    {
        return array_filter(explode(',', $this->getOriginal('tags')));
    }

    /**
     * @param array|string $tags
     * @return $this
     */
    public function setTags($tags)
    {
        if (is_array($tags)) {
            $this->setOriginal('tags', array_filter(implode(',', $tags)));
        } elseif (is_string($tags)) {
            $this->setOriginal('tags', $tags);
        }
        return $this;
    }
}