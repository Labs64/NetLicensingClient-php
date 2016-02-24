<?php
/**
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2015 Labs64
 */
namespace NetLicensing;


class TokenService extends BaseEntityService
{
    const SERVICE_URL = '/token';

    public function init()
    {
        $this->nlic_connect->setResponseFormat('xml');
    }

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new TokenService($nlic_connect);
    }

    public function getList()
    {
        return $this->_getList($this->nlic_connect);
    }

    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    public function create($token_type = 'DEFAULT', $licensee_number = '', $custom_properties = array())
    {
        $token_type = strtoupper($token_type);
        if ($token_type != 'DEFAULT' && $token_type != 'SHOP') {
            throw new NetLicensingException('Wrong token type, expected DEFAULT or SHOP, given ' . $token_type);
        }

        if ($custom_properties) {
            foreach ($custom_properties as $name => $value) {
                $params[$name] = $value;
            }
        }

        $params['tokenType'] = $token_type;

        if ($token_type == 'SHOP') {
            $params['licenseeNumber'] = $licensee_number;
        }

        $response = $this->nlic_connect->post($this->_getServiceUrlPart(), $params);
        $properties_array = NetLicensingAPI::getPropertiesByXml($response);

        if (empty($properties_array)) return FALSE;

        $properties = reset($properties_array);

        $token = $this->_getNewEntity();
        $token->setProperties($properties, TRUE);

        return $token;
    }


    public function delete($number)
    {
        return $this->_delete($number, $this->nlic_connect);
    }

    protected function _getNewEntity()
    {
        return new Token();
    }

    protected function _getServiceUrlPart()
    {
        return self::SERVICE_URL;
    }
} 
