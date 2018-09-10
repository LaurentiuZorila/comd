<?php
class Math
{
    /**
     * @param $items
     * @return float|int
     */
    public function average($items)
    {
        $values = [];
        foreach ($items as $item) {
            array_push($values, $item->quantity);
        }
        $countItems = count($values);
        $sumItems = array_sum($values);

        $average = $sumItems / $countItems;
        return number_format($average, 2);
    }


    /**
     * @param $items
     * @param int $shift
     * @return float|int
     */
    public static function averageAbsentees($items, $shift = 8)
    {
        $values = [];

        foreach ($items as $item) {
            array_push($values, $item->quantity);
        }

        $sumItems = array_sum($values);
        $hours = 164;
        $missingHours = $sumItems * $shift;
        $average = $missingHours / $hours * 100;

        return number_format($average, 2);
    }


    /**
     * @param $items
     * @param int $shift
     * @return string
     */
    public static function averageFurlought($items, $shift = 8)
    {
        $values = [];

        foreach ($items as $item) {
            array_push($values, $item->quantity);
        }

        if ($shift == 8) {
            $furlought = 21;
        } elseif ($shift = 6) {
            $furlought = 25;
        }

        $average = array_sum($values) * 100 / $furlought;
        return number_format($average, 2);

    }


    public static function countItems($items)
    {
        $array = [];
        foreach ($items as $item) {
            array_push($array, $item->quantity);
        }
        return count($array);
    }

}