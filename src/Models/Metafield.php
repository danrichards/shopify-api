<?php

namespace ShopifyApi\Models;

use Guzzle\Common\Exception\BadMethodCallException;

/**
 * Class Metafield
 *
 * @method array getNamespace()
 * @method array getKey()
 * @method array getValue()
 * @method array getValueType()
 * @method array getDescription()
 * @method array getOwnerId()
 * @method array getOwnerResource()
 * @method string setNamespace(string $namespace)
 * @method string setKey(string $key)
 * @method string setValue(string $value)
 * @method string setValueType(string $value_type)
 * @method string setDescription(string $description)
 * @method string setOwnerId(int $owner_id)
 * @method string setOwnerResource(string $owner_resource)
 * @method bool hasNamespace()
 * @method bool hasKey()
 * @method bool hasValue()
 * @method bool hasValueType()
 * @method bool hasDescription()
 * @method bool hasOwnerId()
 * @method bool hasOwnerResource()
 * @method bool hasTitle()
 */
class Metafield extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'metafield';

    /** @var array $load_params */
    protected static $load_params = [];

    /** @var string $owner_resource */
    protected $owner_resource;

    /** @var int $owner_resource_id */
    protected $owner_id;

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->owner_resource = isset($data['owner_resource'])
            ? $data['owner_resource'] : null;
        $this->owner_id = isset($data['owner_id'])
            ? $data['owner_id'] : null;
        $this->data = $data;

        return $this;
    }

    /**
     * Update the object through API
     *
     * @return $this
     */
    protected function update()
    {
        $this->preUpdate();
        if ($this->owner_resource) {
            $api_owner = $this->owner_resource;
            $this->data = $this
                ->api
                ->$api_owner($this->owner_id)
                ->update($this->id, $this->data)[static::$api_name];
        } else {
            $this->data = $this->api->update($this->id, $this->data)[static::$api_name];
        }
        $this->postUpdate();

        return $this;
    }

    /**
     * Create the object through API
     *
     * @return $this
     */
    protected function create()
    {
        $this->preCreate();
        if ($this->owner_resource) {
            $api_owner = $this->owner_resource;
            $this->data = $this
                ->api
                ->$api_owner($this->owner_id)
                ->create($this->data)[static::$api_name];
        } else {
            $this->data = $this->api->create($this->data)[static::$api_name];
        }
        $this->id = $this->data['id'];
        $this->postCreate();

        return $this;
    }

    /**
     * Remove the object through API
     *
     * @return $this
     */
    public function remove()
    {
        try {
            $this->preRemove();
            $this->id = $this->data['id'];
            if ($this->owner_resource) {
                $api_owner = $this->owner_resource;
                $this->data = $this
                    ->api
                    ->$api_owner($this->owner_id)
                    ->delete($this->data['id']);
            } else {
                $this->data = $this->api->delete($this->data['id']);
            }
            $this->postRemove();
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                "You can't remove %s objects.",
                get_called_class()
            ));
        }

        return $this;
    }

    /**
     * @return null|Order|Product|Variant
     */
    public function getResource()
    {
        switch ($this->getOwnerResource()) {
            case 'product':
                return new Product($this->client, $this->getOwnerId());
            case 'variant':
                return new Variant($this->client, $this->getOwnerId());
            case 'order':
                return new Order($this->client, $this->getOwnerId());
            default:
                return null;
        }
    }

}