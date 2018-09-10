<?php

/**
 * Class Js
 */
class Js
{
    /**
     * @param $obj
     * @return array with names
     */
    public static function chartLabel($items)
    {
        $values = [];
        foreach ($items as $item) {
            array_push($values, $item->name);
        }
        return $values;
    }


    /**
     * @param $items
     * @return string with quantity column
     */
    public static function chartValues ($items)
    {
        $values = [];
        foreach ($items as $item) {
            array_push($values, $item->quantity);
        }
        return $values = implode(',', $values);
    }

    /**
     * @param $items
     * @return array with months present in db
     */
    public static function chartMonths($items)
    {
        $date = [];
        $months = [];
        foreach ($items as $item) {
            array_push($date, $item->month);
        }

        for ($i = min($date); $i <= count($date); $i++) {
            array_push($months,Profile::getMonthsList()[$i]);
        }
        return $months;
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
     * @param $obj
     * @return array with sorted months
     */
    public static function chartMonthsSorted($obj)
    {
        $date = [];
        $months = [];
        foreach ($obj as $key => $item) {
            array_push($date, $key);
        }

        for ($i = min($date); $i <= count($date); $i++) {
            array_push($months,Profile::getMonthsList()[$i]);
        }
        return $months;
    }


    /**
     * @param $obj
     * @return string delimited by ,
     */
    public static function chartValuesSorted($obj)
    {
        $values = [];
        foreach ($obj as $key => $item) {
            array_push($values, $item);
        }
        return $values = implode(',', $values);
    }


    /**
     * @param $obj
     * @return array
     */
    public static function chartLabelSorted($obj)
    {
        $values = [];
        foreach ($obj as $key => $item) {
            array_push($values, $item);
        }
        return $values;
    }

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
        return self::toJson($keys);
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
        return implode(', ', $values);
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
     * @param $items
     * @return string
     * return Json
     */
    public static function toJson($items)
    {
        return json_encode($items);
    }
}