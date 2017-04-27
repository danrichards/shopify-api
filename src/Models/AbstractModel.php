<?php

namespace ShopifyApi\Models;

use DateTime;
use DateTimeZone;
use JsonSerializable;
use ShopifyApi\ClientTrait;
use ShopifyApi\Util;
use ShopifyApi\Client;
use BadMethodCallException;
use ShopifyApi\Api\AbstractApi;

/**
 * Class AbstractModel
 */
abstract class AbstractModel implements JsonSerializable
{

    use ClientTrait;

    /** @var string $api_name */
    protected static $api_name;

    /** @var array $load_params */
    protected static $load_params = [];

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
     * @param array|string  $id_or_data Id or the data
     */
    public function __construct(Client $client, $id_or_data = null)
    {
        $this->client = $client;

        // Skip api call (refresh) if we already have the data
        if (is_array($id_or_data)) {
            $this->api = $client->api(
                static::$api_name,
                isset($id_or_data['id']) ? $id_or_data['id'] : null
            );
            $this->fields = $this->api->getFields();
            $this->setData($id_or_data);

        // Otherwise, pull data from api
        } else {
            $this->api = $client->api(static::$api_name, $id_or_data);
            $this->fields = $this->api->getFields();
            if ($id_or_data) {
                $this->id = $id_or_data;
                $this->refresh();
            }
        }
    }

    /**
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

            $strict = false;

            if (defined('SHOPIFY_API_MODE')) {
                if (SHOPIFY_API_MODE == 'strict') {
                    $strict = true;
                }
            }

            if ($strict) {
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
            } else {
                switch ($action) {
                    case 'get':
                        return array_key_exists($key, $this->data)
                            ? $this->data[$key] : null;
                        break;
                    case 'set':
                        $this->data[$key] = isset($arguments[0])
                            ? $arguments[0] : null;
                        return $this;
                        break;
                    case 'has':
                        return array_key_exists($key, $this->data);
                        break;
                }
            }
        }

        throw new BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $method . '()');
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
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
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
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
     * @param $name
     * @param $value
     * @return mixed
     */
    public function setOriginal($name, $value)
    {
        return $this->data[$name] = $value;
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setCreatedAt($stringOrDateTime)
    {
        $this->data['created_at'] = $stringOrDateTime instanceof DateTime || $stringOrDateTime instanceof \Carbon\Carbon
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    /**
     * @param DateTimeZone $time_zone
     * @return DateTime|null
     */
    public function getCreatedAt(DateTimeZone $time_zone = null)
    {
        return is_null($date = $this->getOriginal('created_at'))
            ? $date : new DateTime($date, $time_zone);
    }

    /**
     * @param DateTimeZone $time_zone
     * @return DateTime|null
     */
    public function getUpdatedAt(DateTimeZone $time_zone = null)
    {
        return is_null($date = $this->getOriginal('updated_at'))
            ? $date : new DateTime($date, $time_zone);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setUpdatedAt($stringOrDateTime)
    {
        $this->data['updated_at'] = $stringOrDateTime instanceof DateTime || $stringOrDateTime instanceof \Carbon\Carbon
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
            $this->setData($this->data[static::$api_name]);
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
     * Get multiple results from the Api and map them to Models
     *
     * @param array $params
     * @return array
     */
    public function all(array $params = [])
    {
        $all = $this->api->all($params);

        if (! empty($all)) {
            $all = $all[$this->api->getParametersWrapMany()];
        }

        $all = array_map(function($product) {
            return (new static($this->client))->setData($product);
        }, $all);

        return $all;
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
        $this->data = $this->api->create($this->data)[static::$api_name];
        $this->id = $this->data['id'];
        $this->postCreate();

        return $this;
    }

    /**
     * Called before saving (creating or updating) an entity
     */
    protected function preSave()
    {
        $this->setUpdatedAt(new DateTime('now'));
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

    /**
     * @return string
     */
    public function jsonSerialize ()
    {
        return json_encode($this->getData());
    }

}
