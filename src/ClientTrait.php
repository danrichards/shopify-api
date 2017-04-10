<?php

namespace ShopifyApi;

/**
 * Class ClientTrait
 */
trait ClientTrait
{

    /** @var Client $client */
    protected $client;

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getShop()
    {
        return 'ok';
        $shop = $this->getClient()->getHttpClient()->getOption('base_url');
        $shop = preg_replace('/https?\:\/\//', '', $shop);
        return rtrim($shop, "/");
    }

    /**
     * @return string
     */
    public function getShopName()
    {
        return str_replace(".myshopify.com", '', $this->getShop());
    }

}