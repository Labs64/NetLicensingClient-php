<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;


class License extends BaseEntity
{

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

    public function setName($name, $refresh = FALSE)
    {
        $this->_setProperty('name', $name, $refresh);
    }

    public function getName($default = '')
    {
        return $this->_getProperty('name', $default);
    }

    public function setLicenseeNumber($licensee_number, $refresh = FALSE)
    {
        $this->_setProperty('licenseeNumber', $licensee_number, $refresh);
    }

    public function getLicenseeNumber($default = '')
    {
        return $this->_getProperty('licenseeNumber', $default);
    }

    public function setLicenseTemplateNumber($license_template_number, $refresh = FALSE)
    {
        $this->_setProperty('licenseTemplateNumber', $license_template_number, $refresh);
    }

    public function getLicenseTemplateNumber($default = '')
    {
        return $this->_getProperty('licenseTemplateNumber', $default);
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
} 