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
 * https://netlicensing.io/wiki/license-template-services
 *
 * @package NetLicensing
 */
class LicenseTemplateService
{
    /**
     * Creates new license template object with given properties.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/license-template-services#create-license-template
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * parent product module to which the new license template is to be added
     * @param string $productModuleNumber
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param LicenseTemplate $licenseTemplate
     *
     * the newly created license template object
     * @return LicenseTemplate|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function create(Context $context, string $productModuleNumber, LicenseTemplate $licenseTemplate): ?LicenseTemplate
    {
        CheckUtils::paramNotEmpty($productModuleNumber, Constants::PRODUCT_MODULE_NUMBER);

        $licenseTemplate->setProperty(Constants::PRODUCT_MODULE_NUMBER, $productModuleNumber);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH, $licenseTemplate->asPropertiesMap());

        $createdLicenseTemplate = null;

        if (!empty($response->items->item[0])) {
            $createdLicenseTemplate = ItemToLicenseTemplateConverter::convert($response->items->item[0]);
            $createdLicenseTemplate->exists = true;
        }

        return $createdLicenseTemplate;
    }

    /**
     * Gets license template by its number.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/license-template-services#get-license-template
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the license template number
     * @param string $number
     *
     * return the license template object
     * @return LicenseTemplate|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function get(Context $context, string $number): ?LicenseTemplate
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH . '/' . $number);

        $licenseTemplate = null;

        if (!empty($response->items->item[0])) {
            $licenseTemplate = ItemToLicenseTemplateConverter::convert($response->items->item[0]);
            $licenseTemplate->exists = true;
        }

        return $licenseTemplate;
    }

    /**
     * Returns all license templates of a vendor.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/license-template-services#license-templates-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * array of license templates (of all products/modules) or null/empty list if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function getList(Context $context, string $filter = null): Page
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH, $queryParams);

        $licenseTemplates = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $licenseTemplate = ItemToLicenseTemplateConverter::convert($item);
                $licenseTemplate->exists = true;

                $licenseTemplates[] = $licenseTemplate;
            }
        }

        return new Page($licenseTemplates, $pageNumber, $itemsNumber, $totalPages, $totalItems);
    }

    /**
     * Updates license template properties.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/license-template-services#update-license-template
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * license template number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param LicenseTemplate $licenseTemplate
     *
     * updated license template.
     * @return LicenseTemplate|null
     * @throws RestException
     * @throws MalformedArgumentsException
     */
    public static function update(Context $context, string $number, LicenseTemplate $licenseTemplate): ?LicenseTemplate
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH . '/' . $number, $licenseTemplate->asPropertiesMap());

        $updatedLicenseTemplate = null;

        if (!empty($response->items->item[0])) {
            $updatedLicenseTemplate = ItemToLicenseTemplateConverter::convert($response->items->item[0]);
            $updatedLicenseTemplate->exists = true;
        }

        return $updatedLicenseTemplate;
    }

    /**
     * Deletes license template.See NetLicensingAPI JavaDoc for details:
     * https://netlicensing.io/wiki/license-template-services#delete-license-template
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * license template number
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

        $queryParams[Constants::CASCADE] = ($forceCascade) ? 'true' : 'false';

        NetLicensingService::getInstance()
            ->delete($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}
