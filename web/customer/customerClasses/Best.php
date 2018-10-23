<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/12/18
 * Time: 5:31 PM
 */

class Best
{
    /**
     * @var CustomerProfile
     */
    private $_data;

    /**
     * @var
     */
    private $_officeId;

    /**
     * @var false|string
     */
    private $_year;

    const COLUMN = 'quantity';

    const MAX    = '>';

    const MIN    = '<';


    /**
     * Best constructor.
     * @param $officeId
     */
    public function __construct($officeId)
    {
        $this->_data      = new CustomerProfile();
        $this->_year      = date('Y');
        $this->_officeId  = $officeId;
    }


    /**
     * @param $table
     * @param bool $first
     * @return mixed
     */
    private function setBestEmployees($table, $first = true)
    {
        foreach ($this->getTblCond($first) as $condition) {
            if ($condition == self::MAX) {
                $key        = array_keys($this->getAverageForAllEmployees($table), max($this->getAverageForAllEmployees($table)));
                $val        = max($this->getAverageForAllEmployees($table));
                $best[$key[0]] = $val;
            } elseif ($condition == self::MIN) {
                $key        = array_keys($this->getAverageForAllEmployees($table), min($this->getAverageForAllEmployees($table)));
                $val        = min($this->getAverageForAllEmployees($table));
                $best[$key[0]] = $val;
            }
        }
        return $best;
    }


    /**
     * @return array
     * @return array with names and average for all employees
     */
    public function setChart($first = true) :array
    {
        if ($first) {
            foreach ($this->getAverageForAllEmployees($this->getFirstPriorityTbl(true)) as $k => $v) {
                $pos    = strpos($k, '_');
                $name     = substr($k, 0, $pos);
                $data[$name] = $v;
            }
        } elseif (!$first) {
            foreach ($this->getAverageForAllEmployees($this->getSecondPriorityTbl(true)) as $k => $v) {
                $pos    = strpos($k, '_');
                $name     = substr($k, 0, $pos);
                $data[$name] = $v;
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public function priorityTbls()
    {
        $tbls = Common::toArray($this->_data->records(Params::TBL_OFFICE, ['id', '=', $this->_officeId], ['tables_priorities'], false)->tables_priorities);
        foreach ($tbls as $k => $value) {
            if ($k < 3) {
                $table[$k] = $value;
            }
        }
        return array_map('trim', $table);
    }


    /**
     * @return array
     */
    public function getCondTbl()
    {
        $conditions = Common::toArray($this->_data->records(Params::TBL_OFFICE, ['id', '=', $this->_officeId], ['tables_conditions'], false)->tables_conditions);
        foreach ($conditions as $k => $condition) {
            $cond[$k] = $condition;
        }
        return $cond;
    }


    /**
     * @param bool $prefix
     * @return string
     */
    public function getFirstPriorityTbl($prefix = false)
    {
        if ($prefix) {
            return Params::PREFIX . $this->priorityTbls()[1];
        }
        return $this->priorityTbls()[1];
    }


    /**
     * @param bool $prefix
     * @return string
     */
    public function getSecondPriorityTbl($prefix = false)
    {
        if ($prefix) {
            return Params::PREFIX . $this->priorityTbls()[2];
        }
        return $this->priorityTbls()[2];
    }


    /**
     * @param bool $first
     * @return array
     * @uses If param is true return first table by priority, if is false return second table by priority
     */
    public function getTblCond($first = true)
    {
        if ($first) {
            foreach ($this->getCondTbl() as $k => $v) {
                if ($k == $this->getFirstPriorityTbl()) {
                    $array[$this->getFirstPriorityTbl()] = $v;
                }
            }
        } else {
            foreach ($this->getCondTbl() as $k => $v) {
                if ($k == $this->getSecondPriorityTbl()) {
                    $array[$this->getSecondPriorityTbl()] = $v;
                }
            }
        }
        return $array;
    }


    /**
     * @param $table
     * @param bool $first
     * @return array
     */
    public function getBestEmployees($table, $first = true) :array
    {
        foreach ($this->setBestEmployees($table, $first) as $k => $v) {
            $pos    = strpos($k, '_');
            $name     = substr($k, 0, $pos);
            $data[$name] = $v;
        }
        return $data;
    }


    /**
     * @return array
     */
    public function getAllEmployees() :array
    {
        $records = $this->_data->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $this->_officeId], ['id', 'name']);
        foreach ($records as $record) {
            $array[$record->id] = $record->name;
        }
        return $array;
    }


    /**
     * @param $table
     * @return array
     */
    public function getAverageForAllEmployees($table)
    {
        foreach ($this->getAllEmployees() as $k => $v) {
            // Conditions for action
            $where = ['employees_average_id', '=', $k . '_' . date('Y')];
            $array[$v. '_' . $k] = $this->_data->average($table, $where, self::COLUMN);
        }
        return $array;
    }


    /**
     * @return bool|string
     */
    private function bestId()
    {
        foreach ($this->setBestEmployees($this->getFirstPriorityTbl(true)) as $k => $v) {
            $lenght = strlen($k);
            $pos    = strpos($k, '_') + 1;
            $id     = substr($k, $pos, $lenght);
        }
        return $id;
    }


    /**
     * @return mixed
     * @return name for best employees
     */
    public function getBestEmployeesName()
    {
       return array_keys($this->getBestEmployees($this->getFirstPriorityTbl(true)))[0];
    }


    /**
     * @return array
     */
    public function getCommonData() :array
    {
        $x = 0;
        foreach (Params::PREFIX_TBL_COMMON as $prefTables) {
            $commonData[Params::TBL_COMMON[$x]] = $this->_data->sum($prefTables, ['employees_id', '=', $this->bestId()], 'quantity');
            $x++;
        }
        return $commonData;
    }


    /**
     * @param bool $first
     * @return false|string
     */
    public function getChartLabel($first = true) :array
    {
        foreach ($this->setChart($first) as $key => $value) {
            $data[] = $key;
        }
        return $data;
    }


    /**
     * @param bool $first
     * @return string
     */
    public function getChartValues($first = true)
    {
        return implode(',', $this->setChart($first));
    }


    /**
     * @param bool $first
     * @return string
     */
    public function getChartColor($first = true)
    {
        if ($first) {
            foreach ($this->setChart($first) as $k => $v) {
                if ($k == $this->getBestEmployeesName()) {
                    $colors[] = '\'#9528b9\'';
                } else {
                    $colors[] = '';
                }
            }
        } else {
            foreach ($this->setChart($first) as $k => $v) {
                if ($k == $this->getBestEmployeesName()) {
                    $colors[] = '\'#EF8C99\'';
                } else {
                    $colors[] = '';
                }
            }
        }
        return implode(',', $colors);
    }
}