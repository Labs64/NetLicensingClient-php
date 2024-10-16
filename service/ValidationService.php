<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use DateTime;
use DOMDocument;
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
     * @throws MalformedArgumentsException
     * @throws RestException
     * @throws Exception
     */
    static public function validate(Context $context, string $number, ValidationParameters $validationParameters, array &$meta = []): ?ValidationResults
    {
        return self::convertValidationResult(self::retrieveValidationFile($context, $number, $validationParameters), $meta);
    }

    /**
     * Retrieves validation file for the given licensee from the server as response string. The response can be
     * stored locally for subsequent validation by method {@link validateOffline}, that doesn't require connection to
     * the server.
     *
     * @param Context $context determines the vendor on whose behalf the call is performed
     *
     * @param string $number licensee number
     *
     * @param ValidationParameters $validationParameters optional validation parameters. See ValidationParameters and
     * licensing model documentation for details.
     *
     * @return array|mixed|null validation (response), possibly signed, for subsequent use in {@link validateOffline}
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    static public function retrieveValidationFile(Context $context, string $number, ValidationParameters $validationParameters)
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
     * @throws Exception
     */
    static public function validateOffline(Context $context, string $validationFile, array &$meta = []): ?ValidationResults
    {
        $validationDoc = new DOMDocument();
        $validationDoc->loadXML($validationFile);

        SignatureUtils::check($context, $validationDoc);
        return self::convertValidationResult($validationDoc);
    }

    /**
     * @param ValidationParameters $validationParameters
     * @return array
     */
    static private function convertValidationParameters(ValidationParameters $validationParameters): array
    {
        $queryParams = [];

        if ($validationParameters->getProductNumber()) {
            $queryParams[Constants::PRODUCT_NUMBER] = $validationParameters->getProductNumber();
        }

        foreach ($validationParameters->getLicenseeProperties() as $key => $value) {
            $queryParams[$key] = $value;
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
    static private function convertValidationResult($validationFile, array &$meta = []): ?ValidationResults
    {
        $validationResults = new ValidationResults();

        if (!empty($validationFile->items->item)) {
            foreach ($validationFile->items->item as $item) {
                $array = ItemToArrayConverter::convert($item);
                $validationResults->setProductModuleValidation($array[Constants::PRODUCT_MODULE_NUMBER], $array);
            }

            $validationResults->setTtl(new DateTime($validationFile->ttl));
        }

        if (!empty($validationFile->infos->info)) {
            foreach ($validationFile->infos->info as $info) {
                $meta[] = $info;
            }
        }

        return $validationResults;
    }
}
