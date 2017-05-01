<?php

namespace ShopifyApi\Api;

/**
 * Class Shop
 *
 * API calls that can be carried out on a Product
 */
class Shop extends AbstractApi
{

    /** @var string $parameters_wrap */
    protected static $parameters_wrap = 'shop';

    /** @var string $parameters_wrap_many */
    protected static $parameters_wrap_many = 'shops';

    /** @var string $path */
    protected static $path = '/admin/shop.json';

    /** @var array $fields */
    public static $fields = [
        'id',
        'name',
        'email',
        'domain',
        'created_at',
        'province',
        'country',
        'address1',
        'zip',
        'city',
        'source',
        'phone',
        'updated_at',
        'customer_email',
        'latitude',
        'longitude',
        'primary_location_id',
        'primary_locale',
        'address2',
        'country_code',
        'country_name',
        'currency',
        'timezone',
        'iana_timezone',
        'shop_owner',
        'money_format',
        'money_with_currency_format',
        'weight_unit',
        'province_code',
        'taxes_included',
        'tax_shipping',
        'county_taxes',
        'plan_display_name',
        'plan_name',
        'has_discounts',
        'has_gift_cards',
        'myshopify_domain',
        'google_apps_domain',
        'google_apps_login_enabled',
        'money_in_emails_format',
        'money_with_currency_in_emails_format',
        'eligible_for_payments',
        'requires_extra_payments_agreement',
        'password_enabled',
        'has_storefront',
        'eligible_for_card_reader_giveaway',
        'finances',
        'setup_required',
        'force_ssl',
    ];

    /** @var array $ignore_on_update_fields */
    public static $ignore_on_update_fields = [];

    /**
     * Find a Product
     *
     * @link https://help.shopify.com/api/reference/shop#show
     *
     * @return array Shop info
     */
    public function show()
    {
        return $this->get($this->getPath());
    }

}