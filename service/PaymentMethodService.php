<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the PaymentMethodService Service. See NetLicensingAPI for details:
 * https://netlicensing.io/wiki/payment-method-services
 *
 * @package NetLicensing
 */
class PaymentMethodService
{
    /**
     * Gets payment method by its number.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/payment-method-services#get-payment-method
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the payment method number
     * @param $number
     *
     * return the payment method
     * @return PaymentMethod|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function get(Context $context, $number): ?PaymentMethod
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::PAYMENT_METHOD_ENDPOINT_PATH . '/' . $number);

        $paymentMethod = null;

        if (!empty($response->items->item[0])) {
            $paymentMethod = ItemToPaymentMethodConverter::convert($response->items->item[0]);
            $paymentMethod->exists = true;
        }

        return $paymentMethod;
    }

    /**
     * Returns payment methods of a vendor.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/payment-method-services#payment-methods-list
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string|null $filter
     *
     * array of payment method entities or empty array if nothing found.
     * @return Page
     * @throws RestException
     */
    public static function getList(Context $context, ?string $filter = null): Page
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::PAYMENT_METHOD_ENDPOINT_PATH, $queryParams);

        $paymentMethods = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $paymentMethod = ItemToPaymentMethodConverter::convert($item);
                $paymentMethod->exists = true;

                $paymentMethods[] = $paymentMethod;
            }
        }

        return new Page($paymentMethods, $pageNumber, $itemsNumber, $totalPages, $totalItems);
    }

    /**
     * Updates payment method properties.See NetLicensingAPI for details:
     * https://netlicensing.io/wiki/payment-method-services#update-payment-method
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the payment method number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param PaymentMethod $paymentMethod
     *
     * return updated payment method.
     * @return PaymentMethod|null
     * @throws MalformedArgumentsException
     * @throws RestException
     */
    public static function update(Context $context, string $number, PaymentMethod $paymentMethod): ?PaymentMethod
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::PAYMENT_METHOD_ENDPOINT_PATH . '/' . $number, $paymentMethod->asPropertiesMap());

        $paymentMethod = null;

        if (!empty($response->items->item[0])) {
            $paymentMethod = ItemToPaymentMethodConverter::convert($response->items->item[0]);
            $paymentMethod->exists = true;
        }

        return $paymentMethod;
    }
}
