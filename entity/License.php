<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * License entity used internally by NetLicensing.
 *
 * Properties visible via NetLicensing API:
 *
 * Unique number (across all products/licensees of a vendor) that identifies the license. Vendor can
 * assign this number when creating a license or let NetLicensing generate one. Read-only after corresponding creation
 * transaction status is set to closed.
 * @property string $number
 *
 * Name for the licensed item. Set from license template on creation, if not specified explicitly.
 * @property string $name
 *
 * If set to false, the license is disabled. License can be re-enabled, but as long as it is disabled,
 * the license is excluded from the validation process.
 * @property boolean $active
 *
 * price for the license. If >0, it must always be accompanied by the currency specification. Read-only,
 * set from license template on creation.
 * @property float $price
 *
 * specifies currency for the license price. Check data types to discover which currencies are
 * supported. Read-only, set from license template on creation.
 * @property string $currency
 *
 * If set to true, this license is not shown in NetLicensing Shop as purchased license. Set from license
 * template on creation, if not specified explicitly.
 * @property boolean $hidden
 *
 * @property string $startDate
 *
 * Arbitrary additional user properties of string type may be associated with each license. The name of user property
 * must not be equal to any of the fixed property names listed above and must be none of id, deleted, licenseeNumber,
 * licenseTemplateNumber.
 *
 * @method string getNumber($default = null)
 * @method string getName($default = null)
 * @method boolean getActive($default = null)
 * @method double getPrice($default = null)
 * @method string getCurrency($default = null)
 * @method boolean getHidden($default = null)
 * @method boolean getInUse($default = null)
 * @method string getParentFeature($default = null)
 * @method int getTimeVolume($default = null)
 * @method int getStartDate($default = null)
 * @method License setNumber($number)
 * @method License setName($name)
 * @method License setActive($active)
 * @method License setHidden($hidden)
 * @method License setParentFeature ($parentFeature)
 * @method License setTimeVolume($timeVolume)
 * @method License setStartDate($startDate)
 *
 *
 * @package NetLicensing
 */
class License extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean_string',
        'price' => 'double',
        'hidden' => 'boolean_string',
        'inUse' => 'boolean_string',
        'timeVolume' => 'int',
    ];

    protected $licensee;

    protected $licenseTemplate;

    protected $licenseTransactionJoins = [];

    public function getLicensee()
    {
        return $this->licensee;
    }

    public function setLicensee(Licensee $licensee)
    {
        $licenses = $licensee->getLicenses();
        $licenses[] = $this;

        $licensee->setLicenses($licenses);
        $this->licensee = $licensee;

        return $this;
    }

    public function getLicenseTemplate()
    {
        return $this->licenseTemplate;
    }

    public function setLicenseTemplate(LicenseTemplate $licenseTemplate)
    {
        $licenses = $licenseTemplate->getLicenses();
        $licenses[] = $this;

        $licenseTemplate->setLicenses($licenses);
        $this->licenseTemplate = $licenseTemplate;

        return $this;
    }

    public function getLicenseTransactionJoins()
    {
        return $this->licenseTransactionJoins;
    }

    public function setLicenseTransactionJoins(array $licenseTransactionJoins)
    {
        $this->licenseTransactionJoins = $licenseTransactionJoins;
        return $this;
    }
}