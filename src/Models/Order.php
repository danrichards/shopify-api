<?php

namespace ShopifyApi\Models;

use DateTime;
use ShopifyApi\Models\Traits\OwnsMetafields;
use ShopifyApi\Models\Traits\Taggable;
use stdClass;

/**
 * Class Order
 *
 * @method string getEmail()
 * @method string getNumber()
 * @method string getNote()
 * @method string getToken()
 * @method string getGateway()
 * @method bool getTest()
 * @method string getTotalPrice()
 * @method string getSubtotalPrice()
 * @method string getTotalWeight()
 * @method string getTotalTax()
 * @method bool getTaxesIncluded()
 * @method string getCurrency()
 * @method string getFinancialStatus()
 * @method bool getConfirmed()
 * @method string getTotalDiscounts()
 * @method string getTotalLineItemsPrice()
 * @method string getCartToken()
 * @method bool getBuyerAcceptsMarketing()
 * @method string getName()
 * @method string getReferringSite()
 * @method string getLandingSite()
 * @method string getCancelReason()
 * @method string getTotalPriceUsd()
 * @method string getCheckoutToken()
 * @method string getReference()
 * @method string getUserId()
 * @method string getLocationId()
 * @method string getSourceIdentifier()
 * @method string getSourceUrl()
 * @method string getDeviceId()
 * @method string getBrowserIp()
 * @method string getLandingSiteRef()
 * @method string getOrderNumber()
 * @method array getDiscountCodes()
 * @method array getNoteAttributes()
 * @method array getPaymentGatewayNames()
 * @method string getProcessing_Method()
 * @method string getCheckoutId()
 * @method string getSourceName()
 * @method string getFulfillmentStatus()
 * @method array getTaxLines()
 * @method string getContactEmail()
 * @method string getOrderStatusUrl()
 * @method array getLineItems()
 * @method array getShippingLines()
 * @method stdClass getBillingAddress()
 * @method stdClass getShippingAddress()
 * @method array getFulfillments()
 * @method stdClass getClientDetails()
 * @method array getRefunds()
 * @method stdClass getPaymentDetails()
 * @method stdClass getCustomer()
 *
 * @method string setEmail(string $email)
 * @method string setNumber(string $number)
 * @method string setNote(string $note)
 * @method string setToken(string $token)
 * @method string setGateway(string $gateway)
 * @method bool setTest(bool $test)
 * @method string setTotalPrice(string $total_price)
 * @method string setSubtotalPrice(string $subtotal_price)
 * @method string setTotalWeight(string $total_weight)
 * @method string setTotalTax(string $total_tax)
 * @method bool setTaxesIncluded(bool $taxes_included)
 * @method string setCurrency(string $currency)
 * @method string setFinancialStatus(string $financial_status)
 * @method bool setConfirmed(bool $confirmed)
 * @method string setTotalDiscounts(string $total_discounts)
 * @method string setTotalLineItemsPrice(string $total_line_items_price)
 * @method string setCartToken(string $cart_token)
 * @method bool setBuyerAcceptsMarketing(bool $buyer_accepts_marketing)
 * @method string setName(string $name)
 * @method string setReferringSite(string $referring_site)
 * @method string setLandingSite(string $landing_site)
 * @method string setCancelReason(string $cancel_reason)
 * @method string setTotalPriceUsd(string $total_price_usd)
 * @method string setCheckoutToken(string $checkout_token)
 * @method string setReference(string $reference)
 * @method string setUserId(string $user_id)
 * @method string setLocationId(string $location_id)
 * @method string setSourceIdentifier(string $source_identifier)
 * @method string setSourceUrl(string $source_url)
 * @method string setDeviceId(string $device_id)
 * @method string setBrowserIp(string $browser_ip)
 * @method string setLandingSiteRef(string $landing_site_ref)
 * @method string setOrderNumber(string $order_number)
 * @method array setDiscountCodes(array $discount_codes)
 * @method array setNoteAttributes(array $note_attributes)
 * @method array setPaymentGatewayNames(array $payment_gateway_names)
 * @method string setProcessing_Method(string $processing_method)
 * @method string setCheckoutId(string $checkout_id)
 * @method string setSourceName(string $source_name)
 * @method string setFulfillmentStatus(string $fulfillment_status)
 * @method array setTaxLines(array $tax_lines)
 * @method string setContactEmail(string $contact_email)
 * @method string setOrderStatusUrl(string $order_status_url)
 * @method array setLineItems(array $line_items)
 * @method array setShippingLines(array $shipping_lines)
 * @method stdClass setBillingAddress(stdClass $billing_address)
 * @method stdClass setShippingAddress(stdClass $shipping_address)
 * @method array setFulfillments(array $fulfillments)
 * @method stdClass setClientDetails($client_details)
 * @method array setRefunds(array $refunds)
 * @method stdClass setPaymentDetails(stdClass $payment_details)
 * @method stdClass setCustomer(stdClass $customer)
 *
 * @method bool hasEmail()
 * @method bool hasNumber()
 * @method bool hasNote()
 * @method bool hasToken()
 * @method bool hasGateway()
 * @method bool hasTest()
 * @method bool hasTotalPrice()
 * @method bool hasSubtotalPrice()
 * @method bool hasTotalWeight()
 * @method bool hasTotalTax()
 * @method bool hasTaxesIncluded()
 * @method bool hasCurrency()
 * @method bool hasFinancialStatus()
 * @method bool hasConfirmed()
 * @method bool hasTotalDiscounts()
 * @method bool hasTotalLineItemsPrice()
 * @method bool hasCartToken()
 * @method bool hasBuyerAcceptsMarketing()
 * @method bool hasName()
 * @method bool hasReferringSite()
 * @method bool hasLandingSite()
 * @method bool hasCancelReason()
 * @method bool hasTotalPriceUsd()
 * @method bool hasCheckoutToken()
 * @method bool hasReference()
 * @method bool hasUserId()
 * @method bool hasLocationId()
 * @method bool hasSourceIdentifier()
 * @method bool hasSourceUrl()
 * @method bool hasDeviceId()
 * @method bool hasBrowserIp()
 * @method bool hasLandingSiteRef()
 * @method bool hasOrderNumber()
 * @method bool hasDiscountCodes()
 * @method bool hasNoteAttributes()
 * @method bool hasPaymentGatewayNames()
 * @method bool hasProcessing_Method()
 * @method bool hasCheckoutId()
 * @method bool hasSourceName()
 * @method bool hasFulfillmentStatus()
 * @method bool hasTaxLines()
 * @method bool hasContactEmail()
 * @method bool hasOrderStatusUrl()
 * @method bool hasLineItems()
 * @method bool hasShippingLines()
 * @method bool hasBillingAddress()
 * @method bool hasShippingAddress()
 * @method bool hasFulfillments()
 * @method bool hasClientDetails()
 * @method bool hasRefunds()
 * @method bool hasPaymentDetails()
 * @method bool hasCustomer()
 */
