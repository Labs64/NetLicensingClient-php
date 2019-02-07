<?php

namespace NetLicensing;

class ItemToLicenseeConverter
{
    public static function convert($item)
    {
        return new Licensee(ItemToArrayConverter::convert($item));
    }
}