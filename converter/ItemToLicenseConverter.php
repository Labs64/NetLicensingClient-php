<?php

namespace NetLicensing;

class ItemToLicenseConverter
{
    public static function convert($item)
    {
        return new License(ItemToArrayConverter::convert($item));
    }
}