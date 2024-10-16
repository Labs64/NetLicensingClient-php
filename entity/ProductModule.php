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
 * Unique number (across all products of a vendor) that identifies the product module. Vendor can assign
 * this number when creating a product module or let NetLicensing generate one. Read-only after creation of the first
 * licensee for the product.
 * @property string $number
 *
 * If set to false, the product module is disabled. Licensees can not obtain any new licenses for this
 * product module.
 * @property boolean $active
 *
 * Product module name that is visible to the end customers in NetLicensing Shop.
 * @property string $name
 *
 * Licensing model applied to this product module. Defines what license templates can be
 * configured for the product module and how licenses for this product module are processed during validation.
 * @property string $licensingModel
 *
 * Maximum checkout validity (days). Mandatory for 'Floating' licensing model.
 * @property integer $maxCheckoutValidity
 *
 * Remaining time volume for yellow level. Mandatory for 'Rental' licensing model.
 * @property integer $yellowThreshold
 *
 * Remaining time volume for red level. Mandatory for 'Rental' licensing model.
 * @property integer $redThreshold
 *
 * License template. Mandatory for 'Try & Buy' licensing model. Supported types: "TIMEVOLUME", "FEATURE".
 * @property string $licenseTemplate
 *
 * @method string  getNumber($default = null)
 * @method boolean getActive($default = null)
 * @method string  getName($default = null)
 * @method string  getLicensingModel($default = null)
 * @method integer getMaxCheckoutValidity($default = null)
 * @method integer getYellowThreshold($default = null)
 * @method integer getRedThreshold($default = null)
 * @method boolean getInUse($default = null)
 * @method ProductModule setNumber(string $number)
 * @method ProductModule setActive(boolean $active)
 * @method ProductModule setName(string $name)
 * @method ProductModule setLicensingModel(string $licensingModel)
 * @method ProductModule setMaxCheckoutValidity(int $maxCheckoutValidity)
 * @method ProductModule setYellowThreshold(int $yellowThreshold)
 * @method ProductModule setRedThreshold(int $redThreshold)
 *
 *
 * @package NetLicensing
 */
class ProductModule extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'active' => 'boolean_string',
        'maxCheckoutValidity' => 'int',
        'yellowThreshold' => 'int',
        'redThreshold' => 'int',
        'inUse' => 'boolean_string',
    ];

    protected ?Product $product = null;

    protected array $licenseTemplates = [];

    public function setProduct(Product $product): ProductModule
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setLicenseTemplates(array $licenseTemplates): ProductModule
    {
        $this->licenseTemplates = $licenseTemplates;
        return $this;
    }

    public function getLicenseTemplates(): array
    {
        return $this->licenseTemplates;
    }
}
