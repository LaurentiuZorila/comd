<?php
class FrontendProfile
{
    private $_frontDb;

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
     * @param $id
     * @return mixed
     */
    public function departmentDetails($id, array $column)
    {
        $details = $this->_db->get(Params::TBL_DEPARTMENT, ['id', '=', $id], $column)->first();
        return $details;
    }


    /**
     * @param $id
     * @param $column
     * @return array
     */
    public function officeDetails($id, array $column)
    {
        $details = $this->_db->get(Params::TBL_OFFICE, ['id', '=', $id], $column)->first();
        return $details;
    }

    /**
     * @param $id
     * @param $column
     * @return mixed
     */
    public function supervisorDetails($id, array $column)
    {
        $details = $this->_db->get(Params::TBL_SUPERVISORS, ['id', '=', $id])->first();
        return $details->$column;
    }


    /**
     * @param $id
     * @param $column
     * @return mixed
     */
    public function teamLeadDetails($id, array $column)
    {
        $details = $this->_db->get(Params::TBL_TEAM_LEAD, ['id', '=', $id])->first();
        return $details->$column;
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
            $key[]          = $columns[0];
            $value[]        = $columns[1];
            $keyColumn      = $columns[0];
            $valueColumn    = $columns[1];
            $dataKeys       = [];
            $objDataKey     = $this->_db->get($table, $where, $key)->results();
            $objDataValues  = $this->_db->get($table, $where, $value)->results();

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
     * @param array $where
     * @return mixed
     */
    public function unpaidHours($where = [])
    {
        return $this->_db->get('cmd_unpaid', $where, ['hours'])->first();
    }


}