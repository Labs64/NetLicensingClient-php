<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the ProductModule Service. See NetLicensingAPI for details:
 * https://netlicensing.io/wiki/product-module-services
 *
 * @package NetLicensing
 */
class ProductModuleService
{
    /**
     * Creates new product module object with given properties.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/product-module-services#create-product-module
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
     */
    public static function create(Context $context, string $productNumber, ProductModule $productModule): ?ProductModule
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
     * https://netlicensing.io/wiki/product-module-services#get-product-module
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the product module number
     * @param string $number
     *
     * return the product module object
     * @return ProductModule|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function get(Context $context, string $number): ?ProductModule
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
     * https://netlicensing.io/wiki/product-module-services#product-modules-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * array of product modules entities or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function getList(Context $context, string $filter = null): Page
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
     * https://netlicensing.io/wiki/product-module-services#update-product-module
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * product module number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param ProductModule $productModule
     *
     * updated product module.
     * @return ProductModule|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function update(Context $context, string $number, ProductModule $productModule): ?ProductModule
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
     * https://netlicensing.io/wiki/product-module-services#delete-product-module
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * product module number
     * @param string $number
     *
     * if true, any entities that depend on the one being deleted will be deleted too
     * @param bool $forceCascade
     *
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function delete(Context $context, string $number, bool $forceCascade = false): void
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $queryParams[Constants::CASCADE] = ((bool)$forceCascade) ? 'true' : 'false';

        NetLicensingService::getInstance()
            ->delete($context, Constants::PRODUCT_MODULE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}
