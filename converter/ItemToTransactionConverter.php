<?php

namespace NetLicensing;

class ItemToTransactionConverter
{
    public static function convert($item)
    {
        $array = ItemToArrayConverter::convert($item);

        $licenseTransactionJoins = !empty($array['licenseTransactionJoin']) ? $array['licenseTransactionJoin'] : [];

        unset($array['licenseTransactionJoin']);

        $transaction = new Transaction($array);

        if ($licenseTransactionJoins) {
            $joins = [];

            foreach ($licenseTransactionJoins as $licenseTransactionJoin) {
                $join = new LicenseTransactionJoin();
                $join->setLicense(new License(['number' => $licenseTransactionJoin[Constants::LICENSE_NUMBER]]));
                $join->setTransaction(new Transaction(['number' => $licenseTransactionJoin[Constants::TRANSACTION_NUMBER]]));

                $joins[] = $join;
            }

            $transaction->setLicenseTransactionJoins($joins);
        }

        return $transaction;
    }
}