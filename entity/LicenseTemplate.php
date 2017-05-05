<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * License template entity used internally by NetLicensing.
 *
 * Properties visible via NetLicensing API:
 *
 * Unique number (across all products of a vendor) that identifies the license template. Vendor can
 * assign this number when creating a license template or let NetLicensing generate one. Read-only after creation of the
 * first license from this license template.
 * @property string $number
 *
 * If set to false, the license template is disabled. Licensee can not obtain any new licenses off this
 * license template.
 * @property boolean $active
 *
 * Name for the licensed item.
 * @property string $name
 *
 * Type of licenses created from this license template. Supported types: "FEATURE", "TIMEVOLUME",
 * "FLOATING", "QUANTITY"
 * @property string $licenseType
 *
 * Price for the license. If >0, it must always be accompanied by the currency specification.
 * @property double $price
 *
 * Specifies currency for the license price. Check data types to discover which currencies are
 * supported.
 * @property string $currency
 *
 * If set to true, every new licensee automatically gets one license out of this license template on
 * creation. Automatic licenses must have their price set to 0.
 * @property boolean $automatic
 *
 * If set to true, this license template is not shown in NetLicensing Shop as offered for purchase.
 * @property boolean $hidden
 *
 * If set to true, licenses from this license template are not visible to the end customer, but
 * participate in validation.
 * @property boolean $hideLicenses
 *
 * Mandatory for 'TIMEVOLUME' license type.
 * @property integer $timeVolume
 *
 * Mandatory for 'FLOATING' license type.
 * @property integer $maxSessions
 *
 * Mandatory for 'QUANTITY' license type.
 * @property integer $quantity
 *
 *
 * @method string  getNumber($default = null)
 * @method boolean getActive($default = null)
 * @method string  getName($default = null)
 * @method string  getLicenseType($default = null)
 * @method double  getPrice($default = null)
 * @method string  getCurrency($default = null)
 * @method boolean getAutomatic($default = null)
 * @method boolean getHidden($default = null)
 * @method boolean getHideLicenses($default = null)
 * @method integer getTimeVolume($default = null)
 * @method integer getMaxSessions($default = null)
 * @method integer getQuantity($default = null)
 * @method boolean getInUse($default = null)
 * @method LicenseTemplate setNumber($number)
 * @method LicenseTemplate setActive($active)
 * @method LicenseTemplate setName($name)
 * @method LicenseTemplate setLicenseType($licenseType)
 * @method LicenseTemplate setPrice($price)
 * @method LicenseTemplate setCurrency($currency)
 * @method LicenseTemplate setAutomatic($automatic)
 * @method LicenseTemplate setHidden($hidden)
 * @method LicenseTemplate setHideLicenses($hideLicenses)
 * @method LicenseTemplate setTimeVolume($timeVolume)
 * @method LicenseTemplate setMaxSessions($maxSessions)
 * @method LicenseTemplate setQuantity($quantity)
 *
 * @package NetLicensing
 */
class LicenseTemplate extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'double',
        'active' => 'boolean_string',
        'inUse' => 'boolean_string',
        'automatic' => 'boolean_string',
        'hidden' => 'boolean_string',
        'hideLicenses' => 'boolean_string',
        'timeVolume' => 'int',
        'maxSessions' => 'int',
        'quantity' => 'int',
    ];
}