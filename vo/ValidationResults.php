<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 *
 * @property string $productModuleNumber
 * @property boolean $valid
 * @property int $remainingQuantity
 * @property string $productModuleName
 * @property string $licensingModel
 *
 * @property
 *
 * @package NetLicensing
 */
class ValidationResults
{
    protected $validators = [];
    protected $ttl;

    public function getValidations()
    {
        return $this->validators;
    }

    public function getProductModuleValidation($productModuleNumber)
    {
        return isset($this->validators[$productModuleNumber]) ? $this->validators[$productModuleNumber] : null;
    }

    public function setProductModuleValidation($productModuleNumber, $productModuleValidation)
    {
        $this->validators[$productModuleNumber] = $productModuleValidation;

        return $this;
    }

    public function __toString()
    {
        $data = 'ValidationResult [';

        foreach ($this->validators as $productModuleNumber => $validator) {
            $data .= 'ProductModule<';
            $data .= $productModuleNumber;
            $data .= '>';

            foreach ($validator as $key => $value) {
                $data .= $key . '=' . $value;
            }

            if ($validator != end($validator)) {
                $data .= ',';
            }
        }

        $data .= ']';

        return $data;
    }


    public function getTtl()
    {
        return $this->ttl;
    }

    public function setTtl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }
}