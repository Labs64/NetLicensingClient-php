<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Token Service. See NetLicensingAPI for details:
 * https://netlicensing.io/wiki/token-services
 *
 * @package NetLicensing
 */
class TokenService
{
    /**
     * Creates new token.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/token-services#create-token
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param Token $token
     *
     * return created token
     * @return Token|null
     * @throws RestException
     */
    public static function create(Context $context, Token $token): ?Token
    {
        $response = NetLicensingService::getInstance()
            ->post($context, Constants::TOKEN_ENDPOINT_PATH, $token->asPropertiesMap());

        $createdToken = null;

        if (!empty($response->items->item[0])) {
            $createdToken = ItemToTokenConverter::convert($response->items->item[0]);
            $createdToken->exists = true;
        }

        return $createdToken;
    }

    /**
     * Gets token by its number..See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/token-services#get-token
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the token number
     * @param string $number
     *
     * return the token
     * @return Token|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function get(Context $context, string $number): ?Token
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::TOKEN_ENDPOINT_PATH . '/' . $number);

        $token = null;

        if (!empty($response->items->item[0])) {
            $token = ItemToTokenConverter::convert($response->items->item[0]);
            $token->exists = true;
        }

        return $token;
    }

    /**
     * Returns tokens of a vendor.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/token-services#tokens-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * array of token entities or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function getList(Context $context, ?string $filter = null): Page
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::TOKEN_ENDPOINT_PATH, $queryParams);

        $tokens = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $token = ItemToTokenConverter::convert($item);
                $token->exists = true;

                $tokens[] = $token;
            }
        }

        return new Page($tokens, $pageNumber, $itemsNumber, $totalPages, $totalItems);
    }

    /**
     * Delete token by its number.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/token-services#delete-token
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     *  the token number
     * @param string $number
     *
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function delete(Context $context, string $number)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        NetLicensingService::getInstance()
            ->delete($context, Constants::TOKEN_ENDPOINT_PATH . '/' . $number);
    }
}
