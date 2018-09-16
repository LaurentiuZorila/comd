<?php

/**
 * Class Js
 */
class Js
{
    /**
     * @param $array
     * @return string
     */
    public static function key($items = [])
    {
        $keys = [];
        foreach ($items as $key => $value) {
            $keys[] = $key;
        }
        return $keys;
    }


    /**
     * @param $array
     * @return mixed
     */
    public static function values($items = [])
    {
        $values = [];
        foreach ($items as $key => $value) {
            $values[] = $value;
        }
        return $values;
    }


    /**
     * @return bool
     */
    public static function ifExistsValues($items = [])
    {
        $values = explode(', ', self::values($items));
        if (array_sum($values) > 0) {
            return true;
        }
        return false;
    }


    /**
     * @param array $item
     * @return false|string
     */
    public static function toJson(array $item)
    {
        return json_encode($item);
    }


    /**
     * @param array $item
     * @return string
     */
    public static function toString(array $item)
    {
        return implode(', ', $item);
    }

}