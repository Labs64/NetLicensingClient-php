<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the Product Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Product+Services
 *
 * @package NetLicensing
 */
class ProductService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::PRODUCT_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'product';

    /**
     * Creates new product with given properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Createproduct
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param \NetLicensing\Product $product
     *
     * return the newly created product object
     * @return Product
     */
    public static function create(Context $context, Product $product)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, Constants::PRODUCT_ENDPOINT_PATH, $product->asPropertiesMap(), $product);
    }

    /**
     * Gets product by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Getproduct
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * the product number
     * @param string $number
     *
     * return the product object
     * @return mixed|\NetLicensing\Product|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->get($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number, [], Product::class);
    }

    /**
     * Returns products of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Productslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string $filter
     *
     * array of product entities or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, Constants::PRODUCT_ENDPOINT_PATH, $queryParams, Product::class);
    }

    /**
     * Updates product properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Updateproduct
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * product number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param \NetLicensing\Product $product
     *
     * updated product.
     * @return mixed|\NetLicensing\Product|null
     */
    public static function update(Context $context, $number, Product $product)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number, $product->asPropertiesMap(), $product);
    }

    /**
     * Deletes product.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Services#ProductServices-Deleteproduct
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * product number
     * @param string $number
     *
     * if true, any entities that depend on the one being deleted will be deleted too
     * @param bool $forceCascade
     *
     * @return bool
     */
    public static function delete(Context $context, $number, $forceCascade = false)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams['forceCascade'] = ((bool)$forceCascade) ? 'true' : 'false';

        return NetLicensingService::getInstance()->delete($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}