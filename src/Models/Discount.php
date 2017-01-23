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
 * @method int setUsageLimit(string $usage_limit)
 * @method int setAppliesToId(string $applies_to_id)
 * @method boolean setAppliesOnce(boolean $applies_once)
 * @method boolean setAppliesOncePerCustomer(boolean $applies_once_per_customer)
 * @method string setDiscountType(string $discount_type)
 * @method string setAppliesToResource(string $applies_to_resource)
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
}