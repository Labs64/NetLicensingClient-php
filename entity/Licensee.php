<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * Licensee entity used internally by NetLicensing.
 *
 * Properties visible via NetLicensing API:
 *
 * Unique number (across all products of a vendor) that identifies the licensee. Vendor can assign this
 * number when creating a licensee or let NetLicensing generate one. Read-only after creation of the first license for
 * the licensee.
 * @property string $number
 *
 * Licensee name.
 * @property string $name
 *
 * If set to false, the licensee is disabled. Licensee can not obtain new licenses, and validation is
 * disabled (tbd).
 * @property boolean $active
 *
 * Licensee Secret for licensee
 * @property @deprecated string $licenseeSecret
 *
 * Mark licensee for transfer.
 * @property boolean $markedForTransfer
 *
 * Arbitrary additional user properties of string type may be associated with each licensee. The name of user property
 * must not be equal to any of the fixed property names listed above and must be none of id, deleted, productNumber
 *
 * @method string getNumber($default = null)
 * @method string getName($default = null)
 * @method string getActive($default = null)
 * @method @deprecated string getLicenseeSecret($default = null)
 * @method boolean getMarkedForTransfer($default = null)
 * @method boolean getInUse($default = null)
 * @method Licensee setNumber(string $number)
 * @method Licensee setName(string $name)
 * @method Licensee setActive(boolean $active)
 * @method @deprecated Licensee setLicenseeSecret($licenseeSecret)
 * @method Licensee setMarkedForTransfer(boolean $markedForTransfer)
 *
 * @package NetLicensing
 */
class Licensee extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'active' => 'boolean_string',
        'markedForTransfer' => 'boolean_string',
        'inUse' => 'boolean_string',
    ];

    protected ?Product $product = null;

    protected array $licenses = [];

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): Licensee
    {
        $licensees = $product->getLicensees();

        $licensees[] = $this;
        $product->setLicensees($licensees);

        $this->product = $product;

        return $this;
    }

    public function getLicenses(): array
    {
        return $this->licenses;
    }

    public function setLicenses(array $licenses): Licensee
    {
        $this->licenses = $licenses;
        return $this;
    }
}
