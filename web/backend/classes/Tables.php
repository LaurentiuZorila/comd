<?php
class Tables
{
    /**
     * @param $id
     * @return mixed
     */
    public static function getDetails ($table, array $where, $column)
    {
        $columns = ['name', 'id'];
        if (in_array($column, $columns)) {
            $name = DB::getInstance()->get($table, $where)->first();
            return $name = $name->$column;
        }
        return '';
    }
}