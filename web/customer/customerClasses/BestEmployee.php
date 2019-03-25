<?php
class BestEmployee
{
    private $_officeId;

    private $_db;

    private $_pTblsCol = ['tables_priorities'];

    private $_cTblsCol = ['tables_conditions'];

    private $_year;

    const COLUMN = 'quantity';

    const MAX    = '>';

    const MIN    = '<';


    /**
     * BestEmployee constructor.
     * @param $officeId
     */
    public function __construct($officeId)
    {
        $this->_db = CustomerDB::getInstance();
        $this->_officeId = $officeId;
        if (Input::get('year')) {
            $this->_year      = Input::get('year');
        } else {
            $this->_year      = date('Y');
        }
    }

    /**
     * @return array
     */
    private function setTablesPriority()
    {
        $tbls = Common::toArray($this->_db->get(Params::TBL_OFFICE, AC::where(['id', $this->_officeId]), $this->_pTblsCol)->first()->tables_priorities);
        foreach ($tbls as $k => $value) {
            $table[$k] = $value;
        }
        ksort($table);
        array_values($table);
        return array_map('trim', $table);
    }

    /**
     * @return mixed
     */
    public function setTableVisualisation()
    {
        $visualisations = Common::toArray($this->_db->get(Params::TBL_OFFICE, AC::where(['id', $this->_officeId]), ['data_visualisation'])->first()->data_visualisation);
        return $visualisations;
    }

    /**
     * @return mixed
     */
    private function setTablesByConditions()
    {
        $tbls = Common::toArray($this->_db->get(Params::TBL_OFFICE, AC::where(['id', $this->_officeId]), $this->_cTblsCol)->first()->tables_conditions);
        foreach ($tbls as $k => $value) {
            $table[$k] = $value;
        }
        return $table;
    }

    /**
     * @param bool $first
     * @return array
     */
    public function employeesData($first = true)
    {
        $table = $first ? Params::PREFIX . $this->getFirstTable() : Params::PREFIX . $this->getSecondTable();

        $employeesData = $this->_db->get(Params::TBL_EMPLOYEES, AC::where(['offices_id', $this->_officeId]), ['id', 'name'])->results();
        foreach ($employeesData as $employeeData) {
            $emplAvgId = $employeeData->id . '_' . $this->_year;
            $average = $this->_db->average($table, AC::where([['offices_id', $this->_officeId], ['employees_average_id', $emplAvgId]]), self::COLUMN)->first();
            $average = number_format($average->average, 2);
            $data[] = ['id' => $employeeData->id, 'name' => $employeeData->name, 'average' => $average];
        }
        return $data;
    }


    /**
     * @param array $item
     * @return array|mixed|string
     */
    public function bestEmployeeData($item = [])
    {
        $max = max(array_column($this->employeesData(), 'average'));
        $min = min(array_column($this->employeesData(), 'average'));

        foreach ($this->employeesData() as $values) {
            if ($this->getTablesByConditions()[$this->getFirstTable()] == '>') {
                if ($values['average'] == $max) {
                    $employeeData = ['id' => $values['id'], 'name' => $values['name'], 'average' => $values['average']];
                }
            } else if ($this->getTablesByConditions()[$this->getFirstTable()] == '<') {
                if ($values['average'] == $min) {
                    $employeeData = ['id' => $values['id'], 'name' => $values['name'], 'average' => $values['average']];
                }
            }

        }

        if (!empty($item)) {
            switch ($item[0]) {
                case 'id':
                    return $employeeData['id'];
                    break;
                case 'name';
                    return $employeeData['name'];
                    break;
                case 'average':
                    return $employeeData['average'];
                    break;
                case 'displayData':
                    if ($this->setTableVisualisation()[$this->getFirstTable()] == 'percentage') {
                        return $employeeData['average'] . '%';
                    } else {
                        return $employeeData['average'];
                    }
                    break;
            }
        } else {
            return $employeeData;
        }
    }

    /**
     * @return array
     */
    public function bestEmployeeCommonData()
    {
        $where = AC::where([['employees_id', $this->bestEmployeeData(['id'])], ['year', $this->_year]]);
        foreach (Params::TBL_COMMON as $commonTables) {
            $record = $this->_db->sum(Params::PREFIX . $commonTables, $where, 'quantity')->first()->sum;
            $record = is_null($record) ? 0 : $record;
            $data[] = ['table' => $commonTables, 'sum' => $record];
        }
        return $data;
    }

    /**
     * @param array $item
     * @param bool $label
     * @param bool $first
     * @return false|string
     */
    public function chartData($item = [], $label = false, $first = true)
    {
        // if label is true return json else return string
        if ($label) {
            foreach ($this->employeesData($first) as $value) {
                $records[] = $value[$item[0]];
            }
            return json_encode($records);
        } else {
            foreach ($this->employeesData($first) as $value) {
                $records[] = $value[$item[0]];
            }
            return implode(',', $records);
        }
    }


    /**
     * @param bool $first
     * @return string
     */
    public function chartColors($first = true)
    {
        if ($first) {
            foreach ($this->employeesData($first) as $value) {
                if ((int) $value['id'] == (int) $this->bestEmployeeData(['id'])) {
                    $colors[] = '\'#9528b9\'';
                } else {
                    $colors[] = '';
                }
            }
        } else {
            foreach ($this->employeesData($first) as $value) {
                if ((int)$value['id'] == (int)$this->bestEmployeeData(['id'])) {
                    $colors[] = '\'#e95f71\'';
                } else {
                    $colors[] = '';
                }
            }
        }
        return implode(',', $colors);
    }

    /**
     * @return array
     */
    public function getTablesPriority()
    {
        return $this->setTablesPriority();
    }

    /**
     * @return string
     */
    public function getFirstTable()
    {
        return trim(strtolower($this->getTablesPriority()[1]));
    }

    /**
     * @return string
     */
    public function getSecondTable()
    {
        return trim(strtolower($this->getTablesPriority()[2]));
    }

    /**
     * @param array $cond
     * @return mixed
     */
    public function getTablesByConditions($cond = [])
    {
        switch ($cond[0]) {
            case 'first':
                return $this->setTablesByConditions()[$this->getFirstTable()];
                break;
            case 'second':
                return $this->setTablesByConditions()[$this->getSecondTable()];
                break;
            default:
                return $this->setTablesByConditions();
                break;
        }
    }

    /**
     * @return false|string
     */
    public function year()
    {
        return $this->_year;
    }
}