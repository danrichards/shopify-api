<?php

namespace ShopifyApi\Api\Traits;

/**
 * Class OwnsMetafields
 */
trait OwnsMetafields
{

    /**
     * Retrieve the metafields for a resource
     *
     * @param $id
     * @param array $params
     * @return array
     */
    public function metafields($id, $params = [])
    {
        $id = rawurlencode($id);
        $api_wrap = static::$parameters_wrap_many;
        $arr = $this->get("/{$api_wrap}/{$id}/metafields.json", $params);
        return is_array($arr) && isset($arr['metafields'])
            ? $arr['metafields'] : [];
    }

}
