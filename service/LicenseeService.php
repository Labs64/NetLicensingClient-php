<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use ErrorException;

/**
 * PHP representation of the Licensee Service. See NetLicensingAPI for details:
 * https://netlicensing.io/wiki/licensee-services
 *
 * @package NetLicensing
 */
class LicenseeService
{
    /**
     * Creates new licensee object with given properties.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/licensee-services#create-licensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * parent product to which the new licensee is to be added
     * @param string $productNumber
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param Licensee $licensee
     *
     * return the newly created licensee object
     * @return Licensee|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function create(Context $context, string $productNumber, Licensee $licensee): ?Licensee
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
     * https://netlicensing.io/wiki/licensee-services#get-licensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the licensee number
     * @param string $number
     *
     * return the licensee
     * @return Licensee|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function get(Context $context, string $number): ?Licensee
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
     * https://netlicensing.io/wiki/licensee-services#licensees-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * array of licensees (of all products) or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function getList(Context $context, string $filter = null): Page
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
     * https://netlicensing.io/wiki/licensee-services#update-licensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * licensee number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param Licensee $licensee
     *
     * return updated licensee.
     * @return Licensee|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function update(Context $context, string $number, Licensee $licensee): ?Licensee
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
     * https://netlicensing.io/wiki/licensee-services#delete-licensee
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * licensee number
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

        $queryParams[Constants::CASCADE] = ((bool)$forceCascade) ? 'true' : 'false';

        NetLicensingService::getInstance()
            ->delete($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number, $queryParams);
    }


    /**
     * Validates active licenses of the licensee.
     * In the case of multiple product modules validation, required parameters indexes will be added automatically.
     * See NetLicensingAPI for details: https://netlicensing.io/wiki/licensee-services#validate-licensee
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
     * @param array $meta optional parameter, receiving messages returned within response <infos> section.
     *
     * @return ValidationResults|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function validate(Context $context, string $number, ValidationParameters $validationParameters, array &$meta = []): ?ValidationResults
    {
        return ValidationService::validate($context, $number, $validationParameters, $meta);
    }

    /**
     * Transfer licenses between licensees.
     * https://netlicensing.io/wiki/licensee-services#transfer-licenses
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the number of the licensee receiving licenses
     * @param string $number
     *
     * the number of the licensee delivering licenses
     * @param string $sourceLicenseeNumber
     *
     * @return void
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function transfer(Context $context, string $number, string $sourceLicenseeNumber): void
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);
        CheckUtils::paramNotEmpty($sourceLicenseeNumber, Constants::LICENSEE_SOURCE_LICENSEE_NUMBER);

        $queryParams[Constants::LICENSEE_SOURCE_LICENSEE_NUMBER] = $sourceLicenseeNumber;

        NetLicensingService::getInstance()
            ->post($context, Constants::LICENSEE_ENDPOINT_PATH . '/' . $number . '/' . Constants::LICENSEE_ENDPOINT_PATH_TRANSFER, $queryParams);
    }
}
