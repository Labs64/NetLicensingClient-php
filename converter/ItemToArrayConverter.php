<?php

namespace NetLicensing;

class ItemToArrayConverter
{
    public static function convert($item)
    {
        $array = [];

        if ($item) {
            $item = (array)$item;

            if (!empty($item['property'])) {
                foreach ($item['property'] as $property) {
                    $property = (array)$property;
                    $array[$property['name']] = $property['value'];
                }
            }

            if (!empty($item['list'])) {
                foreach ($item['list'] as $list) {
                    $list = (array)$list;
                    if (!empty($list['name'])) {
                        $array[$list['name']][] = self::convert($list);
                    }
                }
            }
        }

        return $array;
    }
}