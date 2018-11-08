<?php
class Common
{
    /**
     *  Allowed characters for profile setup
     */
    const ALLOWED_CHARACTERS = ['<', '>'];


    /**
     * @return bool
     */
    public static function checkValues($items)
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
     * @param $column
     * @return array
     */
    public static function objToArray ($items, $column)
    {
        foreach ($items as $item) {
            $values = explode(',', $item->$column);
        }
        return $values;
    }


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
     * @param $num
     * @return string
     *
     */
    public static function numberToMonth($num, $language)
    {
        switch ($language) {
            case 'ro':
                $format = new IntlDateFormatter('ro_RO', IntlDateFormatter::NONE,
                    IntlDateFormatter::NONE, NULL, NULL, "MMMM");
                break;
            case 'en':
                $format = new IntlDateFormatter('en_US', IntlDateFormatter::NONE,
                    IntlDateFormatter::NONE, NULL, NULL, "MMMM");
                break;
            case 'it':
                $format = new IntlDateFormatter('it_IT', IntlDateFormatter::NONE,
                    IntlDateFormatter::NONE, NULL, NULL, "MMMM");
                break;
        }
        return ucfirst(datefmt_format($format, mktime(0, 0, 0, $num, 10)));
    }


    /**
     * @return array
     */
    public static function getMonths($language) :array
    {
        for ($x = 1; $x < 13; $x++) {
            switch ($language) {
                case 'ro':
                    $format = new IntlDateFormatter('ro_RO', IntlDateFormatter::NONE,
                        IntlDateFormatter::NONE, NULL, NULL, "MMMM");
                    break;
                case 'en':
                    $format = new IntlDateFormatter('en_US', IntlDateFormatter::NONE,
                        IntlDateFormatter::NONE, NULL, NULL, "MMMM");
                    break;
                case 'it':
                    $format = new IntlDateFormatter('it_IT', IntlDateFormatter::NONE,
                        IntlDateFormatter::NONE, NULL, NULL, "MMMM");
                    break;
            }
            $months[$x] = ucfirst(datefmt_format($format, mktime(0, 0, 0, $x, 10)));
        }
        return $months;
    }


    /**
     * @param $name
     * @return string
     */
    public static function makeAvatar($name)
    {
        $name = explode(' ', $name);
        $name = array_reverse($name);
        $value = '';
        foreach ($name as $item) {
            $value .= substr($item, 0, 1);
        }
        return $value;
    }


    /**
     * @param $item
     * @param int $char
     * @return string
     */
    public static function number($item, $char = 2)
    {
        return number_format($item, $char);
    }


    /**
     * @param $string
     * @return bool|string
     * @uses If last character is alphabetic
     */
    public static function checkLastCharacter($string)
    {
        if (ctype_alpha(substr($string, strlen($string) -1))) {
            return $string;
        } elseif (in_array(substr($string, strlen($string) -1), self::ALLOWED_CHARACTERS)) {
            return $string;
        } elseif (is_numeric(substr($string, strlen($string) -1))) {
            return $string;
        } else {
            return substr($string, 0, strlen($string) -1);
        }
    }


    /**
     * @param $key
     * @param $value
     * @return array
     */
    public static function assocArray($key, $value)
    {
        if (count($key) === count($value)) {
           return array_combine($key, $value);
        }
    }


    /**
     * @param $array
     * @return false|string
     */
    public static function toJson(array $array)
    {
        return json_encode($array);
    }


    /**
     * @param $json
     * @return mixed
     */
    public static function toArray($json)
    {
        return (array)json_decode($json);
    }


    /**
     * @param $item
     * @return array|string
     */
    public static function valuesToInsert($item)
    {
        if (is_array($item)) {
            $data = array_map('trim', $item);
            $data = array_map('strtolower', $item);
            return $data;
        } else {
            $data = strtolower(trim($item));
        }
        return $data;
    }


    /**
     * @param array $params
     * @return mixed
     */
    public static function dbValues($params = [])
    {
        foreach ($params as $key => $param) {
            foreach ($param as $value) {
                $data = $value($key);
            }
        }
        return $data;
    }


    /**
     * @param $total
     * @param $divisor
     * @param bool $sign
     * @return string
     */
    public static function percentage($total, $divisor, $sign = true)
    {
        $percentage =  $divisor / $total * 100;
        return ($sign) ? self::number($percentage) . '%' : self::number($percentage);
    }

}