<?php

namespace ShopifyApi\Models;

use DateTime;
use BadMethodCallException;
use ShopifyApi\Util;
use ShopifyApi\Client;
use ShopifyApi\Api\AbstractApi;

/**
 * Class AbstractModel
 */
abstract class AbstractModel
{

    /** @var string $api_name */
    protected static $api_name;

    /** @var array $load_params */
    protected static $load_params = [];

    /** @var Client $client */
    protected $client;

    /** @var AbstractApi $api */
    protected $api;

    /** @var array $fields */
    protected $fields;

    /** @var string $id */
    protected $id;

    /** @var array $data */
    protected $data;

    /**
     * Constructor.
     *
     * @param $client Client
     * @param string  $id       The id of the object
     */
    public function __construct(Client $client, $id = null)
    {
        $this->client = $client;
        $this->api = $client->api(static::$api_name);
        $this->fields = $this->api->getFields();

        if ($id) {
            $this->id = $id;
            $this->refresh();
        }
    }

    /**
     * Because __get() was being a dick.
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $chunks = explode('_', Util::snake($method));

        if (count($chunks) > 1) {
            $action = array_shift($chunks);
            $key = implode('_', $chunks);

            switch ($action) {
                case 'get':
                    return $this->data[$key];
                    break;
                case 'set':
                    $this->data[$key] = $arguments[0];
                    return $this;
                    break;
                case 'has':
                    return array_key_exists($key, $this->data);
                    break;
            }
        }

        throw new BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $method . '()');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getOriginal($name)
    {
        return $this->data[$name];
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setCreatedAt($stringOrDateTime)
    {
        $this->data['created_at'] = $stringOrDateTime instanceof DateTime
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt()
    {
        return is_null($date = $this->getOriginal('created_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt()
    {
        return is_null($date = $this->getOriginal('updated_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setUpdatedAt($stringOrDateTime)
    {
        $this->data['updated_at'] = $stringOrDateTime instanceof DateTime
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->preRefresh();
        $this->data = $this->api->show($this->id, static::$load_params);
        if (is_array($this->data) && array_key_exists(static::$api_name, $this->data)) {
            $this->data = $this->data[static::$api_name];
        }
        $this->postRefresh();

        return $this;
    }

    /**
     * @return $this
     */
    public function save()
    {
        try {
            $this->preSave();
            $this->id ? $this->update() : $this->create();
            $this->postSave();
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                "You can't %s %s objects.",
                $this->id ? 'update' : 'create',
                get_called_class()
            ));
        }

        return $this->refresh();
    }

    /**
     * @return $this
     */
    public function remove()
    {
        try {
            $this->preRemove();
            $this->api->remove($this->id);
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
     * Update the object through API
     *
     * @return $this
     */
    protected function update()
    {
        $this->preUpdate();
        $this->data = $this->api->update($this->id, $this->data);
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
        $this->data = $this->api->create($this->data);
        $this->id   = $this->data['id'];
        $this->postCreate();

        return $this;
    }

    /**
     * Called before saving (creating or updating) an entity
     */
    protected function preSave()
    {
    }

    /**
     * Called after saving (creating or updating) an entity
     */
    protected function postSave()
    {
    }

    /**
     * Called before creating an entity
     */
    protected function preCreate()
    {
    }

    /**
     * Called after creating an entity
     */
    protected function postCreate()
    {
    }

    /**
     * Called before updating an entity
     */
    protected function preUpdate()
    {
    }

    /**
     * Called after updating an entity
     */
    protected function postUpdate()
    {
    }

    /**
     * Called before refreshing an entity
     */
    protected function preRefresh()
    {
    }

    /**
     * Called after refreshing an entity
     */
    protected function postRefresh()
    {
    }

    /**
     * Called before removing an entity
     */
    protected function preRemove()
    {
    }

    /**
     * Called after removing an entity
     */
    protected function postRemove()
    {
    }

}
