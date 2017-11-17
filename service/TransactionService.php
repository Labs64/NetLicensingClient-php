<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */

namespace NetLicensing;

/**
 * PHP representation of the Transaction Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services
 *
 * Transaction is created each time change to  LicenseService licenses happens. For instance licenses are
 * obtained by a licensee, licenses disabled by vendor, licenses deleted, etc. Transaction is created no matter what
 * source has initiated the change to licenses: it can be either a direct purchase of licenses by a licensee via
 * NetLicensing Shop, or licenses can be given to a licensee by a vendor. Licenses can also be assigned implicitly by
 * NetLicensing if it is defined so by a license model (e.g. evaluation license may be given automatically). All these
 * events are reflected in transactions. Of all the transaction handling routines only read-only routines are exposed to
 * the public API, as transactions are only allowed to be created and modified by NetLicensing internally.
 *
 * @package NetLicensing
 */
class TransactionService
{
    /**
     * @deprecated
     * No longer used by internal code and not recommended, will be removed in future versions.
     * Use class Constants::TRANSACTION_ENDPOINT_PATH instead.
     */
    const ENDPOINT_PATH = 'transaction';

    /**
     * Creates new transaction object with given properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services#TransactionServices-Createtransaction
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * non-null properties will be taken for the new object, null properties will either stay null, or will
     * be set to a default value, depending on property.
     * @param Transaction $transaction
     *
     * return the newly created transaction object
     * @return mixed|\NetLicensing\Transaction|null
     */
    public static function create(Context $context, Transaction $transaction)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, Constants::TRANSACTION_ENDPOINT_PATH, $transaction->asPropertiesMap(), $transaction);
    }

    /**
     * Gets transaction by its number.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services#TransactionServices-Gettransaction
     *
     * determines the vendor on whose behalf the call is performed
     * @param Context $context
     *
     * the transaction number
     * @param $number
     *
     * return the transaction
     * @return mixed|\NetLicensing\Transaction|null
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->get($context, Constants::TRANSACTION_ENDPOINT_PATH . '/' . $number, [], Transaction::class);
    }

    /**
     * Returns all transactions of a vendor.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services#TransactionServices-Transactionslist
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * reserved for the future use, must be omitted / set to NULL
     * @param string $filter
     *
     * array of transaction entities or empty array if nothing found.
     * @return array
     */
    public static function getList(Context $context, $filter = null)
    {
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        $queryParams = (!is_null($filter)) ? ['filter' => $filter] : [];

        return NetLicensingService::getInstance()->getList($context, Constants::TRANSACTION_ENDPOINT_PATH, $queryParams, Transaction::class);
    }

    /**
     * Updates transaction properties.See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services#TransactionServices-Updatetransaction
     *
     * determines the vendor on whose behalf the call is performed
     * @param \NetLicensing\Context $context
     *
     * transaction number
     * @param string $number
     *
     * non-null properties will be updated to the provided values, null properties will stay unchanged.
     * @param \NetLicensing\Transaction $transaction
     *
     * return updated transaction.
     * @return mixed|\NetLicensing\Transaction|null
     */
    public static function update(Context $context, $number, Transaction $transaction)
    {
        CheckUtils::paramNotEmpty($number, 'number');

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        return NetLicensingService::getInstance()->post($context, Constants::TRANSACTION_ENDPOINT_PATH . '/' . $number, $transaction->asPropertiesMap(), $transaction);
    }
}