<?php

namespace ShopifyApi\Models;

use DateTime;

/**
 * Class Order
 */
class Order extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'order';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * @return DateTime|null
     */
    public function getClosedAt()
    {
        return is_null($date = $this->getOriginal('closed_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setClosedAt($stringOrDateTime)
    {
        $this->data['closed_at'] = $stringOrDateTime instanceof DateTime
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

}