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
     * @var $curl Curl
     */
    private $curl;

    /**
     * NetLicensingService constructor.
     * @throws \ErrorException
     */
    private function __construct()
    {
        $this->curl = new NetLicensingCurl();
        $this->curl->setHeader('Accept', 'application/json');
        $this->curl->setUserAgent('NetLicensing/PHP ' . Constants::NETLICENSING_VERSION .'/' . PHP_VERSION. ' (https://netlicensing.io)');
    }

    protected function __clone()
    {
    }

    /**
     * @return NetLicensingService|null
     * @throws \ErrorException
     */
    static public function getInstance()
    {
        if (is_null(self::$_instance)) self::$_instance = new self();
        return self::$_instance;
    }

    /**
     * Return curl object
     *
     * @return Curl|NetLicensingCurl
     */
    public function curl()
    {
        return $this->curl;
    }

    /**
     * Return curl info
     *
     * @return object
     */
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
     * @return mixed|null
     * @throws RestException
     */

    public function get(Context $context, $urlTemplate, array $queryParams = [])
    {
        return $this->request($context, 'get', $urlTemplate, $queryParams);
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
     * @return mixed|null
     * @throws RestException
     */
    public function post(Context $context, $urlTemplate, array $queryParams = [])
    {
        return $this->request($context, 'post', $urlTemplate, $queryParams);
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
     * @return mixed|null
     * @throws RestException
     */
    public function delete(Context $context, $urlTemplate, array $queryParams = [])
    {
        return $this->request($context, 'delete', $urlTemplate, $queryParams);
    }

    /**
     * @param Context $context
     * @param $method
     * @param $urlTemplate
     * @param array $queryParams
     * @return array|null
     * @throws RestException
     */
    public function request(Context $context, $method, $urlTemplate, array $queryParams = [])
    {
        $restUrl = $context->getBaseUrl() . preg_replace('#/+#', '/', '/' . $urlTemplate);

        // validate http method
        $this->validateMethod($method);

        // validate context
        $this->validateBaseUrl($context);

        // validate baseUrl +  urlTemplate
        $this->validateRestUrl($restUrl);

        // configure
        $this->configure($context);

        // set vendor
        if ($context->getVendorNumber()) {
            $queryParams[Constants::VENDOR_NUMBER] = $context->getVendorNumber();
        }

        $response = $this->curl->{$method}($restUrl, $queryParams);

        switch ($this->getStatusCode()) {
            case 200:
                return $response;
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

    /**
     * @param $method
     * @throws RestException
     */
    protected function validateMethod($method)
    {
        if (!in_array(strtolower($method), ['get', 'post', 'delete'])) {
            throw new RestException('Invalid request type:' . $method . ', allowed requests types: GET, POST, DELETE.');
        }
    }

    /**
     * @param Context $context
     * @throws RestException
     */
    protected function validateBaseUrl(Context $context)
    {

        if (!$context->getBaseUrl()) {
            throw new RestException('Base url must be specified');
        }

        if (filter_var($context->getBaseUrl(), FILTER_VALIDATE_URL) === false) {
            throw new RestException('Base url "' . $context->getBaseUrl() . '" is not a valid URL');
        }
    }

    /**
     * @param $restUrl
     * @throws RestException
     */
    protected function validateRestUrl($restUrl)
    {
        if (filter_var($restUrl, FILTER_VALIDATE_URL) === false) {
            throw new RestException('Rest url"' . $restUrl . '" is not a valid URL');
        }
    }

    /**
     * @param Context $context
     * @throws RestException
     */
    private function configure(Context $context)
    {
        if (!$context->getSecurityMode()) {
            throw new RestException('Security mode must be specified');
        }

        switch ($context->getSecurityMode()) {
            case Constants::BASIC_AUTHENTICATION:
                if (!$context->getUsername()) throw new RestException('Missing parameter "username"');
                if (!$context->getPassword()) throw new RestException('Missing parameter "password"');

                $this->curl->setHeader('Authorization', 'Basic ' . base64_encode($context->getUsername() . ":" . $context->getPassword()));
                break;
            case Constants::APIKEY_IDENTIFICATION:
                if (!$context->getApiKey()) throw new RestException('Missing parameter "apiKey"');

                $this->curl->setHeader('Authorization', 'Basic ' . base64_encode("apiKey:" . $context->getApiKey()));
                break;
            case Constants::ANONYMOUS_IDENTIFICATION:
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
        return !empty($response->infos->info[0]->value)
            ? $response->infos->info[0]->value
            : '';
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     * @throws \ErrorException
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}