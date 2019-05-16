<?php

/**
 * Class Js
 */
class Js
{
    /**
     * @param $items
     * @param $column
     * @return array
     */
    public static function chartLabel($items, $column)
    {
        foreach ($items as $item) {
            $values[] = $item->$column;
        }
        return $values;
    }


    /**
     * @param $items
     * @param $column
     * @return string
     */
    public static function chartValues($items, $column)
    {
        foreach ($items as $item) {
            $values[] = $item->$column;
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
     * @param array $items
     * @param array $params
     * @return string
     */
    public static function key(array $items,array $params)
    {
        $lang = Session::get('lang');

        if (!empty($items)) {
            foreach ($items as $key => $value) {
                if (!empty($params)) {
                    $keys[] = Translate::t($key, $params);
                } else {
                    $keys[] = Translate::t($key);
                }
            }
            return self::toJson($keys);
        }
    }


    /**
     * @param array $items
     * @param array $rules
     * @return string
     */
    public static function values(array $items, array $rules = [])
    {
        if (!empty($items)) {
            foreach ($items as $key => $value) {
                if (!empty($rules)) {
                    foreach ($rules as $rule) {
                        $values[] = $rule($value);
                    }
                }
                $values[] = $value;
            }
            return !empty($values) ? implode(', ', $values) : '';
        }
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