<?php

namespace ShopifyApi;

use BadMethodCallException;
use Guzzle\Http\Client as GuzzleClient;
use InvalidArgumentException;
use ShopifyApi\Api\AbstractApi;
use ShopifyApi\Api\Discount;
use ShopifyApi\Api\Metafield;
use ShopifyApi\Api\Order;
use ShopifyApi\Api\Product;
use ShopifyApi\Api\Shop;
use ShopifyApi\Api\Variants;
use ShopifyApi\Api\Webhook;

/**
 * Simple PHP Shopify client
 *
 * @method \ShopifyApi\Api\Product product()
 * @method \ShopifyApi\Api\Product products()
 * @method \ShopifyApi\Api\Order order()
 * @method \ShopifyApi\Api\Order orders()
 * @method \ShopifyApi\Api\Variants variant()
 * @method \ShopifyApi\Api\Variants variants()
 * @method \ShopifyApi\Api\Metafield metafield()
 * @method \ShopifyApi\Api\Metafield metafields()
 * @method \ShopifyApi\Api\Discount discount()
 * @method \ShopifyApi\Api\Discount discounts()
 * @method \ShopifyApi\Api\Webhook webhook()
 * @method \ShopifyApi\Api\Webhook webhooks()
 */
class Client
{
    /**
     * Constant for authentication method. Indicates the default, but deprecated
     * login with username and token in URL.
     */
    const AUTH_URL_TOKEN = 'url_token';

    /**
     * Constant for authentication method. Not indicates the new login, but allows
     * usage of unauthenticated rate limited requests for given client_id + client_secret
     */
    const AUTH_URL_CLIENT_ID = 'url_client_id';

    /**
     * Constant for authentication method. Indicates the new favored login method
     * with username and password via HTTP Authentication.
     */
    const AUTH_HTTP_PASSWORD = 'http_password';

    /**
     * Constant for authentication method. Indicates the new login method with
     * with username and token via HTTP Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';

    /**
     * @var array
     */
    private $options = array(
        'user_agent'  => 'php-shopify-api (http://github.com/ShineOnCom/php-shopify-api)',
        'timeout'     => 10,
        'api_limit'   => 250,
        'api_version' => 1,
        'cache_dir'   => null,
    );

    /**
     * The Buzz instance used to communicate with Trello
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Instantiate a new Trello client
     *
     * @param null|HttpClient $httpClient Shopify http client
     */
    public function __construct($httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get an API by name
     *
     * @param string $name
     * @return AbstractApi
     *
     * @throws InvalidArgumentException if the requested api does not exist
     */
    public function api($name)
    {
        switch ($name) {
            case 'discount':
            case 'discounts':
                $api = new Discount($this);
                break;
            case 'metafield':
            case 'metafields':
                $api = new Metafield($this);
                break;
            case 'order':
            case 'orders':
                $api = new Order($this);
                break;
            case 'product':
            case 'products':
                $api = new Product($this);
                break;
            case 'shop':
                $api = new Shop($this);
                break;
            case 'variant':
            case 'variants':
                $api = new Variants($this);
                break;
            case 'webhook':
            case 'webhooks':
                $api = new Webhook($this);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Undefined api called: "%s"', $name));
        }

        return $api;
    }

    /**
     * Authenticate a user for all next requests
     *
     * @see \ShopifyApi\Providers\ShopifyServiceProvider
     *
     * @param array $config
     * @return $this
     */
    public function withShop(array $config = [])
    {
        // Guzzle does our REST client and is immutable
        $this->httpClient = new HttpClient($config);
        return $this;
    }

    /**
     * So we may access the Shopify Client with the Facade.
     *
     * @return $this
     */
    public function getClient()
    {
        return $this;
    }

    /**
     * Get Http Client
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return GuzzleClient
     */
    public function getGuzzleClient()
    {
        return $this->getHttpClient()->getClient();
    }

    /**
     * Set Http Client
     *
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get option by name
     *
     * @param string $name the option's name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getOption($name)
    {
        if (! array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * Set option
     *
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException if the option is not defined
     * @throws InvalidArgumentException if the api version is set to an unsupported one
     */
    public function setOption($name, $value)
    {
        if (! array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        $this->options[$name] = $value;
    }
    
    /**
     * Proxies $this->members() to $this->api('members')
     *
     * @param string $name method name
     * @param array  $args arguments
     *
     * @return AbstractApi
     *
     * @throws BadMethodCallException
     */
    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}
