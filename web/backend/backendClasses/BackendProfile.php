<?php
class BackendProfile
{
    private $_db;

    public $forTranslate = [
        'absentees'     => 'Total_user_absentees',
        'unpaid'        => 'Total_user_unpaid',
        'medical'       => 'Total_user_medical',
        'furlough'      => 'Total_user_furlough',
        'unpaidHours'   => 'unpaidHours',
        'hoursToRecover' => 'total_user_hoursToRecover'
    ];
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
     * @param array $column
     * @param bool $all
     * @param array $endParams
     * @return mixed
     */
    public function records($table, array $where = [], $column = ['*'], $all = true, $endParams = [])
    {
        if ($all) {
            return $this->_db->get($table, $where, $column, $endParams)->results();
        }
        return $this->_db->get($table, $where, $column, $endParams)->first();
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
        return !empty($sum->sum) ? $sum->sum : 0;
    }


    /**
     * @param array $where
     * @return int
     */
    public function rating(array $where)
    {
        $rating = $this->_db->average(Params::TBL_RATING, $where, 'rating')->first()->average;
        return (float)$rating;
    }


    /**
     * @param array $where
     * @param bool $assoc
     * @return array
     */
    public function getSumForCommonTables(array $where, $assoc = false)
    {
        if ($assoc) {
            foreach (Params::ASSOC_PREFIX_TBL as $tables => $prefixTables) {
                $commonData[$tables] = $this->sum($prefixTables, $where, 'quantity');
            }
        } else {
            foreach (Params::ASSOC_PREFIX_TBL as $tables => $prefixTables) {
                $commonData[] = $this->sum($prefixTables, $where, 'quantity');
            }
        }
        return $commonData;
    }


    /**
     * @param array $where
     * @return array
     */
    public function getRecordsForCommonTables(array $where)
    {
        foreach (Params::ASSOC_PREFIX_TBL as $tables => $prefixTables) {
            $commonData[$tables] = $this->records($prefixTables, $where, ['quantity']);
        }
        return $commonData;
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
                $dataKeys[] = Common::getMonths($lang)[$dataKey->$keyColumn];
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