<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * Class ProductDiscount
 *
 * @property double $totalPrice
 * @property string $currency
 * @property double $amountFix
 * @property double $amountPercent
 *
 *
 * @method double getTotalPrice($default = null)
 * @method string getCurrency($default = null)
 * @method double getAmountFix($default = null)
 * @method double getAmountPercent($default = null)
 * @method ProductDiscount setTotalPrice($totalPrice)
 * @method ProductDiscount setCurrency(string $currency)
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
    protected array $casts = [
        'totalPrice' => 'double',
        'amountFix' => 'double',
        'amountPercent' => 'double',
    ];

    protected ?Product $product = null;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): ProductDiscount
    {
        $this->product = $product;
        return $this;
    }

    public function setAmountFix($amountFix): ProductDiscount
    {
        $this->setProperty('amountFix', $amountFix)
            ->removeProperty('amountPercent');

        return $this;
    }

    public function setAmountPercent($amountPercent): ProductDiscount
    {
        $this->setProperty('amountPercent', $amountPercent)
            ->removeProperty('amountFix');

        return $this;
    }

    public function __toString(): string
    {
        $totalPrice = $this->getTotalPrice();
        $currency = $this->getCurrency();

        $amount = '';

        if (!is_null($this->getAmountFix())) $amount = $this->getAmountFix();
        if (!is_null($this->getAmountPercent())) $amount = $this->getAmountPercent() . '%';

        return $totalPrice . ';' . $currency . ';' . $amount;
    }
}
