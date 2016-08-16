<?php

namespace ShopifyApi\Support;

use Illuminate\Support\Facades\Facade;

/**
 * Class ShopifyFacade
 *
 * Facade for the Laravel Framework
 */
class ShopifyFacade extends Facade
{

    /**
     * Return \ShopifyApi\Manager singleton.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'shopify'; }

}