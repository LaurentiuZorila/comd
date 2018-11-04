<?php
class FrontendProfile
{
    private $_db;

    private $_leadsTbl = ['cmd_users'];

    private $_year;

    private $_month;

    /**
     * FrontendProfile constructor.
     */
    public function __construct()
    {
        $this->_db = FrontendDB::getInstance();
        $this->_year = date('Y');
        $this->_month = date('n');
    }


    /**
     * @param $table
     * @param array $where
     * @param array $column
     * @param bool $all
     * @return mixed
     */
    public function records($table, array $where, array $column, $all = false)
    {
        if ($all) {
            return $this->_db->get($table, $where, $column)->results();
        }
        return $this->_db->get($table, $where, $column)->first();
    }


    /**
     * @param array $where
     * @param string $column
     * @return array
     */
    public function sumAllCommonData(array $where, $column = '')
    {
        $data = [];
        foreach (Params::TBL_COMMON as $table) {
            $data[$table] = $this->_db->sum(Params::PREFIX . $table, $where, $column)->first();
        }
        return $data;
    }


    /**
     * @param array $where
     * @param array $column
     * @return array
     */
    public function commonDetails(array $where, array $column)
    {
        $data = [];
        foreach (Params::TBL_COMMON as $table) {
            $data[$table] = $this->_db->get(Params::PREFIX . $table, $where, $column)->first();
        }
        return $data;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getFeedback($id)
    {
        return $this->records(Params::TBL_RATING, ['employees_id', '=', $id], ['user_id', 'rating'], true);
    }


    /**
     * @param $where
     * @param $columns
     * @return mixed
     */
    public function getLeads($where, $columns)
    {
       $item = ActionConditions::condition($where);
       return $this->records($this->_leadsTbl[0], $item, $columns, true);
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
     * @param $lang
     * @param $table
     * @param $where
     * @param $columns
     * @return array
     */
    public function arrayMultipleRecords($table, $where, $columns, $lang)
    {
        if (count($columns) == 2) {

            list($filed, $item) = $columns;

            //Array with key
            $key[]          = $filed;
            // Array with value
            $value[]        = $item;
            // String key
            $keyColumn      = $filed;
            // String value
            $valueColumn    = $item;

            $objDataKey     = $this->_db->get($table, $where, $key)->results();
            $objDataValues  = $this->_db->get($table, $where, $value)->results();
            foreach ($objDataKey as $dataKey) {
                $dataKeys[] = Common::getMonths($lang)[$dataKey->$keyColumn];
            }
            foreach ($objDataValues as $dataValue) {
                $dataValues[] = $dataValue->$valueColumn;
            }

            if (count($dataKeys) > 0 && count($dataValues) > 0) {
                return array_combine($dataKeys, $dataValues);
            }
        }
    }


    /**
     * @param array $where
     * @return mixed
     */
    public function unpaidHours($where = [])
    {
        return $this->_db->get('cmd_unpaid', $where, ['hours'])->first();
    }

}