<?php

namespace ShopifyApi\Models;

/**
 * Class Variant
 *
 * @method string getTitle()
 * @method string getProductId()
 * @method string getPrice()
 * @method string getSku()
 * @method string getPosition()
 * @method string getGrams()
 * @method string getInventoryPolicy()
 * @method string getCompareAtPrice()
 * @method string getFulfillmentService()
 * @method string getInventoryManagement()
 * @method string getOption1()
 * @method string getOption2()
 * @method string getOption3()
 * @method string getTaxable()
 * @method string getBarcode()
 * @method string getImageId()
 * @method string getInventoryQuantity()
 * @method string getWeight()
 * @method string getWeightUnit()
 * @method string getOldInventoryQuantity()
 * @method string getRequiresShipping()
 * @method string setTitle(string $title)
 * @method string setProductId(int $product_id)
 * @method string setPrice(float $price)
 * @method string setSku(string $sku)
 * @method string setPosition(int $position)
 * @method string setGrams(int $gram)
 * @method string setInventoryPolicy(string $inventory_policy)
 * @method string setOption1(string $option1)
 * @method string setOption2(string $option2)
 * @method string setOption3(string $option3)
 * @method string setTaxable(int $taxable)
 * @method string setBarcode(string $barcode)
 * @method string setImageId(int $image_id)
 * @method string setInventoryQuantity(int $inventory_quantity)
 * @method string setWeight(float $weight)
 * @method string setWeightUnit(string $weight_unit)
 * @method string setOldInventoryQuantity(int $old_inventory_quantity)
 * @method string setRequiresShipping(int $requires_shipping)
 * @method string hasTitle()
 * @method string hasProductId()
 * @method string hasPrice()
 * @method string hasSku()
 * @method string hasPosition()
 * @method string hasGrams()
 * @method string hasInventoryPolicy()
 * @method string hasCompareAtPrice()
 * @method string hasFulfillmentService()
 * @method string hasInventoryManagement()
 * @method string hasOption1()
 * @method string hasOption2()
 * @method string hasOption3()
 * @method string hasTaxable()
 * @method string hasBarcode()
 * @method string hasImageId()
 * @method string hasInventoryQuantity()
 * @method string hasWeight()
 * @method string hasWeightUnit()
 * @method string hasOldInventoryQuantity()
 * @method string hasRequiresShipping()
 */
class Variant extends AbstractModel
{

    /** @var string $api_name */
    protected static $api_name = 'variant';

    /** @var array $load_params */
    protected static $load_params = [];

}