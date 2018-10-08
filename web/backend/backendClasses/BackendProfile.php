<?php
class BackendProfile
{
    private $_db;


    /**
     * ProfileDetails constructor.
     */
    public function __construct()
    {
        $this->_db = BackendDB::getInstance();
    }


    /**
     * @param $table
     * @param array $where
     * @return object
     */
    public function records($table, array $where, $column = ['*'], $all = true)
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
     * @return int
     */
    public function sum($table, array $where, $column)
    {
        $sum = $this->_db->sum($table, $where, $column)->first();
        return $sum->sum;
    }


    /**
     * @param $lead_id
     * @return float average
     */
    public function rating(array $where)
    {
        $rating = $this->_db->average(Params::TBL_RATING, $where, 'rating')->results();
        return round(Common::columnValues($rating, 'average'));
    }


    /**
     * @param $table
     * @param array $where
     * @param array $columns
     * @return array key => values
     * @uses first column is key and second is value only for 2 columns
     */
    public function arrayMultipleRecords($table, array $where, array $columns)
    {
        if (count($columns) == 2) {
            $key[]          = $columns[0];
            $value[]        = $columns[1];
            $keyColumn      = $columns[0];
            $valueColumn    = $columns[1];
            $dataKeys       = [];
            $objDataKey     = $this->_db->get(Params::PREFIX . $table, $where, $key)->results();
            $objDataValues  = $this->_db->get(Params::PREFIX . $table, $where, $value)->results();

            foreach ($objDataKey as $dataKey) {
                $dataKeys[] = Common::getMonths()[$dataKey->$keyColumn];
            }

            foreach ($objDataValues as $dataValue) {
                $dataValues[] = $dataValue->$valueColumn;
            }

            return array_combine($dataKeys, $dataValues);
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