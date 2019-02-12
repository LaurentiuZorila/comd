<?php
trait Dates
{

    /**
     * @param $days
     * @param $year
     * @return DateTime|string
     * @throws Exception
     */
    public static function startDate($days, $year) {
        $days = explode(',', $days);
        if (count($days) > 1) {
            $start_date = current($days) . '/' . $year;
            $start_date = str_replace('/','-',$start_date);
            $startDate  = new DateTime($start_date);
            $startDate = $startDate->format('Y-m-d');
        } else {
            $start_date = $days[0] . '/' . $year;
            $start_date = str_replace('/','-',$start_date);
            $startDate  = new DateTime($start_date);
            $startDate = $startDate->format('Y-m-d');
        }
        return $startDate;
    }


    /**
     * @param $days
     * @param $year
     * @return string
     * @throws Exception
     */
    public static function endDate($days, $year)
    {
        $days = explode(',', $days);
        if (count($days) > 1) {
            $end_date       = end($days) . '/' . $year;
            $end_date       = str_replace('/','-',$end_date);
            $endDate        = new DateTime($end_date);
            $endDateOneMore = $endDate->modify( '+1 day' );
            $end            = $endDateOneMore->format('Y-m-d');
        } else {
            $end_date       = $days[0] . '/' . $year;
            $end_date       = str_replace('/','-',$end_date);
            $endDate        = new DateTime($end_date);
            $endDateOneMore = $endDate->modify( '+1 day' );
            $end            = $endDateOneMore->format('Y-m-d');
        }
        return $end;
    }


    /**
     * @param $string
     * @param $month
     * @return string
     */
    public static function makeDateForDb($string, $month)
    {
        $month = $month < 10 && strlen($month) === 1 ? 0 . $month : $month;
        if (count(explode(',', $string)) > 1) {
            $items = explode(',', $string);
            foreach ($items as $item) {
               $days[] = $item < 10 && strlen($item) === 1 ? 0 . $item . '/' . $month : $item . '/' . $month;
            }
        } else {
            if (in_array($string, range(1,31))) {
             $days[] = $string < 10 && strlen($string) === 1 ? 0 . $string . '/' . $month : $string . '/' . $month;
            }
        }
        return implode(',',$days);
    }


    /**
     * @param $days
     * @return int
     */
    public static function countDays($days)
    {
        return count(explode(',', $days));
    }


    /**
     * @param $string
     * @return bool
     */
    public static function checkDays($string)
    {
        if (strpos($string, ',') > 0) {
            $strings = explode(',', $string);
            foreach ($strings as $item) {
                return in_array($item, range(1,31));
            }
        } else {
            return in_array($string, range(1,31));
        }
    }
}