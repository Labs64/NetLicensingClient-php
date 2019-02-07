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
     * @return License|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function create(Context $context, $licenseeNumber, $licenseTemplateNumber, $transactionNumber = null, License $license)
    {
        CheckUtils::paramNotEmpty($licenseeNumber, Constants::LICENSEE_NUMBER);
        CheckUtils::paramNotEmpty($licenseTemplateNumber, Constants::LICENSE_TEMPLATE_NUMBER);

        $license->setProperty(Constants::LICENSEE_NUMBER, $licenseeNumber);
        $license->setProperty(Constants::LICENSE_TEMPLATE_NUMBER, $licenseTemplateNumber);

        if ($transactionNumber) $license->setProperty(Constants::TRANSACTION_NUMBER, $transactionNumber);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::LICENSE_ENDPOINT_PATH, $license->asPropertiesMap());

        $createdLicense = null;

        if (!empty($response->items->item[0])) {
            $createdLicense = ItemToLicenseConverter::convert($response->items->item[0]);
            $createdLicense->exists = true;
        }

        return $createdLicense;
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
     * @return License|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::LICENSE_ENDPOINT_PATH . '/' . $number);

        $license = null;

        if (!empty($response->items->item[0])) {
            $license = ItemToLicenseConverter::convert($response->items->item[0]);
            $license->exists = true;
        }

        return $license;
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
     * @return Page
     * @throws \ErrorException
     * @throws RestException
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::LICENSE_ENDPOINT_PATH, $queryParams);

        $licenses = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $license = ItemToLicenseConverter::convert($item);
                $license->exists = true;

                $licenses[] = $license;
            }
        }

        return new Page($licenses, $pageNumber, $itemsNumber, $totalPages, $totalItems);
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
     * @return License|null
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function update(Context $context, $number, $transactionNumber = null, License $license)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        if ($transactionNumber) $license->setProperty(Constants::TRANSACTION_NUMBER, $transactionNumber);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::LICENSE_ENDPOINT_PATH . '/' . $number, $license->asPropertiesMap());

        $updatedLicense = null;

        if (!empty($response->items->item[0])) {
            $updatedLicense = ItemToLicenseConverter::convert($response->items->item[0]);
            $updatedLicense->exists = true;
        }

        return $updatedLicense;
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
     * @return bool
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */
    public static function delete(Context $context, $number, $forceCascade = false)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $queryParams[Constants::CASCADE] = ((bool)$forceCascade) ? 'true' : 'false';

        return NetLicensingService::getInstance()
            ->delete($context, Constants::LICENSE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }
}