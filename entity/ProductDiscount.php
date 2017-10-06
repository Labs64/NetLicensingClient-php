<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * Class ProductDiscount
 *
 * @property int $totalPrice
 * @property string $currency
 * @property int $amountFix
 * @property int $amountPercent
 *
 *
 * @method int getTotalPrice($default = null)
 * @method string getCurrency($default = null)
 * @method int getAmountFix($default = null)
 * @method int getAmountPercent($default = null)
 * @method ProductDiscount setTotalPrice($totalPrice)
 * @method ProductDiscount setCurrency($currency)
 *
 *
 * @package NetLicensing
 */
class ProductDiscount extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'totalPrice' => 'float',
        'amountFix' => 'float',
        'amountPercent' => 'int',
    ];

    public function setAmountFix($amountFix)
    {
        $this->setProperty('amountFix', $amountFix)
            ->removeProperty('amountPercent');

        return $this;
    }

    public function setAmountPercent($amountPercent)
    {
        $this->setProperty('amountPercent', $amountPercent)
            ->removeProperty('amountFix');

        return $this;
    }

    public function __toString()
    {
        $totalPrice = $this->getTotalPrice();
        $currency = $this->getCurrency();

        $amount = '';

        if (!is_null($this->getAmountFix())) $amount = $this->getAmountFix();
        if (!is_null($this->getAmountPercent())) $amount = $this->getAmountPercent() . '%';

        return $totalPrice . ';' . $currency . ';' . $amount;
    }
}