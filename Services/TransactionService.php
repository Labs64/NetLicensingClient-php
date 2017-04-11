<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;

/**
 * PHP representation of the Transaction Service. See NetLicensingAPI for details:
 * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services
 *
 * Class TransactionService
 * @package NetLicensing
 */
class TransactionService extends BaseEntityService
{
    const SERVICE_URL = '/transaction';

    /**
     * @param NetLicensingAPI $nlic_connect
     * @return TransactionService
     */
    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new TransactionService($nlic_connect);
    }

    /**
     * Returns all transactions of a vendor. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services#TransactionServices-Transactionslist
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    /**
     * Gets transaction by its number. See NetLicensingAPI for details:
     * https://www.labs64.de/confluence/display/NLICPUB/Transaction+Services#TransactionServices-Gettransaction
     *
     * @param $number
     * @return bool
     */
    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    /**
     * @return Transaction
     */
    protected function _createEntity()
    {
        return new Transaction();
    }

    /**
     * @return string
     */
    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
}
