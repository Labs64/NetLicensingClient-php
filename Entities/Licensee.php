<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache License, Version 2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;


class Licensee extends BaseEntity
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

} 
