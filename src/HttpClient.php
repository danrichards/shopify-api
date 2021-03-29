<?php

namespace ShopifyApi;

use ErrorException;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class HttpClient
 */
class HttpClient
{

    /** @var array $options */
    protected $options = array(
        'user_agent'  => 'php-shopify-api (http://github.com/ShineOnCom/php-shopify-api)',
        'timeout'     => 10
    );

    /** @var GuzzleClient $client */
    protected $client;

    /** @var array $headers */
    protected $headers = [];

    /** @var Response $lastResponse */
    private $lastResponse;

    /** @var Request $lastRequest */
    private $lastRequest;

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = array(), $client = null)
    {
        $this->options = array_merge($this->options, $options);
        $client = $client ?: new GuzzleClient($this->options['base_url'], $this->options);
        $this->client  = $client;
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
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        return $this->request($path, $parameters, 'GET', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body, array $headers = array())
    {
        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function request($path, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array())
    {
        $request = $this->createRequest($httpMethod, $path, $body, $headers, $options);

        $this->lastRequest  = $request;

        try {
            $response = $this->client->send($request);
        } catch (ClientErrorResponseException $e) {
            $responseBody = $e->getResponse()->getBody(true);
            throw new RuntimeException(
                sprintf('%s\n[body] %s', $e->getMessage(),$responseBody), $e->getCode(), $e
            );
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $this->lastResponse = $response;

        $api_deprecated_reason = $response->getHeader('X-Shopify-API-Deprecated-Reason');
        $api_version_warning = $response->getHeader('X-Shopify-Api-Version-Warning');
        if (($api_deprecated_reason || $api_version_warning) && function_exists('logger')) {
            $api_version = $response->getHeader('X-Shopify-Api-Version');
            logger('vendor:dan:shopify-api:deprecated',
                compact('api_version', 'api_version_warning', 'api_deprecated_reason') +
                ['request' => compact('httpMethod', 'path', 'body', 'options')]);
        }

        return $response;
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Make a request with Guzzle
     *
     * @param string $httpMethod
     * @param string $path
     * @param null $body
     * @param array $headers
     * @param array $options
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function createRequest($httpMethod, $path, $body = null, array $headers = array(), array $options = array())
    {
        if ($httpMethod === 'GET' && $body) {
            $path .= (false === strpos($path, '?') ? '?' : '&');
            $path .= utf8_encode(http_build_query($body, '', '&'));
        }

        return $this->client->createRequest(
            $httpMethod,
            $path,
            array_merge($this->headers, $headers),
            $body,
            $options
        );
    }

    /**
     * @return GuzzleClient
     */
    public function getClient()
    {
        return $this->client;
    }

}
