<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Licensee Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services
 *
 * @package NetLicensing
 */
class LicenseeService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::LICENSEE_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'licensee';
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::LICENSEE_ENDPOINT_PATH_VALIDATE instead.
     */
    const ENDPOINT_PATH_VALIDATE = 'validate';
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::LICENSEE_ENDPOINT_PATH_TRANSFER instead.
     */
    const ENDPOINT_PATH_TRANSFER = 'transfer';

    /**
     * Creates new licensee object with given properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Createlicensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * parent product to which the new licensee is to be added
     * @param $productNumber
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param Licensee $licensee
     *
     * return the newly created licensee object
     * @return mixed|\NetLicensing\Licensee|null
     */
    public static function create(Context $context, $productNumber, Licensee $licensee)
    {
        CheckUtils::paramNotEmpty($productNumber, 'productNumber');

        $licensee->setProperty('productNumber', $productNumber);

        return NetLicensingService::getInstance()->post($context, Constants::LICENSEE_ENDPOINT_PATH, $licensee->asPropertiesMap(), $licensee);
    }

    /**
     * Gets licensee by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Getlicensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the licensee number
     * @param $number
     *
     * return the licensee
     * @return mixed|\NetLicensing\Licensee|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        return NetLicensingService::getInstance()->get($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number, [], Licensee::class);
    }

    /**
     * Returns all licensees of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Licenseeslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param null $filter
     *
     * array of licensees (of all products) or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, Constants::LICENSEE_ENDPOINT_PATH, $queryParams, Licensee::class);
    }

    /**
     * Updates licensee properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Updatelicensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * licensee number
     * @param $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param Licensee $licensee
     *
     * return updated licensee.
     * @return mixed|null
     */
    public static function update(Context $context, $number, Licensee $licensee)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        return NetLicensingService::getInstance()->post($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number, $licensee->asPropertiesMap(), $licensee);
    }

    /**
     * Deletes licensee.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Deletelicensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * licensee number
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

        $queryParams['forceCascade'] = ((bool)$forceCascade) ? 'true' : 'false';

        return NetLicensingService::getInstance()->delete($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }


    /**
     * Validates active licenses of the licensee. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Validatelicensee
     *
     * @param Context $context
     *
     * licensee number
     * @param string $number
     *
     * optional validation parameters. See ValidationParameters and licensing model documentation for
     * details.
     * @param ValidationParameters $validationParameters
     *
     * @return ValidationResults
     */
    public static function validate(Context $context, $number, ValidationParameters $validationParameters)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $queryParams = [];

        if ($validationParameters->getProductNumber()) {
            $queryParams['productNumber'] = $validationParameters->getProductNumber();
        }

        if ($validationParameters->getLicenseeName()) {
            $queryParams['licenseeName'] = $validationParameters->getLicenseeName();
        }

        if ($validationParameters->getLicenseeSecret()) {
            $queryParams['licenseeSecret'] = $validationParameters->getLicenseeSecret();
        }

        $pmIndex = 0;

        foreach ($validationParameters->getParameters() as $productModuleName => $parameters) {
            $queryParams['productModuleNumber' . $pmIndex] = $productModuleName;
            foreach ($parameters as $key => $value) {
                $queryParams[$key . $pmIndex] = $value;
            }
            $pmIndex++;
        }

        $data = NetLicensingService::getInstance()->post($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number . '/' . Constants::LICENSEE_ENDPOINT_PATH_VALIDATE, $queryParams);

        $validationResults = new ValidationResults();
        $validationResults->setProductModuleValidation($data['productModuleNumber'], $data);
        $validationResults->setTtl(strtotime((string)NetLicensingService::getInstance()->lastCurlInfo()->response['ttl']));

        return $validationResults;
    }

    /**
     * Transfer licenses between licensees.
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Transferlicensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the number of the licensee receiving licenses
     * @param $number
     *
     * the number of the licensee delivering licenses
     * @param $sourceLicenseeNumber
     *
     * @return void
     */
    public static function transfer(Context $context, $number, $sourceLicenseeNumber)
    {
        CheckUtils::paramNotEmpty($number, 'number');
        CheckUtils::paramNotEmpty($sourceLicenseeNumber, 'sourceLicenseeNumber');

        $queryParams['sourceLicenseeNumber'] = $sourceLicenseeNumber;

        NetLicensingService::getInstance()->post($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number . '/' . Constants::LICENSEE_ENDPOINT_PATH_TRANSFER, $queryParams);
    }
}