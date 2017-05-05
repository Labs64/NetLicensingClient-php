<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the PaymentMethodService Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Payment+Method+Services
 *
 * @package NetLicensing
 */
class PaymentMethodService
{
    const ENDPOINT_PATH = 'paymentmethod';

    /**
     *  Gets payment method by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Payment+Method+Services#PaymentMethodServices-Getpaymentmethod
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the payment method number
     * @param $number
     *
     * return the payment method
     * @return mixed|\NetLicensing\PaymentMethod|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->get($context, self::ENDPOINT_PATH . '/' . $number, [], PaymentMethod::class);
    }

    /**
     * Returns payment methods of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Payment+Method+Services#PaymentMethodServices-Paymentmethodslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string $filter
     *
     * array of payment method entities or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, self::ENDPOINT_PATH, $queryParams, PaymentMethod::class);
    }

    /**
     * Updates payment method properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Payment+Method+Services#PaymentMethodServices-Updatepaymentmethod
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * the payment method number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param \NetLicensing\PaymentMethod $paymentMethod
     *
     * return updated payment method.
     * @return mixed|\NetLicensing\PaymentMethod|null
     */
    public static function update(Context $context, $number, PaymentMethod $paymentMethod)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, self::ENDPOINT_PATH . '/' . $number, $paymentMethod->asPropertiesMap(), $paymentMethod);
    }
}