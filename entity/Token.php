<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * Product module entity used internally by NetLicensing.
 *
 * Properties visible via NetLicensing API:
 *
 * Unique number
 * @property string $number
 *
 * If set to false, the token is disabled.
 * @property boolean $active
 *
 * Expiration Time
 * @property string $expirationTime
 *
 * @property string $vendorNumber
 *
 * Token type to be generated.
 * DEFAULT - default one-time token (will be expired after first request)
 * SHOP - shop token is used to redirect customer to the netlicensingShop(licenseeNumber is mandatory)
 * APIKEY - APIKey-token
 * @property string $tokenType
 *
 * @property string $licenseeNumber
 *
 * @method string getNumber($default = null)
 * @method boolean getActive($default = null)
 * @method \DateTime getExpirationTime($default = null)
 * @method string getVendorNumber($default = null)
 * @method string getTokenType($default = null)
 * @method string getLicenseeNumber($default = null)
 * @method string getShopURL($default = null)
 * @method string getSuccessURL($default = null)
 * @method string getSuccessURLTitle($default = null)
 * @method string getCancelURL($default = null)
 * @method string getCancelURLTitle($default = null)
 * @method string getApiKey($default = null)
 * @method Token setNumber(string $number)
 * @method Token setActive(boolean $active)
 * @method Token setExpirationTime(string|int|\DateTime $expirationTime)
 * @method Token setVendorNumber(string $vendorNumber)
 * @method Token setTokenType(string $tokenType)
 * @method Token setLicenseeNumber(string $tokenType)
 * @method Token setSuccessURL(string $successURL)
 * @method Token setSuccessURLTitle(string $successURLTitle)
 * @method Token setCancelURL(string $cancelURL)
 * @method Token setCancelURLTitle(string $cancelURLTitle)
 * @method Token setApiKey(string $apiKey)
 *
 * @package NetLicensing
 */
class Token extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean_string',
    ];
}