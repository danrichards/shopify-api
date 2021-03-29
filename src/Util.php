<?php

namespace ShopifyApi;

use Guzzle\Http\Message\Response;

/**
 * Class Util
 */
class Util
{

    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
        }

        return $value;
    }

    /**
     * Convert the given string to lower-case.
     *
     * @param  string  $value
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * @param string $hmac
     * @param string $token
     * @param string $data
     * @return bool
     */
    public static function validWebhookHmac($hmac, $token, $data)
    {
        $calculated_hmac = hash_hmac(
            $algorithm = 'sha256',
            $data,
            $token,
            $raw_output = true
        );

        return $hmac == base64_encode($calculated_hmac);
    }

    /**
     * @param $hmac
     * @param $secret
     * @param array $data
     * @return bool
     */
    public static function validAppHmac($hmac, $secret, array $data)
    {
        $message = [];

        $keys = array_keys($data);
        sort($keys);
        foreach($keys as $key) {
            $message[] = "{$key}={$data[$key]}";
        }

        $message = implode('&', $message);

        $calculated_hmac = hash_hmac(
            $alorithm = 'sha256', 
            $message, 
            $secret
        );

        return $hmac == $calculated_hmac;
    }

    /**
     * @param Response $response
     * @return mixed
     */
    public static function getContent(Response $response)
    {
        $body    = $response->getBody(true);

        $content = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return $content;
    }

    /**
     * @return bool
     */
    public static function isLaravel()
    {
        return defined('LARAVEL_START') && ! static::isLumen();
    }

    /**
     * @return bool
     */
    public static function isLumen()
    {
        return function_exists('app')
            && preg_match('/lumen/i', app()->version());
    }
}