<?php

namespace ShopifyApi\Models;

use BadMethodCallException;
use DateTime;
use DateTimeZone;
use ShopifyApi\Client;

/**
 * Class Shop
 *
 * @method string getName()
 * @method string getEmail()
 * @method string getDomain()
 * @method string getProvince()
 * @method string getCountry()
 * @method string getAddress1()
 * @method string getZip()
 * @method string getCity()
 * @method string getSource()
 * @method string getPhone()
 * @method string getCustomerEmail()
 * @method string getLatitude()
 * @method string getLongitude()
 * @method string getPrimaryLocationId()
 * @method string getLocale()
 * @method string getAddress2()
 * @method string getCountryCode()
 * @method string getCountryName()
 * @method string getCurrency()
 * @method string getIanaTimezone()
 * @method string getTimezone()
 * @method string getShopOwner()
 * @method string getMoneyFormat()
 * @method string getMoneyWithCurrencyFormat()
 * @method string getWeightUnit()
 * @method string getProvinceCode()
 * @method string getTaxesIncluded()
 * @method string getTaxShipping()
 * @method string getCountyTaxes()
 * @method string getPlanDisplayName()
 * @method string getPlanName()
 * @method string getHasDiscounts()
 * @method string getHasGiftCards()
 * @method string getMyshopifyDomain()
 * @method string getGoogleAppsDomain()
 * @method string getGoogleAppsLoginEnabled()
 * @method string getMoneyInEmailsFormat()
 * @method string getEligibleForPayments()
 * @method string getRequiresExtraPaymentsAgreement()
 * @method string getPasswordEnabled()
 * @method string getHasStorefront()
 * @method string getEligibleForCardReaderGiveaway()
 * @method string getFinances()
 * @method string getSetupRequired()
 * @method string getForceSsl()
 * 
 * @method $this setName(string $name)
 * @method $this setEmail(string $email)
 * @method $this setDomain(string $domain)
 * @method $this setProvince(string $province)
 * @method $this setCountry(string $country)
 * @method $this setAddress1(string $address1)
 * @method $this setZip(string $zip)
 * @method $this setCity(string $city)
 * @method $this setSource(string $source)
 * @method $this setPhone(string $phone)
 * @method $this setCustomerEmail(string $customer_email)
 * @method $this setLatitude(float $latitude)
 * @method $this setLongitude(float $longitude)
 * @method $this setPrimaryLocationId(int $primary_location_id)
 * @method $this setLocale(string $locale)
 * @method $this setAddress2(string $address2)
 * @method $this setCountryCode(string $country_code)
 * @method $this setCountryName(string $country_name)
 * @method $this setCurrency(string $currency)
 * @method $this setIanaTimezone(string $iana_timezone)
 * @method $this setTimezone(string $timezone)
 * @method $this setShopOwner(string $shop_owner)
 * @method $this setMoneyFormat(string $money_format)
 * @method $this setMoneyWithCurrencyFormat(string $money_with_currency_format)
 * @method $this setWeightUnit(string $weight_unit)
 * @method $this setProvinceCode(string $province_code)
 * @method $this setTaxesIncluded(bool $taes_included)
 * @method $this setTaxShipping(bool $tax_shipping)
 * @method $this setCountyTaxes(bool $county_taxes)
 * @method $this setPlanDisplayName(string $plan_display_name)
 * @method $this setPlanName(string $plan_name)
 * @method $this setHasDiscounts(bool $has_discounts)
 * @method $this setHasGiftCards(bool $has_gift_cards)
 * @method $this setMyshopifyDomain(string $myshopify_domain)
 * @method $this setGoogleAppsDomain(string $google_apps_domain)
 * @method $this setGoogleAppsLoginEnabled(bool $google_apps_login_enabled)
 * @method $this setMoneyInEmailsFormat(string $money_in_emails_format)
 * @method $this setEligibleForPayments(string $eligible_for_payments)
 * @method $this setRequiresExtraPaymentsAgreement(bool $requires_extra_payments_agreement)
 * @method $this setPasswordEnabled(bool $password_enabled)
 * @method $this setHasStorefront(bool $has_storefront)
 * @method $this setEligibleForCardReaderGiveaway(bool $eligible_for_card_reader_giveaway)
 * @method $this setFinances(string $finances)
 * @method $this setSetupRequired(bool $setup_required)
 * @method $this setForceSsl(bool $force_ssl)
 *
 * @method bool hasName()
 * @method bool hasEmail()
 * @method bool hasDomain()
 * @method bool hasProvince()
 * @method bool hasCountry()
 * @method bool hasAddress1()
 * @method bool hasZip()
 * @method bool hasCity()
 * @method bool hasSource()
 * @method bool hasPhone()
 * @method bool hasCustomerEmail()
 * @method bool hasLatitude()
 * @method bool hasLongitude()
 * @method bool hasPrimaryLocationId()
 * @method bool hasLocale()
 * @method bool hasAddress2()
 * @method bool hasCountryCode()
 * @method bool hasCountryName()
 * @method bool hasCurrency()
 * @method bool hasIanaTimezone()
 * @method bool hasTimezone()
 * @method bool hasShopOwner()
 * @method bool hasMoneyFormat()
 * @method bool hasMoneyWithCurrencyFormat()
 * @method bool hasWeightUnit()
 * @method bool hasProvinceCode()
 * @method bool hasTaxesIncluded()
 * @method bool hasTaxShipping()
 * @method bool hasCountyTaxes()
 * @method bool hasPlanDisplayName()
 * @method bool hasPlanName()
 * @method bool hasHasDiscounts()
 * @method bool hasHasGiftCards()
 * @method bool hasMyshopifyDomain()
 * @method bool hasGoogleAppsDomain()
 * @method bool hasGoogleAppsLoginEnabled()
 * @method bool hasMoneyInEmailsFormat()
 * @method bool hasEligibleForPayments()
 * @method bool hasRequiresExtraPaymentsAgreement()
 * @method bool hasPasswordEnabled()
 * @method bool hasHasStorefront()
 * @method bool hasEligibleForCardReaderGiveaway()
 * @method bool hasFinances()
 * @method bool hasSetupRequired()
 * @method bool hasForceSsl()
 */
class Shop extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'shop';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * Constructor.
     *
     * @param $client Client
     * @param array|string  $id_or_data Id or the data
     */
    public function __construct(Client $client, array $data = [])
    {
        // get the shop from the client.
        $this->client = $client;

        $this->api = $client->api(static::$api_name);
        $this->fields = $this->api->getFields();

        if (empty($data)) {
            $this->refresh();
        } else {
            $this->setData($data);
        }
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->preRefresh();
        $this->data = $this->api->show();
        if (is_array($this->data) && array_key_exists(static::$api_name, $this->data)) {
            $this->setData($this->data[static::$api_name]);
        }
        $this->postRefresh();

        return $this;
    }

    /**
     * Update the object through API
     *
     * @return $this
     * @throws \BadMethodCallException
     */
    protected function update()
    {
        throw new BadMethodCallException("Method not allowed on Shop resource.");
    }

    /**
     * Create the object through API
     *
     * @return $this
     */
    protected function create()
    {
        throw new BadMethodCallException("Method not allowed on Shop resource.");
    }

}