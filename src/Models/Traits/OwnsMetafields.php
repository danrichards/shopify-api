<?php

namespace ShopifyApi\Models\Traits;

use BadMethodCallException;
use ShopifyApi\Models\Metafield;
use ShopifyApi\Api\Metafield as MetafieldApi;

/**
 * Trait OwnsMetafields
 *
 * For any of the Models that may own metafields. e.g. Order, Product, Variant
 */
trait OwnsMetafields
{

    /**
     * @return array \ShopifyApi\Models\Metafield
     */
    public function metafields()
    {
        $api = static::$api_name;

        $metafields = $this->client->api('metafields')->$api($this->getId())->all();

        if (! empty($metafields) && isset($metafields['metafields'])) {
            return array_map(function($metafield) {
                return new Metafield($this->client, $metafield);
            }, $metafields['metafields']);
        }

        return [];
    }

    /**
     * @return array
     */
    public function getMetafields()
    {
        return $this->metafields();
    }

    /**
     * @param string $key
     * @param string|null $namespace
     * @return Metafield|null
     */
    public function getMetafield($key, $namespace) {
        foreach ($this->metafields() as $m) {
            if ($m->getKey() == $key && $m->getNamespace() == $namespace) {
                return $m;
            }
        }

        return null;
    }

    /**
     * @param string $key
     * @param string|null $namespace
     * @return bool
     */
    public function hasMetafield($key, $namespace) {
        return ! empty($this->getMetafield($key, $namespace));
    }

    /**
     * @param $key
     * @param $namespace
     * @param array $attributes
     * @return Metafield
     */
    public function createMetafield($key, $namespace, array $attributes = [])
    {
        $api = static::$api_name;

        // Allow for no value to be set
        if (! isset($attributes['value'])) {
            $attributes['value'] = json_encode(null);
            $attributes['value_type'] = 'string';
        }

        // Allow for arrays and objects to be json_encoded
        if (! is_int($attributes['value']) && ! is_string($attributes['value'])) {
            $attributes['value'] = (string) @json_encode($attributes['value']);
            $attributes['value_type'] = 'string';
        }

        // Determine the value_type if it's not already specified
        if (! isset($attributes['value_type'])) {
            $attributes['value_type'] = is_int($attributes['value'])
                ? 'integer' : 'string';
        }

        $arr = $this->client
            ->api('metafields')
            ->$api($this->getId())
            ->create(array_merge(compact('key', 'namespace'), $attributes));

        return new Metafield($this->client, $arr);
    }

    /**
     * @param string $key
     * @param string $namespace
     * @param array $attributes
     * @return Metafield
     */
    public function updateMetafield($key, $namespace, array $attributes)
    {
        $omit = ['id', 'owner_id', 'owner_resource', 'created_at', 'updated_at'];

        if (empty($updated = $this->getMetafield($key, $namespace))) {
            throw new BadMethodCallException('Metafield does not exist.');
        }

        foreach (array_diff(MetafieldApi::$fields, $omit) as $field) {
            if (isset($attributes[$field])) {
                $updated->setOriginal($field, $attributes[$field]);
            }
        }

        if (isset($attributes['updated_at'])) {
            $updated->setUpdatedAt($attributes['updated_at']);
        }

        $updated->save();

        return $updated;
    }

    /**
     * @param string $key
     * @param string $namespace
     *
     * @return boolean | BadMethodCallException
     */
    public function deleteMetafield($key, $namespace)
    {
        if (empty($updated = $this->getMetafield($key, $namespace))) {
            throw new BadMethodCallException('Metafield does not exist.');
        }

        $updated->remove();
        return true;
    }
    /**
     * @param $key
     * @param $namespace
     * @param array $attributes
     * @return Metafield
     */
    public function updateOrCreateMetafield($key, $namespace, array $attributes = [])
    {
        try {
            return $this->updateMetafield($key, $namespace, $attributes);
        } catch (BadMethodCallException $e) {
            return $this->createMetafield($key, $namespace, $attributes);
        }
    }

    /**
     * @param $key
     * @param $namespace
     * @param array $attributes
     * @return Metafield | bool | BadMethodCallException
     */
    public function updateCreateOrRemoveMetafield($key, $namespace, array $attributes = [])
    {
        if(!isset($attributes['value']) || empty($attributes['value'])){
            if(empty($this->getMetafield($key, $namespace))){
                return false;
            }
            return $this->deleteMetafield($key, $namespace);
        }

        return $this->updateOrCreateMetafield($key, $namespace, $attributes);
    }
}