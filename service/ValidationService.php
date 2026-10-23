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
use DOMElement;
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
    static public function retrieveValidationFile(Context $context, string $number, ValidationParameters $validationParameters): mixed
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
     * @throws MalformedArgumentsException
     * @throws Exception
     */
    static public function validateOffline(Context $context, string $validationFile, array &$meta = []): ?ValidationResults
    {
        $validationDoc = self::loadValidationFile($validationFile);

        SignatureUtils::check($context, $validationDoc);
        return self::convertValidationResult(self::convertValidationDoc($validationDoc), $meta);
    }

    /**
     * @param string $validationFile
     * @return DOMDocument
     * @throws MalformedArgumentsException
     */
    static private function loadValidationFile(string $validationFile): DOMDocument
    {
        if (trim($validationFile) === '') {
            throw new MalformedArgumentsException('Validation file is empty');
        }

        $useInternalErrors = libxml_use_internal_errors(true);

        try {
            $validationDoc = new DOMDocument();
            $loaded = $validationDoc->loadXML($validationFile, LIBXML_NONET);
            $error = libxml_get_last_error();
            libxml_clear_errors();
        } finally {
            libxml_use_internal_errors($useInternalErrors);
        }

        if (!$loaded || !$validationDoc->documentElement) {
            throw new MalformedArgumentsException('Validation file is not a valid XML document'
                . ($error ? ': ' . trim($error->message) : ''));
        }

        return $validationDoc;
    }

    /**
     * Converts the XML validation file into the same structure as the JSON response of the online validation.
     *
     * @param DOMDocument $validationDoc
     * @return object
     */
    static private function convertValidationDoc(DOMDocument $validationDoc): object
    {
        $root = $validationDoc->documentElement;
        $result = (object)['infos' => (object)['info' => []], 'items' => (object)['item' => []]];

        if ($root->hasAttribute('ttl')) {
            $result->ttl = $root->getAttribute('ttl');
        }

        foreach (self::childElements($root, 'infos') as $infos) {
            foreach (self::childElements($infos, 'info') as $info) {
                $result->infos->info[] = (object)[
                    'id' => $info->getAttribute('id'),
                    'type' => $info->getAttribute('type'),
                    'value' => $info->textContent,
                ];
            }
        }

        foreach (self::childElements($root, 'items') as $items) {
            foreach (self::childElements($items, 'item') as $item) {
                $result->items->item[] = self::convertXmlItem($item, ['type' => $item->getAttribute('type')]);
            }
        }

        return $result;
    }

    static private function convertXmlItem(DOMElement $element, array $attributes): object
    {
        $item = (object)array_merge($attributes, ['property' => [], 'list' => []]);

        foreach (self::childElements($element, 'property') as $property) {
            $item->property[] = (object)['name' => $property->getAttribute('name'), 'value' => $property->textContent];
        }

        foreach (self::childElements($element, 'list') as $list) {
            $item->list[] = self::convertXmlItem($list, ['name' => $list->getAttribute('name')]);
        }

        return $item;
    }

    /**
     * @return DOMElement[]
     */
    static private function childElements(DOMElement $parent, string $localName): array
    {
        $elements = [];

        foreach ($parent->childNodes as $child) {
            if ($child instanceof DOMElement && $child->localName === $localName) {
                $elements[] = $child;
            }
        }

        return $elements;
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

        }

        if (!empty($validationFile->ttl)) {
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
