<?php

namespace ShopifyApi;

use BadMethodCallException;
use ShopifyApi\Models\Order;
use ShopifyApi\Models\Product;

/**
 * Class Manager
 *
 * @method Client product()
 */
class Manager
{

    /** @var Client $client */
    protected $client;

    /**
     * Constructor.
     *
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the instance with the Facade. Shopify::getInstance();
     *
     * @return $this
     */
    public function getInstance()
    {
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get organization by id or create a new one
     *
     * @param string $id the organization's id
     *
     * @return Product
     */
    public function getProduct($id = null)
    {
        return new Product($this->client, $id);
    }

    /**
     * Get board by id or create a new one
     *
     * @param string $id the board's id
     *
     * @return Order
     */
    public function getOrder($id = null)
    {
        return new Order($this->client, $id);
    }

    /**
     * @param string $method method name
     * @param array  $args arguments
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (method_exists($this->client, $method)) {
            return call_user_func_array([$this->client, $method], $args);
        } else {
            throw new BadMethodCallException("The Manager __call method fires methods that exist on ".get_class($this->client));
        }
    }

}
