<?php
class FrontendProfile
{
    private $_db;

    /**
     * ProfileDetails constructor.
     */
    public function __construct()
    {
        $this->_db = FrontendDB::getInstance();
    }


    /**
     * @param $table
     * @param array $where
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
     * @param $table
     * @param array $where
     * @param array $columns
     * @return array key => values
     * @uses first column is key and second is value only for 2 columns
     */
    public function arrayMultipleRecords($table, array $where, array $columns)
    {
        if (count($columns) == 2) {
            //Array with key
            $key[]          = $columns[0];
            // Array with value
            $value[]        = $columns[1];
            // String key
            $keyColumn      = $columns[0];
            // String value
            $valueColumn    = $columns[1];

            $objDataKey     = $this->_db->get($table, $where, $key)->results();
            $objDataValues  = $this->_db->get($table, $where, $value)->results();
            foreach ($objDataKey as $dataKey) {
                $dataKeys[] = Common::getMonths()[$dataKey->$keyColumn];
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