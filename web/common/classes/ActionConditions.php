<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/22/18
 * Time: 10:50 AM
 */

class ActionConditions
{
    private static $_columns = ['id', 'name', 'offices_id', 'departments_id', 'supervisors_id', 'year', 'month', 'user_id', 'employees_average_id', 'quantity'];

    /**
     * @param array $array
     * @param array $conditions
     * @return array
     */
    public static function conditions(array $array, array $conditions = []) :array
    {
        $x = 1;
        // If condition is empty $cond = AND
        if (!empty($conditions)) {
            foreach ($conditions as $v) {
                $cond[] = [$v];
            }
        } else {
            for ($i=0; $i < count($array) - 1; $i++) {
                $cond[] = 'AND';
            }
        }

        // if value is: $where = ['field', '=', 'value']
        foreach ($array as $item) {
            if (is_string($item)) {
                list($field, $value) = $array;
                if (in_array($field, self::$_columns)) {
                    $where = [$field, '=', $value];
                }
            }
            // if value is: $where = [['field', 'value'], ['field','value']]
            if (is_array($item)) {
                list($field, $value) = $item;
                if (in_array($field, self::$_columns)) {
                    $where[] = [$field, '=', $value];
                    if ($x < count($array)) {
                        if (count($cond) < 2) {
                            $where = array_merge($where, $cond);
                        } else {
                            $where = array_merge($where, $cond);
                        }
                    }
                }
            }
            $x++;
        }
        return $where;
    }


    /**
     * @param $array
     * @param bool $year
     * @return array
     */
    public static function condition($array, $year = false)
    {
        //If year is true year = date('Y') -> this is for average
        if ($year) {
            //Current year
            $year = date('Y');
            list($field, $value) = $array;
            if (in_array($field, self::$_columns)) {
                $value = $value . '_' . $year;
                $record = [$field, '=', $value];
            }
        } else {
            list($field, $value) = $array;
            if (in_array($field, self::$_columns)) {
                $record = [$field, '=', $value];
            }
        }

        return $record;
    }
}