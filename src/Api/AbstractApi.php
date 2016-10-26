<?php

namespace ShopifyApi\Api;

use ShopifyApi\Client;
use BadMethodCallException;
use InvalidArgumentException;
use ShopifyApi\ResponseMediator;

/**
 * Abstract class for Api classes
 */
abstract class AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = '';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = '';

    /** @var Client */
    protected $client;

    /** @var string $path The API path */
    protected static $path;

    /** @var array $fields*/
    public static $fields;

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * @param Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Catches any undefined "get{$field}" calls, and passes them
     * to the getField() if the $field is in the $this->fields property
     *
     * @param string $method    called method
     * @param array  $arguments array of arguments passed to called method
     * @return array
     *
     * @throws BadMethodCallException If the method does not start with "get"
     *                                or the field is not included in the $fields property
     */
    public function __call($method, $arguments)
    {
        if (isset($this->fields) && substr($method, 0, 3) === 'get') {
            $property = lcfirst(substr($method, 3));
            if (in_array($property, $this->fields) && count($arguments) === 2) {
                return $this->getField($arguments[0], $arguments[1]);
            }
        }

        throw new BadMethodCallException(sprintf(
            'There is no method named "%s" in class "%s".',
            $method,
            get_called_class()
        ));
    }

    /**
     * Get field names (properties)
     *
     * @return array array of fields
     */
    public function getFields()
    {
        return static::$fields;
    }

    /**
     * Get a field value by field name
     *
     * @param string $id    the board's id
     * @param array|string $field the field
     * @return mixed field value
     *
     * @throws InvalidArgumentException If the field does not exist
     */
    public function getField($id, $field)
    {
        if (!in_array($field, static::$fields)) {
            throw new InvalidArgumentException(sprintf('There is no field named %s.', $field));
        }

        $response = $this->get($this->getPath($id), ['fields' => implode(',', (array) $field)]);

        if (array_key_exists($sub_key = static::$parameters_wrap, $response)) {
            $response = $response[$sub_key];
        }

        if (count($response) == 1 && array_key_exists($field, $response)) {
            $response = $response[$field];
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getParametersWrap()
    {
        return static::$parameters_wrap;
    }

    /**
     * @return string
     */
    public function getParametersWrapMany()
    {
        return static::$parameters_wrap_many;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     GET parameters.
     * @param array  $request_headers Request Headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    protected function get($path, array $parameters = array(), $request_headers = array())
    {
        $response = $this->client->getHttpClient()
            ->get($path, $parameters, $request_headers);

        return ResponseMediator::getContent($response);
    }

//    /**
//     * Send a HEAD request with query parameters
//     *
//     * @param string $path           Request path.
//     * @param array  $parameters     HEAD parameters.
//     * @param array  $request_headers Request headers.
//     * @return \Guzzle\Http\Message\Response
//     */
//    protected function head($path, array $parameters = array(), $request_headers = array())
//    {
//        $response = $this->client->getHttpClient()->request($path, null, 'HEAD', $request_headers, array(
//            'query' => $parameters,
//        ));
//
//        return $response;
//    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $request_headers Request headers.
     * @return mixed
     */
    protected function post($path, array $parameters = array(), $request_headers = array())
    {
        return $this->postRaw(
            $path,
            $this->createParametersBody($parameters),
            $request_headers
        );
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path           Request path.
     * @param mixed  $body           Request body.
     * @param array  $request_headers Request headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    protected function postRaw($path, $body, $request_headers = array())
    {
        $response = $this->client->getHttpClient()->post(
            $path,
            $body,
            $request_headers
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $request_headers Request headers.
     * @param bool $normalize
     * @return mixed
     */
    protected function patch($path, array $parameters = [], $request_headers = [], $normalize = true)
    {
        if ($normalize) {
            $parameters = isset($parameters[static::$parameters_wrap])
                ? $parameters : [static::$parameters_wrap => $parameters];

            $parameters[static::$parameters_wrap] = $this
                ->createParametersBody($parameters[static::$parameters_wrap]);
        }

        $parameters[static::$parameters_wrap] = $this
            ->removeExcessParameters($parameters[static::$parameters_wrap]);

        $body = json_encode($parameters);

        $response = $this->client
            ->getHttpClient()
            ->put($path, $body, $request_headers);

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $request_headers Request headers.
     * @param bool $normalize
     * @return mixed
     */
    protected function put($path, array $parameters = [], $request_headers = [], $normalize = true)
    {
        return $this->patch($path, $parameters, $request_headers, $normalize);
    }

//    /**
//     * Send a DELETE request with JSON-encoded parameters.
//     *
//     * @param string $path           Request path.
//     * @param array  $parameters     POST parameters to be JSON encoded.
//     * @param array  $request_headers Request headers.
//     * @return mixed
//     */
//    protected function delete($path, array $parameters = array(), $request_headers = array())
//    {
//        $response = $this->client->getHttpClient()->delete(
//            $path,
//            $this->createParametersBody($parameters),
//            $request_headers
//        );
//
//        return ResponseMediator::getContent($response);
//    }

    /**
     * Prepare request parameters.
     *
     * @param array $parameters Request parameters
     * @return null|string
     */
    protected function createParametersBody(array $parameters)
    {
        foreach ($parameters as $name => $parameter) {
//            if (is_bool($parameter)) {
//                $parameters[$name] = $parameter ? 'true' : 'false';
//            } elseif (is_array($parameter)) {
//                foreach ($parameter as $sub_name => $sub_parameter) {
//                    if (is_bool($sub_parameter)) {
//                        $sub_parameter = $sub_parameter ? 'true' : 'false';
//                    }
//                    $parameters[$name.'/'.$sub_name] = $sub_parameter;
//                }
//                unset($parameters[$name]);
//            } elseif ($parameter instanceof DateTime) {
//                $parameters[$name] = $parameter->format('c');
//            }
        }

        return $parameters;
    }

    /**
     * @param null $id
     * @return mixed|string
     */
    protected function getPath($id = null)
    {
        if ($id) {
            return preg_replace('/\#id\#/', rawurlencode($id), static::$path);
        }

        return static::$path;
    }

//    /**
//     * Validate parameters array
//     *
//     * @param string[] $required required properties (array keys)
//     * @param array $params   array to check for existence of the required keys
//     *
//     * @throws MissingArgumentException if a required parameter is missing
//     */
//    protected function validateRequiredParameters(array $required, array $params)
//    {
//        foreach ($required as $param) {
//            if (!isset($params[$param])) {
//                throw new MissingArgumentException(sprintf('The "%s" parameter is required.', $param));
//            }
//        }
//    }
//
//    /**
//     * Validate allowed parameters array
//     * Checks whether the passed parameters are allowed
//     *
//     * @param string[]        $allowed allowed properties
//     * @param array|string $params  array to check
//     * @param string $paramName
//     * @return array array of validated parameters
//     *
//     * @throws InvalidArgumentException if a parameter is not allowed
//     */
//    protected function validateAllowedParameters(array $allowed, $params, $paramName)
//    {
//        if (!is_array($params)) {
//            $params = array($params);
//        }
//
//        foreach ($params as $param) {
//            if (!in_array($param, $allowed)) {
//                throw new InvalidArgumentException(sprintf(
//                    'The "%s" parameter may contain only values within "%s". "%s" given.',
//                    $paramName,
//                    implode(", ", $allowed),
//                    $param
//                ));
//            }
//        }
//
//        return $params;
//    }
//
//    /**
//     * Validate that the params array includes at least one of
//     * the keys in a given array
//     *
//     * @param string[] $atLeastOneOf allowed properties
//     * @param array $params       array to check
//     * @return boolean
//     *
//     * @throws MissingArgumentException
//     */
//    protected function validateAtLeastOneOf(array $atLeastOneOf, array $params)
//    {
//        foreach ($atLeastOneOf as $param) {
//            if (isset($params[$param])) {
//                return true;
//            }
//        }
//
//        throw new MissingArgumentException(sprintf(
//            'You need to provide at least one of the following parameters "%s".',
//            implode('", "', $atLeastOneOf)
//        ));
//    }

    /**
     * Remove our fields that don't need to be passed to Trello.
     * This prevents trello from throwing a "Error parsing body: too many parameters" error
     * on requests with a card that has a lot of content.
     *
     * @param array $parameters
     * @return array
     */
    protected function removeExcessParameters($parameters)
    {
        // Remove our fields that don't need to be passed to Trello.
        foreach(static::$ignore_on_update_fields as $ignored_field) {
            unset($parameters[$ignored_field]);
        }

        return $parameters;
    }

    /**
     * Take a shot as what the api_name should be?
     */
    protected function guessApiName()
    {
        $name = explode("\\", get_class($this));
        $name = strtolower(array_pop($name));
        return $name;
    }

}
