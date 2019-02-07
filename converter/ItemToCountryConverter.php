<?php

namespace NetLicensing;

class ItemToCountryConverter
{
    public static function convert($item)
    {
        return new Country(ItemToArrayConverter::convert($item));
    }
}