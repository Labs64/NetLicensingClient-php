<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use DateTime;

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
    protected array $validators = [];
    protected DateTime  $ttl;

    public function getValidations(): array
    {
        return $this->validators;
    }

    public function getProductModuleValidation(string $productModuleNumber): mixed
    {
        return $this->validators[$productModuleNumber] ?? null;
    }

    public function setProductModuleValidation(string $productModuleNumber, mixed $productModuleValidation): ValidationResults
    {
        $this->validators[$productModuleNumber] = $productModuleValidation;
        return $this;
    }

    public function __toString(): string
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


    public function getTtl(): DateTime
    {
        return $this->ttl;
    }

    public function setTtl(DateTime $ttl): ValidationResults
    {
        $this->ttl = $ttl;

        return $this;
    }
}
