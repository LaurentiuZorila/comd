<?php
class Values
{
    /**
     * @param $items
     * @param string $column
     * @return mixed
     * @uses use only on results() method
     */
    public static function columnValues ($items, $column)
    {
        foreach ($items as $item) {
            return $item->$column;
        }
    }


    /**
     * @param $items
     * @param string $column
     * @return mixed
     * @uses use only on first() method
     */
    public static function columnValue ($items)
    {
        foreach ($items as $item) {
            return $item;
        }
    }


    /**
     * @return bool
     */
    public static function checkValues ($items)
    {
        foreach ($items as $k => $v) {
            if (empty(trim($v))) {
                unset($items[$k]);
            }
        }
        if (count($items) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $items
     * @return int
     */
    public static function countAll ($items)
    {
        $array = [];
        foreach ($items as $item) {
            array_push($array, $item->id);
        }
        return count($array);
    }


    public static function sumAll ($items, $column)
    {
        $array = [];
        foreach ($items as $item) {
            $array[] = $item->$column;
        }
        return array_sum($array);
    }


    /**
     * @param $items
     * @return array
     */
    public static function toArray ($items)
    {
        return $values = explode(',', $items);
    }

}