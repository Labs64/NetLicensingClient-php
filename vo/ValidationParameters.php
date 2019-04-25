<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


class ValidationParameters
{
    protected $productNumber;
    protected $licenseeName;
    protected $licenseeSecret;
    protected $parameters = [];

    /**
     * Sets the target product
     *
     * optional productNumber, must be provided in case licensee auto-create is enabled
     * @param $productNumber
     *
     * @return $this
     */
    public function setProductNumber($productNumber)
    {
        $this->productNumber = $productNumber;

        return $this;
    }

    public function getProductNumber()
    {
        return $this->productNumber;
    }

    /**
     * Sets the name for the new licensee
     *
     * optional human-readable licensee name in case licensee will be auto-created. This parameter must not
     * be the name, but can be used to store any other useful string information with new licensees, up to
     * 1000 characters.
     * @param $licenseeName
     *
     * @return $this
     */
    public function setLicenseeName($licenseeName)
    {
        $this->licenseeName = $licenseeName;

        return $this;
    }

    public function getLicenseeName()
    {
        return $this->licenseeName;
    }

    /**
     * Sets the licensee secret
     *
     * licensee secret stored on the client side. Refer to Licensee Secret documentation for details.
     * @deprecated use 'NodeLocked' licensing model instead
     * @param $licenseeSecret
     *
     * @return $this
     */
    public function setLicenseeSecret($licenseeSecret)
    {
        $this->licenseeSecret = $licenseeSecret;

        return $this;
    }

    /**
     * @deprecated use 'NodeLocked' licensing model instead
     */
    public function getLicenseeSecret()
    {
        return $this->licenseeSecret;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getProductModuleValidationParameters($productModuleNumber)
    {
        if (empty($this->parameters[$productModuleNumber])) {
            $this->parameters[$productModuleNumber] = array();
        }
        return $this->parameters[$productModuleNumber];
    }

    public function setProductModuleValidationParameters($productModuleNumber, $productModuleParameters)
    {
        if (empty($this->parameters[$productModuleNumber])) {
            $this->parameters[$productModuleNumber] = array();
        }
        $this->parameters[$productModuleNumber] += $productModuleParameters;
    }
}