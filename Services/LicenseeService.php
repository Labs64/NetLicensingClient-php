<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the Licensee Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services
 *
 * Class LicenseeService
 * @package NetLicensing
 */
class LicenseeService extends BaseEntityService
{
    const SERVICE_URL = '/licensee';
    const LICENSEE_ENDPOINT_PATH_VALIDATE = 'validate';
    const LICENSEE_ENDPOINT_PATH_TRANSFER = 'transfer';

    const PRODUCT_MODULE_NUMBER = "productModuleNumber";
    const PRODUCT_NUMBER = "productNumber";
    const LICENSEE_NAME = "licenseeName";
    const LICENSEE_SECRET = "licenseeSecret";

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return LicenseeService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new LicenseeService($nlic_connect);
    }

    /**
     * Returns all licensees of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Licenseeslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets licensee by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Getlicensee
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * Creates new licensee object with given properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Createlicensee
     *
     * @param Licensee $licensee
     * @return bool
     */
    public function create(Licensee $licensee)
    {
        return $this->_create($licensee, $this->nlic_connect);
    }

    /**
     * Updates licensee properties. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Updatelicensee
     *
     * @param Licensee $licensee
     * @return bool
     */
    public function update(Licensee $licensee)
    {
        return $this->_update($licensee, $this->nlic_connect);
    }

    /**
     * Deletes licensee. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Deletelicensee
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
     * Validates active licenses of the licensee. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Validatelicensee
     *
     * @param $licensee_number
     * @param $validationParameters
     * @return array
     */
    public function validate($licensee_number, $validationParameters)
    {
        if ($validationParameters instanceof ValidationParameters) {
            $params = array();
            if (!empty($validationParameters->getProductNumber())) {
                $params[self::PRODUCT_NUMBER] = $validationParameters->getProductNumber();
            }
            if (!empty($validationParameters->getLicenseeName())) {
                $params[self::LICENSEE_NAME] = $validationParameters->getLicenseeName();
            }
            if (!empty($validationParameters->getLicenseeSecret())) {
                $params[self::LICENSEE_SECRET] = $validationParameters->getLicenseeSecret();
            }

            $pmIndex = 0;
            foreach ($validationParameters->getParameters() as $productModuleName => $parameters) {
                $params[self::PRODUCT_MODULE_NUMBER . $pmIndex] = $productModuleName;
                foreach ($parameters as $parameter_key => $parameter_val) {
                    $params[$parameter_key . $pmIndex] = $parameter_val;
                }
                $pmIndex++;
            }

            $response = $this->nlic_connect->post($this->_getServiceRequestUrl() . '/' . $licensee_number . '/' . self::LICENSEE_ENDPOINT_PATH_VALIDATE, $params);
            return NetLicensingAPI::getPropertiesByXml($response);
        }

        //support for old call
        $args = func_get_args();

        $product_number = !empty($args[1]) ? $args[1] : '';
        $licensee_name = !empty($args[2]) ? $args[2] : '';
        return $this->validate_old($licensee_number, $product_number, $licensee_name);
    }

    /**
     * Validates active licenses of the licensee. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Licensee+Services#LicenseeServices-Validatelicensee
     *
     * @param $licensee_number
     * @param string $product_number
     * @param string $license_name
     * @return array
     * @throws NetLicensingException
     */
    public function validate_old($licensee_number, $product_number = '', $license_name = '')
    {
        trigger_error('Deprecated method: "validate($licensee_number, $product_number, $license_name)", use validate($licensee_number, $validationParameters)', E_USER_DEPRECATED);

        $params = array();
        $licensee_number = (string)$licensee_number;

        if (empty($licensee_number)) {
            throw new NetLicensingException('Licensee Number cannot be empty');
        }

        //check product number(s)
        if (!empty($product_number)) {
            switch (gettype($product_number)) {
                case 'string':
                    $params[self::PRODUCT_NUMBER] = $product_number;
                    break;
                case 'array':
                    $count = count($product_number);
                    $index = ($count > 1) ? 0 : '';
                    foreach ($product_number as $number) {
                        switch (gettype($number)) {
                            case 'int':
                                $params[self::PRODUCT_NUMBER . $index] = (string)$number;
                                break;
                            case 'string':
                                $params[self::PRODUCT_NUMBER . $index] = $number;
                                break;
                            case 'object':
                                if ($number instanceof Product) {
                                    if (!$number->getOldProperty('number')) {
                                        throw new NetLicensingException('Validation error: product number cannot be empty');
                                    }

                                    $params[self::PRODUCT_NUMBER . $index] = $number->getOldProperty('number');
                                } else {
                                    throw new NetLicensingException('Validation error: entity ' . get_class($number) . ' is invalid; must be instanceof Product');
                                }
                                break;
                            default:
                                throw new NetLicensingException('Validation error: product number cannot be ' . gettype($product_number));
                                break;
                        }
                        if ($count > 1) $index++;
                    }
                    break;
                default:
                    if (!is_string($license_name)) {
                        throw new NetLicensingException('Validation error: wrong product number type provided ' . gettype($product_number));
                    }
                    break;
            }
        }

        if ($license_name) {
            if (!is_string($license_name)) {
                throw new NetLicensingException('Validation error: license name is not string ' . gettype($product_number));
            }
            $params[self::LICENSEE_NAME] = $license_name;
        }

        $response = $this->nlic_connect->post($this->_getServiceRequestUrl() . '/' . $licensee_number . '/' . self::LICENSEE_ENDPOINT_PATH_VALIDATE, $params);

        return NetLicensingAPI::getPropertiesByXml($response);
    }

    /**
     * Transfer licenses between licensees.
     * TODO(AY): Wiki Link
     *
     * @param $licensee_number
     * @param $sourceLicenseeNumber
     * @return boolean
     * @throws NetLicensingException
     */
    public function transfer($licensee_number, $sourceLicenseeNumber)
    {
        $params = array();
        $licensee_number = (string)$licensee_number;

        if (empty($licensee_number)) {
            throw new NetLicensingException('Licensee Number cannot be empty');
        }
        if (empty($sourceLicenseeNumber)) {
            throw new NetLicensingException('Source Licensee Number cannot be empty');
        }

        if (!is_string($sourceLicenseeNumber)) {
            throw new NetLicensingException('Transfer error: Source Licensee Number is not string ' . gettype($sourceLicenseeNumber));
        }
        $params['sourceLicenseeNumber'] = $sourceLicenseeNumber;

        $this->nlic_connect->post($this->_getServiceRequestUrl() . '/' . $licensee_number . '/' . self::LICENSEE_ENDPOINT_PATH_TRANSFER, $params);

        $status_code = $this->nlic_connect->getHttpStatusCode();
        return (!empty($status_code) && $status_code == '204') ? TRUE : FALSE;
    }

    /**
     * @param $api_key
     */
    public static function validateByApiKey($api_key)
    {
        // TODO
    }

    /**
     * @return Licensee
     */
    protected function _createEntity()
    {
        return new Licensee();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 
