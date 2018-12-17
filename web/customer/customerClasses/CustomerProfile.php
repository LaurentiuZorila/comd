<?php

/**
 * Class CustomerProfile
 */
class CustomerProfile
{
    private $_db;

    const CONFIGURED = 1;

    /**
     * CustomerProfile constructor.
     */
    public function __construct()
    {
        $this->_db = CustomerDB::getInstance();
    }


    /**
     * @param $table
     * @param array $where
     * @param array $column
     * @param bool $all
     * @return mixed|null
     */
    public function records($table, array $where, array $column = ['*'], $all = true)
    {
        if ($all) {
            return $this->_db->get($table, $where, $column)->results();
        }
        return $this->_db->get($table, $where, $column)->first();
    }



    /**
     * @param $table
     * @param array $where
     * @return int
     */
    public function count($table, array $where)
    {
        return $this->_db->get($table, $where)->count();
    }


    /**
     * @param $table
     * @param array $where
     * @param $column
     * @return mixed
     */
    public function sum($table, array $where, $column)
    {
        return $this->_db->sum($table, $where, $column)->first()->sum;
    }

    /**
     * @param $table
     * @param $where
     * @param string $column
     * @return string
     */
    public function average($table, $where, $column ='')
    {
        $average = $this->_db->average($table, $where, $column)->first();
        return number_format($average->average, 2);
    }


    /**
     * @param $id
     * @return int
     */
    public function rating($id)
    {
        $rating = $this->_db->average(Params::TBL_RATING, ['user_id', '=', $id], 'rating')->first();
        return (int)round($rating->average);
    }


    /**
     * @param $table
     * @param array $where
     * @param array $columns
     * @return array
     */
    public function arrayMultipleRecords($table, array $where, array $columns)
    {
        if (count($columns) == 2) {
            $key[]          = $columns[0];
            $value[]        = $columns[1];
            $keyColumn      = $columns[0];
            $valueColumn    = $columns[1];
            $dataKeys       = [];
            $objDataKey     = $this->_db->get(self::PREFIX . $table, $where, $key)->results();
            $objDataValues  = $this->_db->get(self::PREFIX . $table, $where, $value)->results();

            foreach ($objDataKey as $dataKey) {
                $dataKeys[] = Common::getMonths(Session::get('lang'))[$dataKey->$keyColumn];
            }

            foreach ($objDataValues as $dataValue) {
                $dataValues[] = $dataValue->$valueColumn;
            }

            return array_combine($dataKeys, $dataValues);
        }
    }


}