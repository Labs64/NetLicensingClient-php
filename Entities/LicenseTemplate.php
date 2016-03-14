<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

class LicenseTemplate extends BaseEntity
{
    const LICENSE_TYPE_TIMEVOLUME = 'TIMEVOLUME';
    const LICENSE_TYPE_FEATURE = 'FEATURE';

    public function __construct(array $properties = array())
    {
        $this->_setProperties($properties);
    }

    public function setNumber($number, $refresh = FALSE)
    {
        $this->_setProperty('number', $number, $refresh);
    }

    public function getNumber($default = '')
    {
        return $this->_getProperty('number', $default);
    }

    public function setProductModuleNumber($number, $refresh = FALSE)
    {
        $this->_setProperty('productModuleNumber', $number, $refresh);
    }

    public function getProductModuleNumber($default = '')
    {
        return $this->_getProperty('productModuleNumber', $default);
    }

    public function setActive($state, $refresh = FALSE)
    {
        if (is_bool($state)) $state = ($state) ? 'true' : 'false';

        $this->_setProperty('active', $state, $refresh);
    }

    public function getActive()
    {
        return ($this->_getProperty('active') == 'true') ? TRUE : FALSE;
    }

    public function setName($name, $refresh = FALSE)
    {
        $this->_setProperty('name', $name, $refresh);
    }

    public function getName($default = '')
    {
        return $this->_getProperty('name', $default);
    }

    public function setLicenseType($license_type, $refresh = FALSE)
    {
        $this->_setProperty('licenseType', $license_type, $refresh);
    }

    public function getLicenseType($default = '')
    {
        return $this->_getProperty('licenseType', $default);
    }

    public function setPrice($price, $refresh = FALSE)
    {
        $this->_setProperty('price', (string)$price, $refresh);
    }

    public function getPrice($default = '')
    {
        return $this->_getProperty('price', $default);
    }

    public function setCurrency($currency, $refresh = FALSE)
    {
        $this->_setProperty('currency', strtoupper($currency), $refresh);
    }

    public function getCurrency($default = '')
    {
        return $this->_getProperty('currency', $default);
    }

    public function setAutomatic($state, $refresh = FALSE)
    {
        if (is_bool($state)) $state = ($state) ? 'true' : 'false';

        $this->_setProperty('automatic ', $state, $refresh);
    }

    public function getAutomatic()
    {
        return ($this->_getProperty('automatic ') == 'true') ? TRUE : FALSE;
    }

    public function setHidden($state, $refresh = FALSE)
    {
        if (is_bool($state)) $state = ($state) ? 'true' : 'false';

        $this->_setProperty('hidden', $state, $refresh);
    }

    public function getHidden()
    {
        return ($this->_getProperty('hidden') == 'true') ? TRUE : FALSE;
    }

    public function setHideLicenses($state, $refresh = FALSE)
    {
        if (is_bool($state)) $state = ($state) ? 'true' : 'false';

        $this->_setProperty('hideLicenses', $state, $refresh);
    }

    public function getHideLicenses()
    {
        return ($this->_getProperty('hideLicenses') == 'true') ? TRUE : FALSE;
    }

    public function setTimeVolume($time_volume, $refresh = FALSE)
    {
        $this->_setProperty('timeVolume', (string)$time_volume, $refresh);
    }

    public function getTimeVolume($default = '')
    {
        return $this->_getProperty('timeVolume', $default);
    }
}
