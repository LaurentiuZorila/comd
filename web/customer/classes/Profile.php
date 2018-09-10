<?php
/**
 * Class Profile
 */

class Profile
{

    /**
     * @param int $maxYears
     * @param int $startYear
     * @return array
     */
    public static function getYearsList($maxYears = 3, $startYear = 2018)
    {
        $range = range($startYear, date('Y') + $maxYears);
        return array_combine($range, $range);
    }


    /**
     * @return array
     */
    public static function getMonthsList()
    {
        return [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
    }



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


    public static function name($items)
    {
        foreach ($items as $item) {
            return $item->name;
        }
    }

    /**
     * @param $string
     * @return string
     */
    public static function makeAvatar($string)
    {
        $string = explode(' ', $string);
        $string = array_reverse($string);
        $value = '';
        foreach ($string as $item) {
            $value .= substr($item, 0, 1);
        }
        return $value;
    }


}