<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the License Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/License+Services
 *
 * Class LicenseService
 * @package NetLicensing
 */
class LicenseService extends BaseEntityService
{
    const SERVICE_URL = '/license';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return LicenseService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new LicenseService($nlic_connect);
    }

    /**
     * Returns all licenses of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Licenseslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets license by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Getlicense
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * Creates new license object with given properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Createlicense
     * @param License $license
     * @return bool
     */
    public function create(License $license)
    {
        return $this->_create($license, $this->nlic_connect);
    }

    /**
     * Updates license properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Updatelicense
     *
     * @param License $license
     * @return bool
     */
    public function update(License $license)
    {
        return $this->_update($license, $this->nlic_connect);
    }

    /**
     * Deletes license. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/License+Services#LicenseServices-Deletelicense
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
     * @return License
     */
    protected function _createEntity()
    {
        return new License();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 
