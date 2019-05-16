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

    private $_defaultCol = ['name', 'id', 'departments_id', 'offices_id'];

    /**
     * ProfileDetails constructor.
     */
    public function __construct()
    {
        $this->_db = BackendDB::getInstance();
        $this->_backUser = new BackendUser();
    }


    /**
     * @param array $column
     * @return array
     */
    private function addColumns($column = [])
    {
        if (!empty($column)) {
            foreach ($column as $col) {
                if (!in_array($col, $this->_defaultCol)) {
                    array_push($col, $this->_defaultCol);
                }
            }
        }
        return $this->_defaultCol;
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
     * @param $column
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
    public function getSumFormCommonTables(array $where, $assoc = false)
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
    public function getRecordsFormCommonTables(array $where)
    {
        foreach (Params::ASSOC_PREFIX_TBL as $tables => $prefixTables) {
            $commonData[$tables] = $this->records($prefixTables, $where, ['quantity']);
        }
        return $commonData;
    }


    /**
     * @param string $officeId
     * @param string $index
     * @return mixed
     */
    public function getAssocTables($officeId = '' ,$index = '')
    {
        $string = $this->records(Params::TBL_OFFICE, AC::where(['id', $officeId]),['tables'], false);
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


    /**
     * @param array $order
     * @param array $where
     * @param array $col
     * @return object
     */
    public function getEmployeesData($order = [], $where = [], $col = [])
    {
        $columns = $this->addColumns($col);
        if (!empty($order) && $order[0] == 'order') {
            $ordered = ['ORDER BY' => $order[1]];
        } else {
            $ordered = $order;
        }
        if (!empty($order)) {
            return $this->records(Params::TBL_EMPLOYEES, $where, $columns, true, $ordered);
        } else {
            return $this->records(Params::TBL_EMPLOYEES, $where, $columns);
        }
    }


    /**
     * @param array $order
     * @param array $where
     * @param $table
     * @return array
     */
    public function getCommonData(array $order, array $where, $table)
    {
        foreach ($this->getEmployeesData($order, $where) as $employeeRecords) {
            $avgParam = $employeeRecords->id . '_' . $this->currentYear();
            $employeeId = $employeeRecords->id;
            $names = $employeeRecords->name;
            $avg = $this->sum($table, AC::condition(['employees_average_id', $avgParam]), 'quantity');
            $id = $employeeId;
            $commonData[] = [$table => ['name' => $names ,'avg' => $avg, 'id' => $id]];
        }
        return $commonData;
    }


    /**
     * @param $leadId
     * @param array $col
     * @return object
     */
    public function leadProfile($leadId, $col = [])
    {
       $columns = $this->addColumns($col);
       return $this->records(Params::TBL_TEAM_LEAD, AC::where(['id', $leadId]),$columns, false);
    }


    /**
     * @param $leadId
     * @return int
     */
    public function totalLeadEmployees($leadId)
    {
       return (int)$this->count(Params::TBL_EMPLOYEES, AC::where(['offices_id', $this->leadProfile($leadId)->offices_id]));
    }


    /**
     * @param $leadId
     * @return mixed
     */
    public function leadDepartName($leadId)
    {
       return $this->records(Params::TBL_DEPARTMENT, AC::where(['id', $this->leadProfile($leadId)->departments_id]), ['name'], false)->name;
    }

    /**
     * @param $leadId
     * @return mixed
     */
    public function leadOfficeName($leadId)
    {
        return $this->records(Params::TBL_OFFICE, AC::where(['id', $this->leadProfile($leadId)->offices_id]), ['name'], false)->name;
    }


    /**
     * @return false|string
     */
    public function currentYear()
    {
        return date('Y');
    }


    /**
     * @param $officeId
     * @param array $col
     * @return mixed
     */
    public function leadsName($officeId, array $col = [])
    {
        return $this->records(Params::TBL_TEAM_LEAD, AC::where(['offices_id', $officeId]), $col);
    }


    /**
     * @return array
     */
    public function tablesDisplay()
    {
        $dataDisplay = $this->records(Params::TBL_OFFICE, AC::where(['departments_id', $this->_backUser->departmentId()]), ['data_visualisation'], false)->data_visualisation;
        return (array)json_decode($dataDisplay);
    }


    /**
     * @return array
     */
    public function getStatus()
    {
        $status = $this->records(Params::TBL_STATS);
            foreach ($status as $stats) {
                $allStats[$stats->id] = $stats->status;
            }
        return $allStats;
    }


    /**
     * @return string
     */
    public function pieBgColors()
    {
        foreach (Params::BACKEND_ASSOC_PREFIX_TBL as $key => $value) {
                $colors[] = $value['pie_chart_color'];
        }
        $stringColors = '"' . implode('","',$colors) . '"';
        return $stringColors;
    }
}