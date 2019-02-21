<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      http://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */
namespace NetLicensing;


class LicenseTransactionJoin
{
    protected $transaction;

    protected $license;

    public function __construct(Transaction $transaction = null, License $license = null)
    {
        $this->transaction = $transaction;
        $this->license = $license;
    }

    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function setLicense(License $license)
    {
        $this->license = $license;
        return $this;
    }

    public function getLicense()
    {
        return $this->license;
    }
}