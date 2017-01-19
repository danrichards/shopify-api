<?php

namespace ShopifyApi\Models;

use BadMethodCallException;

/**
 * Class Discount
 *
 * @method string getDiscountType()
 * @method string getCode()
 * @method string getValue()
 * @method string getEndsAt()
 * @method string getStartsAt()
 * @method string getStatus()
 *
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
class Discount extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'discounts';

    /** @var array $load_params */
    protected static $load_params = [];

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
            $this->data = $this->api->delete($this->data['id']);
            $this->postRemove();
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                "You can't remove %s objects.",
                get_called_class()
            ));
        }

        return $this;
    }

}