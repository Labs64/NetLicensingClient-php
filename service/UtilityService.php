<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Utility Service. See NetLicensingAPI for details:
 * https://netlicensing.io/wiki/utility-services
 * @package NetLicensing
 */
class UtilityService
{
    /**
     * Returns all license types. See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/utility-services#license-types-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * array of available license types or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function listLicenseTypes(Context $context): Page
    {
        $response = NetLicensingService::getInstance()
            ->get($context, Constants::UTILITY_ENDPOINT_PATH . '/' . Constants::UTILITY_ENDPOINT_PATH_LICENSE_TYPES);

        $licenseTypes = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $licenseTypes[] = ItemToArrayConverter::convert($item);
            }
        }

        return new Page($licenseTypes, $pageNumber, $itemsNumber, $totalPages, $totalItems);
    }

    /**
     * Returns all license models. See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/utility-services#licensing-models-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * array of available license models or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function listLicensingModels(Context $context): Page
    {
        $response = NetLicensingService::getInstance()
            ->get($context, Constants::UTILITY_ENDPOINT_PATH . '/' . Constants::UTILITY_ENDPOINT_PATH_LICENSING_MODELS);

        $licensingModels = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $licensingModels[] = ItemToArrayConverter::convert($item);
            }
        }

        return new Page($licensingModels, $pageNumber, $itemsNumber, $totalPages, $totalItems);
    }

    /**
     * Returns all countries.
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * @return Page
     * @throws RestException
     */
    public static function listCountries(Context $context, string $filter = null): Page
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::UTILITY_ENDPOINT_PATH . '/' . Constants::UTILITY_ENDPOINT_PATH_COUNTRIES, $queryParams);

        $countries = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $country = ItemToCountryConverter::convert($item);
                $country->exists = true;

                $countries[] = $country;
            }
        }

        return new Page($countries, $pageNumber, $itemsNumber, $totalPages, $totalItems);
    }
}
