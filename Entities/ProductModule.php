<?php
namespace NetLicensing;

class ProductModule extends BaseEntity
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

    public function setProductNumber($product_number, $refresh = FALSE)
    {
        $this->_setProperty('productNumber', $product_number, $refresh);
    }

    public function getProductNumber($default = '')
    {
        return $this->_getProperty('productNumber', $default);
    }

    public function setLicensingModel($licensingModel, $refresh = FALSE)
    {
        $this->_setProperty('licensingModel', ucfirst($licensingModel), $refresh);
    }

    public function getLicensingModel($default = '')
    {
        return $this->_getProperty('licensingModel', $default);
    }

}