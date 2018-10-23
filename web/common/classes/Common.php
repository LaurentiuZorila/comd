<?php
class Common
{
    /**
     * @var BackendDB|null
     */
    private $_db;

    /**
     *  Allowed characters for profile setup
     */
    const ALLOWED_CHARACTERS = ['<', '>'];

    /**
     * Common constructor.
     */
    public function __construct()
    {
        $this->_db = CommonDB::getInstance();
    }


    /**
     * @param $table
     * @param array $where
     * @param array $column
     * @param bool $all
     * @return mixed
     */
    public function records($table, array $where, array $column = ['*'], $all = true)
    {
        foreach ($column as $col) {
            if (in_array($col, Params::ALLOWED_COLUMNS)) {
                if ($all) {
                    return $this->_db->get($table, $where, $column)->results();
                } else {
                    return $this->_db->get($table, $where, $column)->first();
                }
            }
            return false;
        }
    }


    /**
     * @param $lead_id
     * @return float average
     */
    public function rating(array $where)
    {
        $rating = $this->_db->average(Params::TBL_RATING, $where, 'rating')->results();
        return round(Values::columnValues($rating, 'average'));
    }


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
     * @return mixed , transform OBJ to string for one column
     */
    public static function columnValues($items, $column)
    {
        foreach ($items as $item) {
            return $item->$column;
        }
    }


    /***
     * @param $obj
     * @param $column
     * @param $returnedKey
     * @return mixed
     */
    public static function objToArrayOneValue($obj, $column, $returnedKey)
    {
        $obj       = json_decode($obj->$column);
        $arrItem   = (array) $obj;
        ksort($arrItem, SORT_NUMERIC);
        array_map('trim', $arrItem);
        foreach ($arrItem as $key => $value) {
            $item[$key] = $value;
        }
        return $item[$returnedKey];
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
     * @param $num
     * @return string
     *
     */
    public static function numberToMonth($num)
    {
        $dateObj= DateTime::createFromFormat('!m', $num);
        return $dateObj->format('F');
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
     * @return array
     */
    public static function getMonths() :array
    {
        for ($x = 1; $x < 13; $x++) {
            $months[$x] = date("F", mktime(0, 0, 0, $x, 10));
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

}