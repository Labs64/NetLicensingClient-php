<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

class LicenseTemplateService extends BaseEntityService
{

    const SERVICE_URL = '/licensetemplate';

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new LicenseTemplateService($nlic_connect);
    }

    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    public function create(LicenseTemplate $license_template)
    {
        return $this->_create($license_template, $this->nlic_connect);
    }

    public function update(LicenseTemplate $license_template)
    {
        return $this->_update($license_template, $this->nlic_connect);
    }

    public function delete($number, $force_cascade = FALSE)
    {
        return $this->_delete($number, $this->nlic_connect, $force_cascade);
    }

    protected function _createEntity()
    {
        return new LicenseTemplate();
    }

    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 
