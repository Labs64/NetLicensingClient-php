<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the ProductModule Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services
 *
 * @package NetLicensing
 */
class ProductModuleService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::PRODUCT_MODULE_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'productmodule';

    /**
     * Creates new product module object with given properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Createproductmodule
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * parent product to which the new product module is to be added
     * @param string $productNumber
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param ProductModule $productModule
     *
     * the newly created product module object
     * @return mixed|\NetLicensing\ProductModule|null
     */
    public static function create(Context $context, $productNumber, ProductModule $productModule)
    {
        CheckUtils::paramNotEmpty($productNumber, 'productNumber');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $productModule->setProperty('productNumber', $productNumber);

        return NetLicensingService::getInstance()->post($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH, $productModule->asPropertiesMap(), $productModule);
    }

    /**
     * Gets product module by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Getproductmodule
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * the product module number
     * @param string $number
     *
     * return the product module object
     * @return mixed|\NetLicensing\Product|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->get($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number, [], ProductModule::class);
    }

    /**
     * Returns all product modules of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Productmoduleslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string $filter
     *
     * array of product modules entities or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH, $queryParams, ProductModule::class);
    }

    /**
     * Updates product module properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Updateproductmodule
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * product module number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param \NetLicensing\ProductModule $productModule
     *
     * updated product module.
     * @return mixed|\NetLicensing\Product|null
     */
    public static function update(Context $context, $number, ProductModule $productModule)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number, $productModule->asPropertiesMap(), $productModule);
    }

    /**
     * Deletes product module.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Product+Module+Services#ProductModuleServices-Deleteproductmodule
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * product module number
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

        return NetLicensingService::getInstance()->delete($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}