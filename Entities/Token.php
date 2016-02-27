<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
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
