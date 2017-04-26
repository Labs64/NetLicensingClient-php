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
    public $productNumber = "";
    public $licenseeName = "";
    public $licenseeSecret = "";
    public $parameters = array();

    /**
     * Sets the target product
     *
     * @param productNumber
     *            optional productNumber, must be provided in case licensee auto-create is enabled
     */
    public function setProductNumber($productNumber) {
        $this->productNumber = $productNumber;
    }

    public function getProductNumber() {
        return $this->productNumber;
    }

    /**
     * Sets the name for the new licensee
     *
     * @param licenseeName
     *            optional human-readable licensee name in case licensee will be auto-created. This parameter must not
     *            be the name, but can be used to store any other useful string information with new licensees, up to
     *            1000 characters.
     */
    public function setLicenseeName($licenseeName) {
        $this->licenseeName = $licenseeName;
    }

    public function getLicenseeName() {
        return $this->licenseeName;
    }

    /**
     * Sets the licensee secret
     *
     * @param licenseeSecret
     *            licensee secret stored on the client side. Refer to Licensee Secret documentation for details.
     */
    public function setLicenseeSecret($licenseeSecret) {
        $this->licenseeSecret = $licenseeSecret;
    }

    public function getLicenseeSecret() {
        return $this->licenseeSecret;
    }


    public function getParameters() {
        return $this->parameters;
    }

    public function getProductModuleValidationParameters($productModuleNumber) {
        if (empty($this->parameters[$productModuleNumber])) {
            $this->parameters[$productModuleNumber] = array();
        }
        return $this->parameters[$productModuleNumber];
    }

    public function setProductModuleValidationParameters($productModuleNumber, $productModuleParameters) {
        if (empty($this->parameters[$productModuleNumber])) {
            $this->parameters[$productModuleNumber] = array();
        }
        $this->parameters[$productModuleNumber] += $productModuleParameters;
    }
}