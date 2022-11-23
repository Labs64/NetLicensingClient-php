<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;


class ValidationParameters
{
    protected $productNumber;
    protected $licenseeProperties = [];
    protected $parameters = [];

    /**
     * Sets the target product
     *
     * optional productNumber, must be provided in case licensee auto-create is enabled
     * @param $productNumber
     *
     * @return $this
     */
    public function setProductNumber($productNumber): ValidationParameters
    {
        $this->productNumber = $productNumber;

        return $this;
    }

    public function getProductNumber()
    {
        return $this->productNumber;
    }

    /**
     * Get all licensee properties
     *
     * @return array
     */
    public function getLicenseeProperties(): array
    {
        return $this->licenseeProperties;
    }

    /**
     * Set licensee property
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setLicenseeProperty(string $key, $value): ValidationParameters
    {
        $this->licenseeProperties[$key] = $value;

        return $this;
    }

    /**
     * Get licensee property
     *
     * @param string $key
     * @return mixed
     */
    public function getLicenseeProperty(string $key)
    {
        return $this->licenseeProperties[$key];
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
    public function setLicenseeName($licenseeName): ValidationParameters
    {
        $this->setLicenseeProperty(Constants::LICENSEE_PROP_LICENSEE_NAME, $licenseeName);

        return $this;
    }

    public function getLicenseeName()
    {
        return $this->getLicenseeProperty(Constants::LICENSEE_PROP_LICENSEE_NAME);
    }

    /**
     * Sets the licensee secret
     *
     * licensee secret stored on the client side. Refer to Licensee Secret documentation for details.
     * @param $licenseeSecret
     *
     * @return $this
     * @deprecated use 'NodeLocked' licensing model instead
     */
    public function setLicenseeSecret($licenseeSecret): ValidationParameters
    {
        $this->setLicenseeProperty(Constants::LICENSE_PROP_LICENSEE_SECRET, $licenseeSecret);

        return $this;
    }

    /**
     * @deprecated use 'NodeLocked' licensing model instead
     */
    public function getLicenseeSecret()
    {
        return $this->getLicenseeProperty(Constants::LICENSE_PROP_LICENSEE_SECRET);
    }

    public function getParameters(): array
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