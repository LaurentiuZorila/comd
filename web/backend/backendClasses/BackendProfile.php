<?php
class BackendProfile
{
    private $_db;

    private $_backUser;

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
        $this->_backUser = new BackendUser();
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
     * @param string $index
     * @return mixed
     */
    public function getAssocTables($index = '')
    {
        $string = $this->records(Params::TBL_OFFICE, AC::where(['departments_id', $this->_backUser->departmentId()]),['tables'], false);
        $tables = explode(',', $string->tables);
        foreach ($tables as $table) {
            $assocTables[$table] = Params::PREFIX . $table;
        }
        if (empty($index)) {
            return $assocTables;
        }
        return $assocTables[$index];
    }


    /**
     * @param array $col
     * @return mixed
     */
    public function getOffices($col = [])
    {
        return $this->records(Params::TBL_OFFICE, AC::where(['departments_id', $this->_backUser->departmentId()]), $col);
    }


    /**
     * @return int
     */
    public function countOffices()
    {
        return $this->count(Params::TBL_OFFICE, AC::where(['departments_id', $this->_backUser->departmentId()]));
    }


    /**
     * @return int
     */
    public function countStaff()
    {
        return $this->count(Params::TBL_TEAM_LEAD, AC::where(['departments_id', $this->_backUser->departmentId()]));
    }

    /**
     * @return int
     */
    public function countEmployees()
    {
        return $this->count(Params::TBL_EMPLOYEES, AC::where(['departments_id', $this->_backUser->departmentId()]));
    }
}