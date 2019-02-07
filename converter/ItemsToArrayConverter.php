<?php

namespace NetLicensing;

class ItemsToArrayConverter
{
    public static function convert($items)
    {
        $array = [];

        if ($items) {
            $items = (array)$items;

            foreach ($items as $item) {
                $item = (array)$item;

                if (!empty($item['type'])) {
                    $array[$item['type']][] = ItemToArrayConverter::convert($item);
                }
            }
        }

        return $array;
    }
}