<?php
/**
 * Class ActionCond
 */

class ActionCond
{
    private static $_columns = ['id', 'name', 'offices_id', 'departments_id', 'supervisors_id', 'year', 'month', 'user_id', 'employees_average_id', 'quantity'];

    /**
     * @param array $array
     * @param array $conditions
     * @return array
     */
    private static function conditions(array $array, array $conditions) :array
    {
        // If condition is empty $cond = AND
        if (empty($conditions)) {
            for ($i=0; $i < count($array) - 1; $i++) {
                $conditions[] = ['AND'];
            }
        } else {
            // If count conditions is differnt with count array -1
            if (count($conditions) != count($array) - 1) {
                $corectNumb = count($array)-1;
                $diff = $corectNumb - count($conditions);
                if ($diff < 0) {
                    $diffPositive = - $diff;
                    for ($i=0;$i<$diffPositive;$i++) {
                        array_pop($conditions);
                    }
                } elseif ($diff > 0) {
                    for ($i=0;$i<$diff;$i++) {
                       $conditions[] = ['AND'];
                    }
                }

            }
            $x=0;
            // Check what type of array is an transform in multidimensional array if is not
            foreach ($conditions as $v) {
                if (is_array($v)) {
                    continue;
                }
                $conditions[$x] = [$v];
                $x++;
            }
        }

        // if value is: $where = ['field', '=', 'value']
        $x = 0;
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
                $where[] = [$field, '=', $value];
                if ($x < count($array) - 1) {
                    $where = array_merge($where, $conditions[$x]);
                }
            }
            $x++;
        }
        return $where;
    }


    /**
     * @param array $item
     * @param array $conditions
     * @return array
     */
    public static function where(array $item, array $conditions = [])
    {
        return ActionCond::conditions($item, $conditions);
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