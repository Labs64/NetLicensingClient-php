<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the License Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/License+Services
 *
 * @package NetLicensing
 */
class LicenseService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::LICENSE_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'license';

    /**
     * Creates new license object with given properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Createlicense
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * parent licensee to which the new license is to be added
     * @param $licenseeNumber
     *
     *  license template that the license is created from
     * @param $licenseTemplateNumber
     *
     * For privileged logins specifies transaction for the license creation. For regular logins new
     * transaction always created implicitly, and the operation will be in a separate transaction.
     * Transaction is generated with the provided transactionNumber, or, if transactionNumber is null, with
     * auto-generated number.
     * @param null|string $transactionNumber
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param License $license
     *
     * return the newly created license object
     * @return mixed|\NetLicensing\License|null
     */
    public static function create(Context $context, $licenseeNumber, $licenseTemplateNumber, $transactionNumber = null, License $license)
    {
        CheckUtils::paramNotEmpty($licenseeNumber, 'licenseeNumber');
        CheckUtils::paramNotEmpty($licenseTemplateNumber, 'licenseTemplateNumber');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $license->setProperty('licenseeNumber', $licenseeNumber);
        $license->setProperty('licenseTemplateNumber', $licenseTemplateNumber);

        if ($transactionNumber) $license->setProperty('transactionNumber', $transactionNumber);

        return NetLicensingService::getInstance()->post($context, Constants::LICENSE_ENDPOINT_PATH, $license->asPropertiesMap(), $license);
    }

    /**
     * Gets license by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Getlicense
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the license number
     * @param $number
     *
     * return the license
     * @return mixed|\NetLicensing\License|null
     */
    public static function get(Context $context, $number)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        CheckUtils::paramNotEmpty($number, 'number');

        return NetLicensingService::getInstance()->get($context, Constants::LICENSE_ENDPOINT_PATH . '/' . $number, [], License::class);
    }

    /**
     * Returns licenses of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Licenseslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param null $filter
     *
     * return array of licenses (of all products) or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, Constants::LICENSE_ENDPOINT_PATH, $queryParams, License::class);
    }

    /**
     * Updates license properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Updatelicense
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * license number
     * @param $number
     *
     * transaction for the license update. Created implicitly if transactionNumber is null. In this case the
     * operation will be in a separate transaction.
     * @param null $transactionNumber
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param License $license
     *
     * return updated license.
     * @return mixed|\NetLicensing\License|null
     */
    public static function update(Context $context, $number, $transactionNumber = null, License $license)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        if ($transactionNumber) $license->setProperty('transactionNumber', $transactionNumber);

        return NetLicensingService::getInstance()->post($context, Constants::LICENSE_ENDPOINT_PATH . '/' . $number, $license->asPropertiesMap(), $license);
    }

    /**
     * Deletes license.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Deletelicense
     *
     * When any license is deleted, corresponding transaction is created automatically.
     *
     *  determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * license number
     * @param $number
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

        return NetLicensingService::getInstance()->delete($context, Constants::LICENSE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}