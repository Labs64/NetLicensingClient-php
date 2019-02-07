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
     * @return ProductModule|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function create(Context $context, $productNumber, ProductModule $productModule)
    {
        CheckUtils::paramNotEmpty($productNumber, Constants::PRODUCT_NUMBER);

        $productModule->setProperty(Constants::PRODUCT_NUMBER, $productNumber);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH, $productModule->asPropertiesMap());

        $createdProductModule = null;

        if (!empty($response->items->item[0])) {
            $createdProductModule = ItemToProductModuleConverter::convert($response->items->item[0]);
            $createdProductModule->exists = true;
        }

        return $createdProductModule;
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
     * @return ProductModule|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number);

        $productModule = null;

        if (!empty($response->items->item[0])) {
            $productModule = ItemToProductModuleConverter::convert($response->items->item[0]);
            $productModule->exists = true;
        }

        return $productModule;
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
     * @return Page
     * @throws RestException
     * @throws \ErrorException
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH, $queryParams);

        $productModules = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $productModule = ItemToProductModuleConverter::convert($item);
                $productModule->exists = true;

                $productModules[] = $productModule;
            }
        }

        return new Page($productModules, $pageNumber, $itemsNumber, $totalPages, $totalItems);
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
     * @return ProductModule|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function update(Context $context, $number, ProductModule $productModule)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number, $productModule->asPropertiesMap());

        $updatedProductModule = null;

        if (!empty($response->items->item[0])) {
            $updatedProductModule = ItemToProductModuleConverter::convert($response->items->item[0]);
            $updatedProductModule->exists = true;
        }

        return $updatedProductModule;
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
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function delete(Context $context, $number, $forceCascade = false)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $queryParams[Constants::CASCADE] = ((bool)$forceCascade) ? 'true' : 'false';

        return NetLicensingService::getInstance()
            ->delete($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}