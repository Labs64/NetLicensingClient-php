<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

use Exception;

/**
 * Transaction entity used internally by NetLicensing.
 *
 * Properties visible via NetLicensing API:
 *
 * Unique number (across all products of a vendor) that identifies the transaction. This number is
 * always generated by NetLicensing.
 * @property string $number
 *
 * always true for transactions
 * @property boolean $active
 *
 * Status of transaction. "CANCELLED", "CLOSED", "PENDING".
 * @property string $status
 *
 * "SHOP". AUTO transaction for internal use only.
 * @property string $source
 *
 * grand total for SHOP transaction (see source).
 * @property float $grandTotal
 *
 * discount for SHOP transaction (see source).
 * @property float $discount
 *
 * specifies currency for money fields (grandTotal and discount). Check data types to discover which
 * @property string $currency
 *
 * Date created. Optional.
 * @property string $dateCreated
 *
 * Date closed. Optional.
 * @property string $dateClosed
 *
 * @method string getNumber($default = null)
 * @method string getName($default = null)
 * @method boolean getActive($default = null)
 * @method string getStatus($default = null)
 * @method string getSource($default = null)
 * @method float getGrandTotal($default = null)
 * @method float getDiscount($default = null)
 * @method string getCurrency($default = null)
 * @method Transaction setNumber($number)
 * @method Transaction setName($name)
 * @method Transaction setStatus($status)
 * @method Transaction setSource($source)
 * @method Transaction setGrandTotal($grandTotal)
 * @method Transaction setDiscount($discount)
 * @method Transaction setCurrency($currency)
 *
 * @package NetLicensing
 */
class Transaction extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'active' => 'boolean_string',
        'grandTotal' => 'float',
        'discount' => 'float',
    ];

    protected array $licenseTransactionJoins = [];

    public function __construct(array $properties = [], $exists = false)
    {
        $properties['active'] = true;

        parent::__construct($properties, $exists);
    }

    protected function setActive(): Transaction
    {
        $this->setProperty('active', true);
        return $this;
    }

    public function setDateCreated($dateCreated): Transaction
    {
        return $this->setProperty(Constants::TRANSACTION_DATE_CREATED, $dateCreated);
    }

    /**
     * @throws Exception
     */
    public function getDateCreated($default = null)
    {
        return $this->getProperty(Constants::TRANSACTION_DATE_CREATED, $default);
    }

    public function setDateClosed($dateClosed): Transaction
    {
        return $this->setProperty(Constants::TRANSACTION_DATE_CLOSED, $dateClosed);
    }

    /**
     * @throws Exception
     */
    public function getDateClosed($default = null)
    {
        return $this->getProperty(Constants::TRANSACTION_DATE_CLOSED, $default);
    }

    public function getLicenseTransactionJoins(): array
    {
        return $this->licenseTransactionJoins;
    }

    public function setLicenseTransactionJoins($licenseTransactionJoins = []): Transaction
    {
        $this->licenseTransactionJoins = $licenseTransactionJoins;
        return $this;
    }
}
