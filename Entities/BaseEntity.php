<?php

namespace NetLicensing;

abstract class BaseEntity
{
    protected $_properties = array();
    protected $_old_properties = array();

    public function getProperty($name, $default = '')
    {
        return $this->_getProperty($name, $default);
    }

    public function setProperty($name, $value, $refresh = FALSE)
    {
        $this->_setProperty($name, $value, $refresh);
    }

    public function setProperties(array $properties, $refresh = FALSE)
    {
        $this->_setProperties($properties, $refresh);
    }

    public function getProperties($default = array())
    {
        return $this->_getProperties($default);
    }

    public function getOldProperty($name, $default = '')
    {
        return $this->_getOldProperty($name, $default);
    }

    public function getOldProperties($default = array())
    {
        return $this->_getOldProperties($default);
    }

    protected function _verifyTypeIsString($value)
    {
        if (!is_string($value)) return FALSE;

        return TRUE;
    }

    protected function _setProperty($name, $value, $refresh = FALSE)
    {
        if (is_bool($value)) $value = ($value) ? 'true' : 'false';

        if (!$this->_verifyTypeIsString($value)) {
            throw new NetLicensingException('Expected "' . $name . '"" string type. Passed ' . gettype($value));
        }

        $this->_properties[$name] = $this->_checkPlain($value);

        if (empty($this->_old_properties[$name]) || $refresh) $this->_old_properties[$name] = $this->_properties[$name];
    }

    protected function _getProperty($name, $default = '')
    {
        return isset($this->_properties[$name]) ? $this->_properties[$name] : $default;
    }

    protected function _getOldProperty($name, $default = '')
    {
        return isset($this->_old_properties[$name]) ? $this->_old_properties[$name] : $default;
    }

    protected function _setProperties(array $properties, $refresh = FALSE)
    {
        if ($properties) {
            foreach ($properties as $name => $value) {
                $this->_setProperty($name, $value, $refresh);
            }
        }
    }

    protected function _getProperties($default = array())
    {
        return !empty($this->_properties) ? $this->_properties : $default;
    }

    protected function _getOldProperties($default = array())
    {
        return !empty($this->_old_properties) ? $this->_old_properties : $default;
    }

    protected function _checkPlain($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
