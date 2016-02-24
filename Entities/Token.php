<?php
/**
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2015 Labs64
 */
namespace NetLicensing;


class Token extends BaseEntity
{
    public function __construct(array $properties = array())
    {
        $this->_setProperties($properties);
    }

    public function getNumber($default = '')
    {
        return $this->_getProperty('number', $default);
    }

    public function getActive()
    {
        return ($this->_getProperty('active') == 'true') ? TRUE : FALSE;
    }

    public function getExpirationTime($default = '')
    {
        return $this->_getProperty('expirationTime', $default);
    }

    public function getTokenType($default = '')
    {
        return $this->_getProperty('tokenType', $default);
    }

    public function getShopUrl($default = '')
    {
        return $this->_getProperty('shopURL', $default);
    }

    public function getLicenseeNumber($default = '')
    {
        return $this->_getProperty('licenseeNumber', $default);
    }

    public function getVendorNumber($default = '')
    {
        return $this->_getProperty('vendorNumber', $default);
    }
} 
