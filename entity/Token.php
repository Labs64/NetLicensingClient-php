<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
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
 * @method string getExpirationTime($default = null)
 * @method string getVendorNumber($default = null)
 * @method string getTokenType($default = null)
 * @method string getLicenseeNumber($default = null)
 * @method string getShopURL($default = null)
 * @method string getSuccessURL($default = null)
 * @method string getSuccessURLTitle($default = null)
 * @method string getCancelURL($default = null)
 * @method string getCancelURLTitle($default = null)
 * @method Token setNumber($number)
 * @method Token setActive($active)
 * @method Token setExpirationTime($expirationTime)
 * @method Token setVendorNumber($vendorNumber)
 * @method Token setTokenType($tokenType)
 * @method Token setLicenseeNumber($tokenType)
 * @method Token setSuccessURL($successURL)
 * @method Token setSuccessURLTitle($successURLTitle)
 * @method Token setCancelURL($cancelURL)
 * @method Token setCancelURLTitle($cancelURLTitle)
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