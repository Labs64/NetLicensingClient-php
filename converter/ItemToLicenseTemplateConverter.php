<?php

namespace NetLicensing;

class ItemToLicenseTemplateConverter
{
    public static function convert($item)
    {
        return new LicenseTemplate(ItemToArrayConverter::convert($item));
    }
}