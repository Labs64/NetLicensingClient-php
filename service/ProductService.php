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
     * @return Product|null
     * @throws RestException
     * @throws \ErrorException
     */
    public static function create(Context $context, Product $product)
    {
        $response = NetLicensingService::getInstance()
            ->post($context, Constants::PRODUCT_ENDPOINT_PATH, $product->asPropertiesMap());

        $createdProduct = null;

        if (!empty($response->items->item[0])) {
            $createdProduct = ItemToProductConverter::convert($response->items->item[0]);
            $createdProduct->exists = true;
        }

        return $createdProduct;
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
     * @return Product|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number);

        $product = null;

        if (!empty($response->items->item[0])) {
            $product = ItemToProductConverter::convert($response->items->item[0]);
            $product->exists = true;
        }

        return $product;
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
     * @return Page
     * @throws RestException
     * @throws \ErrorException
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::PRODUCT_ENDPOINT_PATH, $queryParams);

        $products = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $product = ItemToProductConverter::convert($item);
                $product->exists = true;

                $products[] = $product;
            }
        }

        return new Page($products, $pageNumber, $itemsNumber, $totalPages, $totalItems);
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
     * @return Product|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function update(Context $context, $number, Product $product)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number, $product->asPropertiesMap());

        $updatedProduct = null;

        if (!empty($response->items->item[0])) {
            $updatedProduct = ItemToProductConverter::convert($response->items->item[0]);
            $updatedProduct->exists = true;
        }

        return $updatedProduct;
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
     * @throws RestException
     * @throws \ErrorException
     * @throws MalformedArgumentsException
     */
    public static function delete(Context $context, $number, $forceCascade = false)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $queryParams[Constants::CASCADE] = ((bool)$forceCascade) ? 'true' : 'false';

        return NetLicensingService::getInstance()
            ->delete($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}