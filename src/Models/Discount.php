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
 * @method $this setCode(string $code)
 * @method $this setValue(string $value)
 * @method $this setEndsAt(string $ends_at)
 * @method $this setStartsAt(string $starts_at)
 * @method $this setStatus(string $status)
 * @method $this setMinimunOrderAmount(string $minimun_order_amount)
 * @method $this setUsageLimit(string $usage_limit)
 * @method $this setAppliesToId(string $applies_to_id)
 * @method $this setAppliesOnce(boolean $applies_once)
 * @method $this setAppliesOncePerCustomer(boolean $applies_once_per_customer)
 * @method $this setDiscountType(string $discount_type)
 * @method $this setAppliesToResource(string $applies_to_resource)
 *
 * @method bool hasValue()
 * @method bool hasEndsAt()
 * @method bool hasMinimunOrderAmount()
 * @method bool hasUsageLimit()
 * @method bool hasAppliesToId()
 * @method bool hasAppliesToResource()
 */
class Discount extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'discount';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * Delete Discount
     *
     * @return $this
     */
    public function delete()
    {
        return $this->remove();
    }

    /**
     * Disable Discount
     *
     * @return $this
     */
    public function disable()
    {
        try {
            $this->id = $this->data['id'];
            if($this->data['status'] != 'disabled') {
                $this->data = $this->api->disable($this->data['id']);
            }
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                "You can't disable %s objects.",
                get_called_class()
            ));
        }

        return $this;
    }

    /**
     * Enable Discount
     *
     * @return $this
     */
    public function enable()
    {
        try {
            $this->id = $this->data['id'];
            if($this->data['status'] != 'enabled') {
                $this->data = $this->api->enable($this->data['id']);
            }
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                "You can't disable %s objects.",
                get_called_class()
            ));
        }

        return $this;
    }

    /**
     * Update a Discount is not possible
     *
     * @return BadMethodCallException
     */
    public function update()
    {
        throw new BadMethodCallException("Discount can't be updated. The can only by enabled, disabled and removed.");
    }
}