<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2016 Labs64 NetLicensing
 */
namespace NetLicensing;


class TransactionService extends BaseEntityService
{
    const SERVICE_URL = '/transaction';

    public static function connect(NetLicensingAPI $nlic_connect)
    {
        return new TransactionService($nlic_connect);
    }

    public function getList()
    {
        return $this->_list($this->nlic_connect);
    }

    public function get($number)
    {
        return $this->_get($number, $this->nlic_connect);
    }

    protected function _createEntity()
    {
        return new Transaction();
    }

    protected function _getServiceUrl()
    {
        return self::SERVICE_URL;
    }
}
