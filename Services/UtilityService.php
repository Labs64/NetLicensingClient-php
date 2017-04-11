<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the Utility Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Utility+Services
 *
 * Class UtilityService
 * @package NetLicensing
 */
class UtilityService extends BaseEntityService
{
    const SERVICE_URL = '/utility';
    const UTILITY_ENDPOINT_PATH_LICENSING_MODELS = 'licensingModels';
    const UTILITY_ENDPOINT_PATH_LICENSE_TYPES = 'licenseTypes';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return UtilityService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new UtilityService($nlic_connect);
    }

    /**
     * Returns all license types. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Utility+Services#UtilityServices-LicenseTypeslist
     *
     * @return array
     */
    public function getListLicenseTypes()
    {
        $response = $this->nlic_connect->get($this->_getServiceRequestUrl() . '/' . self::UTILITY_ENDPOINT_PATH_LICENSING_MODELS);
        return NetLicensingAPI::getPropertiesByXml($response);
    }

    /**
     * Returns all licensing models. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Utility+Services#UtilityServices-LicensingModelslist
     *
     * @return array
     */
    public function getListLicensingModels()
    {
        $response = $this->nlic_connect->get($this->_getServiceRequestUrl() . '/' . self::UTILITY_ENDPOINT_PATH_LICENSE_TYPES);
        return NetLicensingAPI::getPropertiesByXml($response);
    }

    /**
     * @return null
     */
    protected function _createEntity()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
}
