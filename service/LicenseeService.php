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
     * @return Licensee|null
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function create(Context $context, $productNumber, Licensee $licensee)
    {
        CheckUtils::paramNotEmpty($productNumber, Constants::PRODUCT_NUMBER);

        $licensee->setProperty(Constants::PRODUCT_NUMBER, $productNumber);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::LICENSEE_ENDPOINT_PATH, $licensee->asPropertiesMap());

        $createdLicensee = null;

        if (!empty($response->items->item[0])) {
            $createdLicensee = ItemToLicenseeConverter::convert($response->items->item[0]);
            $createdLicensee->exists = true;
        }

        return $createdLicensee;
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
     * @return Licensee|null
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number);

        $licensee = null;

        if (!empty($response->items->item[0])) {
            $licensee = ItemToLicenseeConverter::convert($response->items->item[0]);
            $licensee->exists = true;
        }

        return $licensee;
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
     * @return Page
     * @throws \ErrorException
     * @throws RestException
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::LICENSEE_ENDPOINT_PATH, $queryParams);

        $licensees = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $licensee = ItemToLicenseeConverter::convert($item);
                $licensee->exists = true;

                $licensees[] = $licensee;
            }
        }

        return new Page($licensees, $pageNumber, $itemsNumber, $totalPages, $totalItems);
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
     * @return Licensee|null
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function update(Context $context, $number, Licensee $licensee)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number, $licensee->asPropertiesMap());

        $updatedLicensee = null;

        if (!empty($response->items->item[0])) {
            $updatedLicensee = ItemToLicenseeConverter::convert($response->items->item[0]);
            $updatedLicensee->exists = true;
        }

        return $updatedLicensee;
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
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function delete(Context $context, $number, $forceCascade = false)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $queryParams[Constants::CASCADE] = ((bool)$forceCascade) ? 'true' : 'false';

        return NetLicensingService::getInstance()
            ->delete($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number, $queryParams);
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
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws \ErrorException
     */

    public static function validate(Context $context, $number, ValidationParameters $validationParameters)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $queryParams = [];

        if ($validationParameters->getProductNumber()) {
            $queryParams[Constants::PRODUCT_NUMBER] = $validationParameters->getProductNumber();
        }

        if ($validationParameters->getLicenseeName()) {
            $queryParams[Constants::LICENSEE_PROP_LICENSEE_NAME] = $validationParameters->getLicenseeName();
        }

        if ($validationParameters->getLicenseeSecret()) {
            $queryParams[Constants::LICENSEE_PROP_LICENSEE_SECRET] = $validationParameters->getLicenseeSecret();
        }

        $pmIndex = 0;

        foreach ($validationParameters->getParameters() as $productModuleName => $parameters) {
            $queryParams[Constants::PRODUCT_MODULE_NUMBER . $pmIndex] = $productModuleName;
            foreach ($parameters as $key => $value) {
                $queryParams[$key . $pmIndex] = $value;
            }
            $pmIndex++;
        }

        $urlTemplate = Constants::LICENSEE_ENDPOINT_PATH . '/' . $number . '/' . Constants::LICENSEE_ENDPOINT_PATH_VALIDATE;

        $response = NetLicensingService::getInstance()->post($context, $urlTemplate, $queryParams);

        $validationResults = new ValidationResults();

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $array = ItemToArrayConverter::convert($item);
                $validationResults->setProductModuleValidation($array[Constants::PRODUCT_MODULE_NUMBER], $array);
            }

            $validationResults->setTtl(new \DateTime($response->ttl));
        }

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
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function transfer(Context $context, $number, $sourceLicenseeNumber)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);
        CheckUtils::paramNotEmpty($sourceLicenseeNumber, Constants::LICENSEE_SOURCE_LICENSEE_NUMBER);

        $queryParams[Constants::LICENSEE_SOURCE_LICENSEE_NUMBER] = $sourceLicenseeNumber;

        NetLicensingService::getInstance()
            ->post($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number . '/' . Constants::LICENSEE_ENDPOINT_PATH_TRANSFER, $queryParams);
    }
}