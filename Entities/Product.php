<?php
/**
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2015 Labs64
 */
namespace NetLicensing;


class Product extends BaseEntity
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

    public function setVersion($version, $refresh = FALSE)
    {
        $version = floatval($version);
        $this->_setProperty('version', (string)$version, $refresh);
    }

    public function getVersion()
    {
        return floatval($this->_getProperty('version'));
    }

    public function setLicenseeAutoCreate($state, $refresh = FALSE)
    {
        if (is_bool($state)) $state = ($state) ? 'true' : 'false';
        $this->_setProperty('licenseeAutoCreate', $state, $refresh);
    }

    public function getLicenseeAutoCreate()
    {
        return ($this->_getProperty('licenseeAutoCreate'));
    }

    public function setDescription($description, $refresh = FALSE)
    {
        $this->_setProperty('description', $description, $refresh);
    }

    public function getDescription($default = '')
    {
        return $this->_checkPlain($this->_getProperty('description', $default));
    }

    public function setLicensingInfo($licensing_info, $refresh = FALSE)
    {
        $this->_setProperty('licensingInfo', $licensing_info, $refresh);
    }

    public function getLicensingInfo($default = '')
    {
        return $this->_checkPlain($this->_getProperty('licensingInfo', $default));
    }

    public function getInUse()
    {
        return ($this->_getProperty('inUse'));
    }

} 
