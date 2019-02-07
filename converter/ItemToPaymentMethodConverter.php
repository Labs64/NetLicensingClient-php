<?php

namespace NetLicensing;

class ItemToPaymentMethodConverter
{
    public static function convert($item)
    {
        return new PaymentMethod(ItemToArrayConverter::convert($item));
    }
}