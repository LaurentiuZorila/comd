<?php
class ProfileDetails
{
    private $_db;

    const TBL_OFFICE        = 'cmd_offices';
    const TBL_DEPARTMENT    = 'cmd_departments';
    const TBL_SUPERVISORS   = 'cmd_supervisors';
    const TBL_TEAM_LEAD     = 'cmd_users';
    const TBL_EMPLOYEES     = 'cmd_employees';
    const TBL_COMMON        = ['furlough', 'absentees', 'unpaid'];
    const PREFIX            = 'cmd_';

    /**
     * ProfileDetails constructor.
     */
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function departmentDetails($id, $column)
    {
        $details = $this->_db->get(self::TBL_DEPARTMENT, ['id', '=', $id])->first();
        return $details->$column;
    }

    /**
     * @param $id
     * @param $column
     * @return array
     */
    public function officeDetails($id, $column)
    {
        $details = $this->_db->get(self::TBL_OFFICE, ['id', '=', $id])->first();
        if ($column === 'tables') {
            return explode(',', $details->$column);
        }
        return $details->$column;
    }

    /**
     * @param $id
     * @param $column
     * @return mixed
     */
    public function supervisorDetails($id, $column)
    {
        $details = $this->_db->get(self::TBL_SUPERVISORS, ['id', '=', $id])->first();
        return $details->$column;
    }

    /**
     * @param $id
     * @param $column
     * @return mixed
     */
    public function teamLeadDetails($id, $column)
    {
        $details = $this->_db->get(self::TBL_TEAM_LEAD, ['id', '=', $id])->first();
        return $details->$column;
    }

    /**
     * @param array $where
     * @return array
     */
    public function allData($where = [])
    {
        $data = [];
        foreach (self::TBL_COMMON as $table) {
            $data[$table] = $this->_db->sum(self::PREFIX . $table, $where, 'quantity')->first();
        }
        return $data;
    }


    /**
     * @param array $where
     * @return array
     */
    public function commonDetails($where = [])
    {
        $data = [];
        foreach (self::TBL_COMMON as $table) {
            $data[$table] = $this->_db->get(self::PREFIX . $table, $where, ['quantity'])->first();
        }
        return $data;
    }


    /**
     * @param $table
     * @param array $where
     * @return mixed
     */
    public function oneRecord($table, array $where, array $column)
    {
        return $this->_db->get($table, $where, $column)->first();
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
            $objDataKey     = $this->_db->get(self::PREFIX . $table, $where, $key)->results();
            $objDataValues  = $this->_db->get(self::PREFIX . $table, $where, $value)->results();

            foreach ($objDataKey as $dataKey) {
                $dataKeys[] = $this->getMonthsList()[$dataKey->$keyColumn];
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


    /**
     * @param int $maxYears
     * @param int $startYear
     * @return array
     */
    public function getYearsList($maxYears = 3, $startYear = 2018)
    {
        $range = range($startYear, date('Y') + $maxYears);
        return array_combine($range, $range);
    }


    /**
     * @return array
     */
    public function getMonthsList()
    {
        return [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
    }

}