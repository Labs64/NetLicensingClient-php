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
 * PaymentMethod entity used internally by NetLicensing.
 *
 * @property string $number
 *
 * @property boolean $active
 *
 * @method string getNumber($default = null)
 * @method boolean getActive($default = null)
 * @method boolean setNumber(string $number)
 * @method boolean setActive(boolean $active)
 *
 *
 * @package NetLicensing
 */
class PaymentMethod extends BaseEntity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'active' => 'boolean_string',
    ];

    /**
     * @throws Exception
     */
    public function getPaypalSubject()
    {
        return $this->getProperty('paypal.subject');
    }

    public function setPaypalSubject($paypalSubject): PaymentMethod
    {
        $this->properties['paypal.subject'] = $paypalSubject;
        return $this;
    }
}
