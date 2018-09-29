<?php

/**
 * Class Js
 */
class Js
{
    /**
     * @param $obj
     * @return array with names
     * @uses on object
     */
    public static function chartLabel($items, $column)
    {
        $values = [];
        foreach ($items as $item) {
            array_push($values, $item->$column);
        }
        return $values;
    }


    /**
     * @param $items
     * @return string with quantity column
     * @uses on object
     */
    public static function chartValues($items, $column)
    {
        $values = [];
        foreach ($items as $item) {
            array_push($values, $item->$column);
        }
        return $values = implode(',', $values);
    }


    /**
     * @param $keyItems
     * @param $valueItems
     * @return array
     * Sort associative array ordered by key
     */
    public static function sortResults($keyItems, $valueItems)
    {
        $keys = [];
        $values = [];

        foreach ($keyItems as $keyItem) {
            array_push($keys, $keyItem->month);
        }
        foreach ($valueItems as $valueItem) {
            array_push($values, $valueItem->quantity);
        }

        $results = array_combine($keys, $values);

        ksort($results, SORT_NUMERIC);
        return $results;
    }


    /**
     * @param $array
     * @return string
     * @uses on array
     */
    public static function key($items = [])
    {
        $keys = [];
        foreach ($items as $key => $value) {
            $keys[] = strtoupper($key);
        }
        return self::toJson($keys);
    }


    /**
     * @param $array
     * @return mixed
     * @uses on array
     */
    public static function values($items = [], $upper = false)
    {
        $values = [];
        foreach ($items as $key => $value) {
            if ($upper) {
                $values[] = strtoupper($value);
            }
            $values[] = $value;
        }
        return implode(', ', $values);
    }


    /**
     * @param $items
     * @return string
     * return Json
     */
    public static function toJson($items)
    {
        return json_encode($items);
    }
}