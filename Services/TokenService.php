<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the Token Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Token+Services
 *
 * Class TokenService
 * @package NetLicensing
 */
class TokenService extends BaseEntityService
{
    const SERVICE_URL = '/token';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return TokenService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new TokenService($nlic_connect);
    }

    /**
     * Returns all tokens of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Tokenslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets token by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Gettoken
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * Genarates token by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Createtoken
     *
     * @param string $token_type
     * @param string $licensee_number
     * @param array $custom_properties
     * @return bool|Token
     * @throws NetLicensingException
     */
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

        $response = $this->nlic_connect->post($this->_getServiceUrl(), $params);
        $properties_array = NetLicensingAPI::getPropertiesByXml($response);

        if (empty($properties_array)) {
            return FALSE;
        }

        $properties = reset($properties_array);

        $token = $this->_createEntity();
        $token->setProperties($properties, TRUE);

        return $token;
    }

    /**
     * Delete token by number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Deletetoken
     *
     * @param $number
     * @return bool
     */
    public function delete($number)
    {
        return $this->_delete($number, $this->nlic_connect);
    }

    /**
     * @return Token
     */
    protected function _createEntity()
    {
        return new Token();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
} 
