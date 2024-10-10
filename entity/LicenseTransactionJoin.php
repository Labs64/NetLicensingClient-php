<?php
/**
 * @author    Labs64 <netlicensing@labs64.com>
 * @license   Apache-2.0
 * @link      https://netlicensing.io
 * @copyright 2017 Labs64 NetLicensing
 */
namespace NetLicensing;


class LicenseTransactionJoin
{
    protected ?Transaction $transaction = null;

    protected ?License $license = null;

    public function __construct(Transaction $transaction = null, License $license = null)
    {
        $this->transaction = $transaction;
        $this->license = $license;
    }

    public function setTransaction(Transaction $transaction): LicenseTransactionJoin
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setLicense(License $license): LicenseTransactionJoin
    {
        $this->license = $license;
        return $this;
    }

    public function getLicense(): ?License
    {
        return $this->license;
    }
}
