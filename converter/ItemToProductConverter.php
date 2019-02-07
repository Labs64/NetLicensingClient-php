<?php

namespace NetLicensing;

class ItemToProductConverter
{
    public static function convert($item)
    {
        $array = ItemToArrayConverter::convert($item);

        $discounts = !empty($array['discount']) ? $array['discount'] : [];

        unset($array['discount']);

        $product = new Product($array);
        $product->setProductDiscounts($discounts);

        return $product;
    }
}