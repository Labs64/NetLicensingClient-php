<?php

/**
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2015 Labs64
 */
namespace NetLicensing;

class LicenseeService extends BaseEntityService
{
    const SERVICE_URL = '/licensee';

    public function init()
    {
        $this->nlic_connect->setResponseFormat('xml');
    }

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new LicenseeService($nlic_connect);
    }

    public function getList()
    {
        return $this->_getList($this->nlic_connect);
    }

    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    public function create(ProductModule $product_module)
    {
        return $this->_create($product_module, $this->nlic_connect);
    }

    public function update(ProductModule $product_module)
    {
        return $this->_update($product_module, $this->nlic_connect);
    }

    public function delete($number, $force_cascade = FALSE)
    {
        return $this->_delete($number, $this->nlic_connect, $force_cascade);
    }

    /**
     * @param string $licensee_number
     * @param array|string $product_number
     * @param string $license_name
     */
    public function validate($licensee_number, $product_number = '', $license_name = '')
    {
        $params = array();
        $licensee_number = (string)$licensee_number;

        if (empty($licensee_number)) throw new NetLicensingException('LicenseeNumber can not be empty');

        //check product number(s)
        if (!empty($product_number)) {
            switch (gettype($product_number)) {
                case 'string':
                    $params['productNumber'] = $product_number;
                    break;
                case 'array':
                    $count = count($product_number);
                    $index = ($count > 1) ? 0 : '';
                    foreach ($product_number as $number) {
                        switch (gettype($number)) {
                            case 'int':
                                $params['productNumber' . $index] = (string)$number;
                                break;
                            case 'string':
                                $params['productNumber' . $index] = $number;
                                break;
                            case 'object':
                                if ($number instanceof Product) {
                                    if (!$number->getOldProperty('number')) throw new NetLicensingException('Unable to request validation, because the product does not have a number');

                                    $params['productNumber' . $index] = $number->getOldProperty('number');
                                } else {
                                    throw new NetLicensingException('Unable to request validation, because entity ' . get_class($number) . ' is invalid, entity must be instanceof Product');
                                }
                                break;
                            default:
                                throw new NetLicensingException('Unable to request validation, because product number can not be' . gettype($product_number));
                                break;
                        }
                        if ($count > 1) $index++;
                    }
                    break;
                default:
                    if (!is_string($license_name)) throw new NetLicensingException('Unable to request validation, because product number expected string or array, ' . gettype($product_number) . ' given');
                    break;
            }
        }

        if ($license_name) {
            if (!is_string($license_name)) throw new NetLicensingException('Unable to request validation, because license number expected string, ' . gettype($product_number) . ' given');
            $params['licenseeName'] = $license_name;
        }

        $response = $this->nlic_connect->get($this->_getServiceRequestPartUrl() . '/' . $licensee_number . '/validate', $params);

       return NetLicensingAPI::getPropertiesByXml($response);
    }

    public static function validateByApiKey($api_key)
    {

    }

    protected function _getNewEntity()
    {
        return new Licensee();
    }

    protected function _getServiceUrlPart()
    {
        return self::SERVICE_URL;
    }
} 
