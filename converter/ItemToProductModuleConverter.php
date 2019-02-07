<?php

namespace NetLicensing;

class ItemToProductModuleConverter
{
    public static function convert($item)
    {
        return new ProductModule(ItemToArrayConverter::convert($item));
    }
}