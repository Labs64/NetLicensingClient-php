<?php

namespace NetLicensing;

class ItemToTransactionConverter
{
    public static function convert($item)
    {
        $array = ItemToArrayConverter::convert($item);

        $licenseTransactionJoins = !empty($array['licenseTransactionJoins']) ? $array['licenseTransactionJoins'] : [];

        unset($array['licenseTransactionJoins']);

        $transaction = new Transaction($array);
        $transaction->setLicenseTransactionJoins($licenseTransactionJoins);

        return $transaction;
    }
}