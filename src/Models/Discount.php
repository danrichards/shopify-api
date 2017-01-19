<?php

namespace ShopifyApi\Models;

use BadMethodCallException;

/**
 * Class Discount
 *
 * @method int getId()
 * @method string getCode()
 * @method string getValue()
 * @method string getEndsAt()
 * @method string getStartsAt()
 * @method string getStatus()
 * @method string getMinimunOrderAmount()
 * @method int getUsageLimit()
 * @method int getAppliesToId()
 * @method boolean getAppliesOnce()
 * @method boolean getAppliesOncePerCustomer()
 * @method string getDiscountType()
 * @method string getAppliesToResource()
 * @method int getTimesUsed()
 *
 * @method string setCode(string $code)
 * @method string setValue(string $value)
 * @method string setEndsAt(string $ends_at)
 * @method string setStartsAt(string $starts_at)
 * @method string setStatus(string $status)
 * @method string setMinimunOrderAmount(string $minimun_order_amount)
 * @method string setUsageLimit(string $usage_limit)
 * @method string setAppliesToId(string $applies_to_id)
 * @method string setAppliesOnce(int $applies_once)
 * @method boolean setAppliesOncePerCustomer(boolean $applies_once_per_customer)
 * @method boolean setDiscountType(boolean $discount_type)
 * @method string setAppliesToResource(string $applies_to_resource)
 *
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