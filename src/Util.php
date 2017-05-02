<?php

namespace ShopifyApi;

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
     * @param $token
     * @param $data
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
     * @param $code
     * @param $shop
     * @param $state
     * @param $timestamp
     * @return bool
     */
    public static function validAppHmac($hmac, $secret, array $data)
    {
        $accepts = ['code', 'protocol', 'shop', 'state', 'timestamp'];
        $message = [];
        foreach($accepts as $key) {
            if (isset($data[$key])) {
                $message[] = "{$key}={$data[$key]}";
            }
        }

        $message = implode('&', $message);

        $calculated_hmac = hash_hmac(
            $alorithm = 'sha256', 
            $message, 
            $secret
        );

        return $hmac == $calculated_hmac;
    }

}