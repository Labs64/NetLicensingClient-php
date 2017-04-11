<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the LicenseTemplate Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services
 *
 * Class LicenseTemplateService
 * @package NetLicensing
 */
class LicenseTemplateService extends BaseEntityService
{

    const SERVICE_URL = '/licensetemplate';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return LicenseTemplateService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new LicenseTemplateService($nlic_connect);
    }

    /**
     * Returns all license templates of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Licensetemplateslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets license template by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Getlicensetemplate
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * Creates new license template object with given properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Createlicensetemplate
     *
     * @param LicenseTemplate $license_template
     * @return bool
     */
    public function create(LicenseTemplate $license_template)
    {
        return $this->_create($license_template, $this->nlic_connect);
    }

    /**
     * Updates license template properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Updatelicensetemplate
     *
     * @param LicenseTemplate $license_template
     * @return bool
     */
    public function update(LicenseTemplate $license_template)
    {
        return $this->_update($license_template, $this->nlic_connect);
    }

    /**
     * Deletes license template. See NetLicensingAPI JavaDoc for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Template+Services#LicenseTemplateServices-Deletelicensetemplate
     *
     * @param $number
     * @param bool $force_cascade
     * @return bool
     */
    public function delete($number, $force_cascade = FALSE)
    {
        return $this->_delete($number, $this->nlic_connect, $force_cascade);
    }

    /**
     * @return LicenseTemplate
     */
    protected function _createEntity()
    {
        return new LicenseTemplate();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 
