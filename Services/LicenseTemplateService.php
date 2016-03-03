<?php
/**
 * Created by PhpStorm.
 * User: Black
 * Date: 03.03.2016
 * Time: 9:40
 */

namespace NetLicensing;


class LicenseTemplateService extends BaseEntityService {

    const SERVICE_URL = '/licensetemplate';

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new LicenseTemplateService($nlic_connect);
    }

    public function getList()
    {
        return $this->_getList($this->nlic_connect);
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

    protected function _getNewEntity()
    {
        return new LicenseTemplate();
    }

    protected function _getServiceUrlPart()
    {
        return self::SERVICE_URL;
    }
} 