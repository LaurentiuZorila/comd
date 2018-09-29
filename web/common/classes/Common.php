<?php
class Common
{
    private $_db;

    /**
     * ProfileDetails constructor.
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


    /**
     * @param $items
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
    public static function getMonths()
    {
        for ($x = 1; $x < 13; $x++) {
            $months[$x] = date("F", mktime(0, 0, 0, $x, 10));
        }
        return $months;
    }


    /**
     * @param $string
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
     * @return string
     */
    public function number($item)
    {
        return number_format($item, 2);
    }

}