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
     * @return Transaction|null
     * @throws \ErrorException
     * @throws RestException
     */
    public static function create(Context $context, Transaction $transaction)
    {
        $response = NetLicensingService::getInstance()
            ->post($context, Constants::TRANSACTION_ENDPOINT_PATH, $transaction->asPropertiesMap());

        $createdTransaction = null;

        if (!empty($response->items->item[0])) {
            $createdTransaction = ItemToTransactionConverter::convert($response->items->item[0]);
            $createdTransaction->exists = true;
        }

        return $createdTransaction;
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
     * @return Transaction|null
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function get(Context $context, $number)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::TRANSACTION_ENDPOINT_PATH . '/' . $number);

        $transaction = null;

        if (!empty($response->items->item[0])) {
            $transaction = ItemToTransactionConverter::convert($response->items->item[0]);
            $transaction->exists = true;
        }

        return $transaction;
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
     * @return Page
     * @throws \ErrorException
     * @throws RestException
     */
    public static function getList(Context $context, $filter = null)
    {
        $queryParams = (!is_null($filter)) ? [Constants::FILTER => $filter] : [];

        $response = NetLicensingService::getInstance()
            ->get($context, Constants::TRANSACTION_ENDPOINT_PATH, $queryParams);

        $transactions = [];
        $pageNumber = !empty($response->items->pagenumber) ? $response->items->pagenumber : 0;
        $itemsNumber = !empty($response->items->itemsnumber) ? $response->items->itemsnumber : 0;
        $totalPages = !empty($response->items->totalpages) ? $response->items->totalpages : 0;
        $totalItems = !empty($response->items->totalitems) ? $response->items->totalitems : 0;

        if (!empty($response->items->item)) {
            foreach ($response->items->item as $item) {
                $transaction = ItemToTransactionConverter::convert($item);
                $transaction->exists = true;

                $transactions[] = $transaction;
            }
        }

        return new Page($transactions, $pageNumber, $itemsNumber, $totalPages, $totalItems);
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
     * @return Transaction|null
     * @throws MalformedArgumentsException
     * @throws \ErrorException
     * @throws RestException
     */
    public static function update(Context $context, $number, Transaction $transaction)
    {
        CheckUtils::paramNotEmpty($number, Constants::NUMBER);

        $response = NetLicensingService::getInstance()
            ->post($context, Constants::TRANSACTION_ENDPOINT_PATH . '/' . $number, $transaction->asPropertiesMap());

        $updatedTransaction = null;

        if (!empty($response->items->item[0])) {
            $updatedTransaction = ItemToTransactionConverter::convert($response->items->item[0]);
            $updatedTransaction->exists = true;
        }

        return $updatedTransaction;
      }
}