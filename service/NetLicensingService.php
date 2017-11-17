<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use Curl\Curl;

class NetLicensingService
{
    private static $_instance = null;

    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::XML_NS instead.
     */
    const XML_NS = 'http://netlicensing.labs64.com/schema/context';

    /**
     * @var $curl Curl
     */
    private $curl;

    private function __construct()
    {
        $this->curl = new NetLicensingCurl();
        $this->curl->setHeader('Accept', 'application/xml');
        $this->curl->setUserAgent('NetLicensing/PHP ' . PHP_VERSION . ' (http://netlicensing.io)' . '; ' . $_SERVER['HTTP_USER_AGENT']);
    }

    protected function __clone()
    {
    }

    static public function getInstance()
    {
        if (is_null(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }

    public function lastCurlInfo()
    {
        return (object)$this->curl->toArray();
    }

    /**
     * Helper method for performing GET request to NetLicensing API services. Finds and returns first suitable item with
     * type resultType from the response.
     *
     * Context for the NetLicensing API call
     * @param Context $context
     *
     * the REST URL template
     * @param $urlTemplate
     *
     * The REST query parameters values. May be null if there are no parameters.
     * @param array $queryParams
     *
     * the type of the result
     * @param $resultType
     *
     * @return mixed|null
     * @throws NetLicensingException
     */

    public function get(Context $context, $urlTemplate, array $queryParams = [], $resultType = null)
    {
        $response = $this->request($context, 'get', $urlTemplate, $queryParams);

        if (!$response) return null;

        return $this->getEntity($resultType, reset($response));
    }

    /**
     * Helper method for performing GET request to NetLicensing API service that returns page of items with type
     * resultType.
     *
     * context for the NetLicensing API call
     * @param Context $context
     *
     *  the REST URL template
     * @param $urlTemplate
     *
     * The REST query parameters values. May be null if there are no parameters.
     * @param array $queryParams
     *
     * the type of the result
     * @param $resultType
     *
     * @return array
     */
    public function getList(Context $context, $urlTemplate, array $queryParams = [], $resultType = null)
    {
        $response = $this->request($context, 'get', $urlTemplate, $queryParams);

        if (!$response) return [];

        $list = [];

        foreach ($response as $key => $value) {
            $list[] = $this->getEntity($resultType, $value);
        }

        return $list;
    }

    /**
     * Helper method for performing POST request to NetLicensing API services. Finds and returns first suitable item
     * with type resultType from the response.
     *
     * context for the NetLicensing API call
     * @param Context $context
     *
     * the REST URL template
     * @param $urlTemplate
     *
     * The REST query parameters values. May be null if there are no parameters.
     * @param array $queryParams
     *
     * the type of the result
     * @param $resultType
     * @return mixed|null
     */
    public function post(Context $context, $urlTemplate, array $queryParams = [], $resultType = null)
    {
        $response = $this->request($context, 'post', $urlTemplate, $queryParams);

        if (!$response) return null;

        return $this->getEntity($resultType, reset($response));
    }

    /**
     * Helper method for performing DELETE request to NetLicensing API services.
     *
     * context for the NetLicensing API call
     * @param Context $context
     *
     * the REST URL template
     * @param $urlTemplate
     *
     * The REST query parameters values. May be null if there are no parameters.
     * @param array $queryParams
     *
     * @return bool
     */
    public function delete(Context $context, $urlTemplate, array $queryParams = [])
    {
        $this->request($context, 'delete', $urlTemplate, $queryParams);

        return ($this->getStatusCode() == 204);
    }

    public function request(Context $context, $method, $urlTemplate, array $queryParams = [])
    {
        $restUrl = $context->getBaseUrl() . preg_replace('#/+#', '/', '/' . $urlTemplate);

        //validate http method
        $this->validateMethod($method);

        //validate context
        $this->validateBaseUrl($context);

        //validate baseUrl +  urlTemplate
        $this->validateRestUrl($restUrl);

        //configure
        $this->configure($context);

        $response = $this->curl->{$method}($restUrl, $queryParams);

        switch ($this->getStatusCode()) {
            case 200:
                return $this->xmlToArray($response);
                break;
            case 204:
                return null;
                break;
            default:
                throw new RestException(sprintf("Unsupported response status code %s: %s",
                    $this->getStatusCode(), $this->getReasonPhrase($response)));
                break;

        }

    }

    protected function validateMethod($method)
    {
        if (!in_array(strtolower($method), ['get', 'post', 'delete'])) {
            throw new RestException('Invalid request type:' . $method . ', allowed requests types: GET, POST, DELETE.');
        }
    }

    protected function validateBaseUrl(Context $context)
    {

        if (!$context->getBaseUrl()) {
            throw new RestException('Base url must be specified');
        }

        if (filter_var($context->getBaseUrl(), FILTER_VALIDATE_URL) === false) {
            throw new RestException('Base url "' . $context->getBaseUrl() . '" is not a valid URL');
        }
    }

    protected function validateRestUrl($restUrl)
    {
        if (filter_var($restUrl, FILTER_VALIDATE_URL) === false) {
            throw new RestException('Rest url"' . $restUrl . '" is not a valid URL');
        }
    }

    private function configure(Context $context)
    {
        if (!$context->getSecurityMode()) {
            throw new RestException('Security mode must be specified');
        }

        switch ($context->getSecurityMode()) {
            case Context::BASIC_AUTHENTICATION:
                if (!$context->getUsername()) throw new RestException('Missing parameter "username"');
                if (!$context->getPassword()) throw new RestException('Missing parameter "password"');

                $this->curl->setHeader('Authorization', 'Basic ' . base64_encode($context->getUsername() . ":" . $context->getPassword()));
                break;
            case Context::APIKEY_IDENTIFICATION:
                if (!$context->getApiKey()) throw new RestException('Missing parameter "apiKey"');

                $this->curl->setHeader('Authorization', 'Basic ' . base64_encode("apiKey:" . $context->getApiKey()));
                break;
            default:
                throw new RestException("Unknown security mode");
                break;
        }
    }

    private function getStatusCode()
    {
        return $this->curl->httpStatusCode;
    }

    private function getReasonPhrase($response)
    {
        $reason = '';
        if (is_string($response)) {
            $response = simplexml_load_string($response);
        }

        if ($response !== false) {
            $children = $response->children(Constants::XML_NS);
            $reason = (string)$children->infos->info;
        }

        if (!$reason) {
            $reason = $this->curl->errorMessage;
        }

        return $reason;
    }

    public static function xmlToArray($response)
    {
        $data = [];

        if (is_string($response)) {
            $response = simplexml_load_string($response);
        }

        if ($response instanceof \SimpleXMLElement) {
            $response->registerXPathNamespace('nl', Constants::XML_NS);
            $items = $response->xpath('//nl:item');

            if ($items) {
                foreach ($items as $item) {

                    $properties = $item->children(Constants::XML_NS);
                    $tmp_array = array();

                    if ($properties) {
                        self::getXmlProperties($properties, $tmp_array);
                        $data[] = $tmp_array;
                    }
                }
            }
        }

        return $data;
    }

    private static function getXmlProperties($properties, &$array, $index = 0)
    {
        /** @var  $property \SimpleXMLElement */
        foreach ($properties as $property) {

            $attributes = $property->attributes();

            $name = (string)$attributes['name'];

            if ($property->count()) {
                $array[$name][$index] = [];
                self::getXmlProperties($property, $array[$name][$index], $index++);
                continue;
            }

            $value = (string)$property;
            $array[$name] = $value;
        }
    }

    private function getEntity($resultType, $properties)
    {
        if (is_null($resultType)) return $properties;

        $entity = ($resultType instanceof BaseEntity) ? $resultType : new $resultType();

        if (!$entity instanceof BaseEntity) {
            throw new NetLicensingException('Invalid entity ' . $resultType . ', entity must be instanceof BaseEntity');
        }

        $entity->setProperties($properties, true);
        $entity->exists = true;

        return $entity;
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}