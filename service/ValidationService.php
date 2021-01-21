<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use DateTime;
use ErrorException;
use Exception;

class ValidationService
{
    /**
     * Validates active licenses of the licensee.
     *
     * @param Context $context determines the vendor on whose behalf the call is performed
     *
     * @param string $number licensee number
     *
     * @param ValidationParameters $validationParameters optional validation parameters. See ValidationParameters and
     * licensing model documentation for details.
     *
     * @param array $meta optional parameter, receiving messages returned within response <infos> section.
     *
     * @return ValidationResults|null result of the validation
     * @throws ErrorException
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws Exception
     */
    static public function validate(Context $context, $number, ValidationParameters $validationParameters, array &$meta = [])
    {
        return self::convertValidationResult(self::retrieveValidationFile($context, $number, $validationParameters));
    }

    /**
     * Retrieves validation file for the given licensee from the server as response string. The response can be
     * stored locally for subsequent validation by method {@link validateOffline}, that doesn't require connection to
     * the server.
     *
     * @param Context $context determines the vendor on whose behalf the call is performed
     *
     * @param $number licensee number
     *
     * @param ValidationParameters $validationParameters optional validation parameters. See ValidationParameters and
     * licensing model documentation for details.
     *
     * @return array|mixed|null validation (response), possibly signed, for subsequent use in {@link validateOffline}
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws ErrorException
     */
    static public function retrieveValidationFile(Context $context, $number, ValidationParameters $validationParameters)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $urlTemplate = Constants::LICENSEE_ENDPOINT_PATH . '/' . $number . '/' . Constants::LICENSEE_ENDPOINT_PATH_VALIDATE;
        $queryParams = self::convertValidationParameters($validationParameters);

        return NetLicensingService::getInstance()->post($context, $urlTemplate, $queryParams);
    }

    /**
     * Perform validation without connecting to the server (offline) using validation file previously retrieved by
     * {@link retrieveValidationFile}.
     *
     * @param Context $context determines the vendor on whose behalf the call is performed
     *
     * @param $validationFile string  validation file(response) returned by {@link retrieveValidationFile} call
     *
     * @param array $meta optional parameter, receiving messages returned within response <infos> section.
     *
     * @return ValidationResults|null result of the validation
     * @throws BadSignatureException
     */
    static public function validateOffline(Context $context, $validationFile, array &$meta = [])
    {
        SignatureUtils::check($context, $validationFile);
        return self::convertValidationResult($validationFile);
    }

    /**
     * @param ValidationParameters $validationParameters
     * @return array
     */
    static private function convertValidationParameters(ValidationParameters $validationParameters)
    {
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

        return $queryParams;
    }

    /**
     * @param $validationFile
     * @param array $meta
     * @return ValidationResults|null
     * @throws Exception
     */
    static private function convertValidationResult($validationFile, array &$meta = [])
    {
        if (is_null($validationFile)) {
            return null;
        }

        $validationResults = new ValidationResults();

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $array = ItemToArrayConverter::convert($item);
                $validationResults->setProductModuleValidation($array[Constants::PRODUCT_MODULE_NUMBER], $array);
            }

            $validationResults->setTtl(new DateTime($response->ttl));
        }

        if (!empty($response->infos->infos)) {
            foreach ($response->infos->infos as $info) {
                // TODO(RVA): just do it
                print_r($info);
            }
        }

        return $validationResults;
    }
}