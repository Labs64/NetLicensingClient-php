<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * NetLicensing Product entity.
 *
 * Properties visible via NetLicensing API:
 *
 * Unique number that identifies the product. Vendor can assign this number when creating a product or
 * let NetLicensing generate one. Read-only after creation of the first licensee for the product.
 * @property string $number
 *
 * If set to false, the product is disabled. No new licensees can be registered for the product,
 * existing licensees can not obtain new licenses.
 * @property boolean $active
 *
 * Product name. Together with the version identifies the product for the end customer.
 * @property string $name
 *
 * Product version. Convenience parameter, additional to the product name.
 * @property float $version
 *
 * If set to 'true', non-existing licensees will be created at first validation attempt.
 * @property boolean $licenseeAutoCreate
 *
 * Product description. Optional.
 * @property string $description
 *
 * Licensing information. Optional.
 * @property string $licensingInfo
 *
 * @property boolean $inUse
 *
 * Arbitrary additional user properties of string type may be associated with each product. The name of user property
 * must not be equal to any of the fixed property names listed above and must be none of id, deleted.
 *
 * @method string  getNumber($default = null)
 * @method boolean getActive($default = null)
 * @method string  getName($default = null)
 * @method string  getVersion($default = null)
 * @method boolean getLicenseeAutoCreate($default = null)
 * @method string  getDescription($default = null)
 * @method string  getLicensingInfo($default = null)
 * @method boolean getInUse($default = null)
 * @method Product setNumber(string $number)
 * @method Product setActive(boolean $active)
 * @method Product setName(string $name)
 * @method Product setVersion($version)
 * @method Product setLicenseeAutoCreate(boolean $licenseeAutoCreate)
 * @method Product setDescription(string $description)
 * @method Product setLicensingInfo($licensingInfo)
 *
 * @package NetLicensing\EntitiesNew
 */
class Product extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'version' => 'string',
        'licenseeAutoCreate' => 'boolean_string',
        'active' => 'boolean_string',
        'inUse' => 'boolean_string',
    ];

    protected array $productDiscounts = [];
    protected bool $productDiscountsTouched = false;

    protected array $productModules = [];

    protected array $licensees = [];

    public function getProductModules(): array
    {
        return $this->productModules;
    }

    public function setProductModules(array $productModules): Product
    {
        $this->productModules = $productModules;
        return $this;
    }

    public function getLicensees(): array
    {
        return $this->licensees;
    }

    public function setLicensees(array $licensees): Product
    {
        $this->licensees = $licensees;
        return $this;
    }

    public function getProductDiscounts(): array
    {
        return $this->productDiscounts;
    }

    public function setProductDiscounts($productDiscounts = []): Product
    {
        $discounts = [];


        if (!empty($productDiscounts)) {
            foreach ($productDiscounts as $productDiscount) {
                if (!($productDiscount instanceof ProductDiscount)) {
                    $productDiscount = new ProductDiscount($productDiscount);
                }
                $discounts[] = $productDiscount;
            }
        }

        $this->productDiscounts = $discounts;
        $this->productDiscountsTouched = true;

        return $this;
    }

    public function addDiscount(ProductDiscount $discount): Product
    {
        $this->productDiscounts[] = $discount;
        $this->productDiscountsTouched = true;

        return $this;
    }

    /**
     * @deprecated use setProductDiscounts or addDiscount instead
     * @param $discount
     * @return $this
     */
    protected function setDiscount($discount): Product
    {
        $this->setProductDiscounts([$discount]);
        return $this;
    }

    public function asPropertiesMap(): array
    {
        $map = $this->toArray();

        if ($this->productDiscounts) {
            $map['discount'] = [];
            foreach ($this->productDiscounts as $productDiscount) {
                $map['discount'][] = (string)$productDiscount;
            }
        }

        if (empty($map['discount']) && $this->productDiscountsTouched) {
            $map['discount'] = '';
        }

        return $map;
    }
}
