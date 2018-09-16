<?php
class Values
{

    /**
     * @param $items
     * @param string $column
     * @return mixed
     * Todo to test if its work
     */
    public static function value($items)
    {
        foreach ($items as $item) {
            return $item->quantity;
        }
    }

    /**
     * @param $items
     * @return int
     */
    public static function countUsers($items)
    {
        $array = [];
        foreach ($items as $item) {
            array_push($array, $item->id);
        }
        return count($array);
    }


    /**
     * @param $items
     * @return int
     */
    public static function totalFurloughs($items)
    {
        $array = [];
        foreach ($items as $item) {
            array_push($array, $item->quantity);
        }
        return array_sum($array);
    }



    /**
     * @param $items
     * @return int
     */
    public static function totalAbsentees($items)
    {
        $array = [];
        foreach ($items as $item) {
            array_push($array, $item->quantity);
        }
        return array_sum($array);
    }


    /**
     * @param $items
     * @return array
     * @uses only on results() methods
     */
    public static function tables($items)
    {
        foreach ($items as $item) {
                $values = explode(',', $item->tables);
        }
        return $values;
    }


    /**
     * @param $items
     * @return array
     * @uses only on first() methods
     */
    public static function table($items)
    {
        return $values = explode(', ', $items->tables);
    }
}