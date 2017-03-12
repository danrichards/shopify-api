<?php

namespace ShopifyApi\Models;

/**
 * Class Webhook
 *
 * @method int getId()
 * @method string getAddress()
 * @method string getTopic()
 *
 * @method string setAddress(string $uri)
 * @method string setTopic(string $topic)
 *
 */
class Webhook extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'webhook';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * Delete Webhook
     *
     * @return $this
     */
    public function delete()
    {
        return $this->remove();
    }

}