class Order extends AbstractModel
{

    use OwnsMetafields, Taggable;

    // Financial statuses from Shopify
    const FINANCIAL_STATUS_AUTHORIZED = 'authorized';
    const FINANCIAL_STATUS_PAID = 'paid';
    const FINANCIAL_STATUS_PARTIALLY_PAID = 'partially_paid';
    const FINANCIAL_STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const FINANCIAL_STATUS_PENDING= 'pending';
    const FINANCIAL_STATUS_REFUNDED = 'refunded';
    const FINANCIAL_STATUS_VOIDED = 'voided';

    /** @var array $financial_statuses */
    public static $financial_statuses = [
        self::FINANCIAL_STATUS_AUTHORIZED,
        self::FINANCIAL_STATUS_PAID,
        self::FINANCIAL_STATUS_PARTIALLY_PAID,
        self::FINANCIAL_STATUS_PARTIALLY_REFUNDED,
        self::FINANCIAL_STATUS_PENDING,
        self::FINANCIAL_STATUS_REFUNDED,
        self::FINANCIAL_STATUS_VOIDED
    ];

    // Fulfillment statuses from Shopify
    const FULFILLMENT_STATUS_FILLED = 'fulfilled';
    const FULFILLMENT_STATUS_PARTIAL = 'partial';
    const FULFILLMENT_STATUS_UNFILLED = null;

    /** @var array $fulfillment_statuses */
    public static $fulfillment_statuses = [
        self::FULFILLMENT_STATUS_FILLED,
        self::FULFILLMENT_STATUS_PARTIAL,
        self::FULFILLMENT_STATUS_UNFILLED
    ];

    // Risk recommendations from Shopify
    const RISK_RECOMMENDATION_LOW = 'accept';
    const RISK_RECOMMENDATION_MEDIUM = 'investigate';
    const RISK_RECOMMENDATION_HIGH = 'cancel';

    /** @var array $risk_statuses */
    public static $risk_statuses = [
        self::RISK_RECOMMENDATION_LOW,
        self::RISK_RECOMMENDATION_MEDIUM,
        self::RISK_RECOMMENDATION_HIGH
    ];

    /** @var string $api_name */
    protected static $api_name = 'order';

    /** @var array $load_params */
    protected static $load_params = [];

    /**
     * @return DateTime|null
     */
    public function getClosedAt()
    {
        return is_null($date = $this->getOriginal('closed_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setClosedAt($stringOrDateTime)
    {
        $this->data['closed_at'] = $stringOrDateTime instanceof DateTime || $stringOrDateTime instanceof \Carbon\Carbon
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCancelledAt()
    {
        return is_null($date = $this->getOriginal('cancelled_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setCancelledAt($stringOrDateTime)
    {
        $this->data['cancelled_at'] = $stringOrDateTime instanceof DateTime || $stringOrDateTime instanceof \Carbon\Carbon
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getProcessedAt()
    {
        return is_null($date = $this->getOriginal('processed_at'))
            ? $date : new DateTime($date);
    }

    /**
     * @param DateTime|string
     * @return $this
     */
    public function setProcessedAt($stringOrDateTime)
    {
        $this->data['processed_at'] = $stringOrDateTime instanceof DateTime || $stringOrDateTime instanceof \Carbon\Carbon
            ? $stringOrDateTime->format('c') : $stringOrDateTime;

        return $this;
    }

    // ------------------------------------------------------------------------
    //                          SUPPORT FOR ORDER RISKS
    // ------------------------------------------------------------------------

    /**
     * Retrieve all risks for an order
     *
     * @link https://help.shopify.com/api/reference/order_risks#index
     *
     * @param array $params
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function risks(array $params = [])
    {
        return $this->api->risks($this->getId(), $params);
    }

    /**
     * @param $id
     * @param array $params
     */
    public function createRisk(array $params = [])
    {
        return $this->api->createRisk($this->getId(), $params);
    }

    /**
     * @param $risk_id
     * @param array $params
     */
    public function updateRisk($risk_id, array $params = [])
    {
        return $this->api->updateRisk($this->getId(), $risk_id, $params);
    }

    /**
     * @param $risk_id
     * @param array $params
     */
    public function showRisk($risk_id, array $params = [])
    {
        return $this->api->showRisk($this->getId(), $risk_id, $params);
    }

    /**
     * @param $risk_id
     * @param array $params
     */
    public function deleteRisk($risk_id)
    {
        return $this->api->deleteRisk($this->getId(), $risk_id);
    }

}
