<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Product Service. See NetLicensingAPI for details:
 * https://netlicensing.io/wiki/product-services
 *
 * @package NetLicensing
 */
class ProductService
{
    /**
     * Creates new product with given properties.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/product-services#create-product
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param Product $product
     *
     * return the newly created product object
     * @return Product|null
     * @throws RestException
     */
    public static function create(Context $context, Product $product): ?Product
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
     * https://netlicensing.io/wiki/product-services#get-product
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the product number
     * @param string $number
     *
     * return the product object
     * @return Product|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function get(Context $context, string $number): ?Product
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
     * https://netlicensing.io/wiki/product-services#products-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * array of product entities or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function getList(Context $context, string $filter = null): Page
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
     * https://netlicensing.io/wiki/product-services#update-product
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * product number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param Product $product
     *
     * updated product.
     * @return Product|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function update(Context $context, string $number, Product $product): ?Product
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
     * https://netlicensing.io/wiki/product-services#delete-product
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * product number
     * @param string $number
     *
     * if true, any entities that depend on the one being deleted will be deleted too
     * @param bool $forceCascade
     *
     * @throws RestException
     * @throws MalformedArgumentsException
     */
    public static function delete(Context $context, string $number, bool $forceCascade = false): void
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $queryParams[Constants::CASCADE] = ($forceCascade) ? 'true' : 'false';

        NetLicensingService::getInstance()
            ->delete($context, Constants::PRODUCT_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}
