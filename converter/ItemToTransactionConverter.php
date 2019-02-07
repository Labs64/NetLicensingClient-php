<?php

namespace NetLicensing;

class ItemToTransactionConverter
{
    public static function convert($item)
    {
        return new Transaction(ItemToArrayConverter::convert($item));
    }
}