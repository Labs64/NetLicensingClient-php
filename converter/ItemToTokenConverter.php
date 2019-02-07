<?php

namespace NetLicensing;

class ItemToTokenConverter
{
    public static function convert($item)
    {
        return new Token(ItemToArrayConverter::convert($item));
    }
}