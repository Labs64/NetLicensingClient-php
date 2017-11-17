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
 * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services
 *
 * @package NetLicensing
 */
class LicenseTemplateService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::LICENSE_TEMPLATE_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'licensetemplate';

    /**
     * Creates new license template object with given properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Createlicensetemplate
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * parent product module to which the new license template is to be added
     * @param productModuleNumber
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param LicenseTemplate $licenseTemplate
     *
     * the newly created license template object
     * @return mixed|\NetLicensing\LicenseTemplate|null
     */
    public static function create(Context $context, $productModuleNumber, LicenseTemplate $licenseTemplate)
    {
        CheckUtils::paramNotEmpty($productModuleNumber, 'productModuleNumber');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $licenseTemplate->setProperty('productModuleNumber', $productModuleNumber);

        return NetLicensingService::getInstance()->post($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH, $licenseTemplate->asPropertiesMap(), $licenseTemplate);
    }

    /**
     * Gets license template by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Getlicensetemplate
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * the license template number
     * @param string $number
     *
     * return the license template object
     * @return mixed|\NetLicensing\Product|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->get($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH . '/' . $number, [], LicenseTemplate::class);
    }

    /**
     * Returns all license templates of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Licensetemplateslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string $filter
     *
     * array of license templates (of all products/modules) or null/empty list if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH, $queryParams, LicenseTemplate::class);
    }

    /**
     * Updates license template properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Updatelicensetemplate
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * license template number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param \NetLicensing\LicenseTemplate $licenseTemplate
     *
     * updated license template.
     * @return mixed|\NetLicensing\Product|null
     */
    public static function update(Context $context, $number, LicenseTemplate $licenseTemplate)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH . '/' . $number, $licenseTemplate->asPropertiesMap(), $licenseTemplate);
    }

    /**
     * Deletes license template.See NetLicensingAPI JavaDoc for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Deletelicensetemplate
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * license template number
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

        return NetLicensingService::getInstance()->delete($context, Constants::LICENSE_TEMPLATE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}