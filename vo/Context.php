<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * Class Context
 *
 * Provides calling context for the NetLicensing API calls.
 * The Context object may differ depending on the level at which NetLicensing API is called.
 * For the internal Java NetLicensing API the Context provides information about the targeted Vendor.
 *
 * @property string $baseUrl
 * Server URL base of the NetLicensing RESTful API. Normally should be "https://go.netlicensing.io".
 *
 * @property  string $username
 * Login name of the user sending the requests when securityMode = BASIC_AUTHENTICATION.
 *
 * @property string $password
 * Password of the user sending the requests when securityMode = BASIC_AUTHENTICATION.
 *
 * @property string $apiKey
 * API Key used to identify the request sender when securityMode = APIKEY_IDENTIFICATION.
 *
 * @property string $securityMode
 * Determines the security mode used for accessing the NetLicensing API.
 * See https://www.labs64.de/confluence/x/pwCo#NetLicensingAPI%28RESTful%29-Security for details.
 *
 * @property string $vendorNumber
 * External number of the vendor.
 *
 * @method string getBaseUrl($default = null)
 * @method string getUsername($default = null)
 * @method string getPassword($default = null)
 * @method string getApiKey($default = null)
 * @method string getSecurityMode($default = null)
 * @method string getVendorNumber($default = null)
 * @method \NetLicensing\Context setBaseUrl($baseurl)
 * @method \NetLicensing\Context setUsername($username)
 * @method \NetLicensing\Context setPassword($password)
 * @method \NetLicensing\Context setApiKey($apiKey)
 * @method \NetLicensing\Context setSecurityMode($securityMode)
 * @method \NetLicensing\Context setVendorNumber($vendorNumber)
 *
 * @package NetLicensing\Rest
 */

class Context
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::BASIC_AUTHENTICATION instead.
     */
    const BASIC_AUTHENTICATION = 'BASIC_AUTH';
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::APIKEY_IDENTIFICATION instead.
     */
    const APIKEY_IDENTIFICATION = 'APIKEY';

    /**
     * Context defaults
     * @var string
     */
    protected $baseUrl = 'https://go.netlicensing.io/core/v2/rest';

    protected $securityMode = Constants::BASIC_AUTHENTICATION;

    /**
     * The context values.
     *
     * @var array
     */
    private $values = [];

    /**
     * Create a new context instance.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->setValues(array_merge([
            'baseUrl' => $this->baseUrl,
            'securityMode' => $this->securityMode
        ], $values));
    }

    /**
     * Get an value from the context.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        if (!$key) return $default;

        return isset($this->values[$key]) ? $this->values[$key] : $default;
    }

    /**
     * Get all of the current value on the context.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Set a given values on the context.
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function setValue($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * Set the array of context values.
     *
     * @param  array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Dynamically retrieve values on the context.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getValue($key);
    }

    /**
     * Dynamically set values on the context.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setValue($key, $value);
    }

    /**
     * Determine if an values exists on the context.
     *
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return !is_null($this->getValue($key));
    }

    /**
     * Unset an values on the context.
     *
     * @param  string $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->values[$key]);
    }

    /**
     * Handle dynamic method calls into the context.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        //convert method to snake case
        $delimiter = '_';
        $method = preg_replace('/\s+/u', '', $method);
        $method = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $method), 'UTF-8');

        $methodParts = explode($delimiter, $method);

        //check if need set or get attributes
        if (in_array($methodParts[0], ['get', 'set'])) {

            //get attribute name
            $key = array_slice($methodParts, 1);
            $key = implode('_', $key);
            $key = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key))));

            array_unshift($parameters, $key);

            //call getValue
            if ($methodParts[0] == 'get') return $this->getValue(...$parameters);

            //call setValue
            if ($methodParts[0] == 'set') return $this->setValue(...$parameters);
        }

        //trigger error if method undefined
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $method . '()', E_USER_ERROR);
    }
}