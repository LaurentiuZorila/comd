<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/22/18
 * Time: 10:50 AM
 */

class ActionConditions
{
    private static $_columns = ['id', 'name', 'offices_id', 'departments_id', 'supervisors_id'];

    /**
     * @param array $array
     * @param array $conditions
     * @return array
     */
    public static function conditions(array $array, array $conditions) :array
    {
        $x = 1;
        if (!empty($conditions)) {
            foreach ($conditions as $v) {
                $cond[] = [$v];
            }
        } else {
            $cond = ['AND'];
        }

        foreach ($array as $item) {
            if (is_string($item)) {
                list($field, $value) = $array;
                if (in_array($field, self::$_columns)) {
                    $where = [$field, '=', $value];
                }
            }
            if (is_array($item)) {
                list($field, $value) = $item;
                if (in_array($field, self::$_columns)) {
                    $where[] = [$field, '=', $value];
                    if ($x < count($array)) {
                        if (count($cond) < 2) {
                            $where = array_merge($where, $cond);
                        } else {
                            $where = array_merge($where, $cond[$x - 1]);
                        }
                    }
                }
            }
            $x++;
        }
        if (count($array) === count($conditions)) {
            return $where;
        }
    }


    /**
     * @param array $array
     * @return array
     */
    public static function condition(array $array)
    {
        list($field, $value) = $array;
        if (in_array($field, self::$_columns)) {
            return [$field, '=', $value];
        }
    }
}