<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Utility Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Utility+Services
 * @package NetLicensing
 */
class UtilityService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::UTILITY_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'utility';

    /**
     * Returns all license types. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Utility+Services#UtilityServices-LicenseTypeslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * array of available license types or empty array if nothing found.
     * @return array
     */
    public static function listLicenseTypes(Context $context)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->getList($context, Constants::UTILITY_ENDPOINT_PATH . '/licenseTypes');
    }

    /**
     * Returns all license models. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Utility+Services#UtilityServices-LicensingModelslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * array of available license models or empty array if nothing found.
     * @return array
     */
    public static function listLicensingModels(Context $context)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->getList($context, Constants::UTILITY_ENDPOINT_PATH . '/licensingModels');
    }
}