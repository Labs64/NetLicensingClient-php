<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Token Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Token+Services
 *
 * @package NetLicensing
 */
class TokenService
{
    const ENDPOINT_PATH = 'token';

    /**
     * Creates new token.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Createtoken
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param Token $token
     *
     * return created token
     * @return mixed|\NetLicensing\Token|null
     */
    public static function create(Context $context, Token $token)
    {
        return NetLicensingService::getInstance()->post($context, self::ENDPOINT_PATH, $token->asPropertiesMap(), $token);
    }

    /**
     * Gets token by its number..See NetLicensingAPI for details:
     * https://www.labs64.de/conluence/display/NLICPUB/Token+Services#TokenServices-Gettoken
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the token number
     * @param $number
     *
     * return the token
     * @return mixed|\NetLicensing\Token|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        return NetLicensingService::getInstance()->get($context, self::ENDPOINT_PATH . '/' . $number, [], Token::class);
    }

    /**
     * Returns tokens of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Tokenslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string $filter
     *
     * array of token entities or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, self::ENDPOINT_PATH, $queryParams, Token::class);
    }

    /**
     * Delete token by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Token+Services#TokenServices-Deletetoken
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     *  the token number
     * @param string $number
     *
     * @return bool
     */
    public static function delete(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        return NetLicensingService::getInstance()->delete($context, self::ENDPOINT_PATH . '/' . $number);
    }
}