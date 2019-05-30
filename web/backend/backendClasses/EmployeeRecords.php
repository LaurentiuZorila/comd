<?php
class EmployeeRecords extends BackendDB
{
    private $_employeeTable = Params::TBL_EMPLOYEES;

    private $_employeeId;

    private $_employeeRecords;

    public $employeeOfficeId;

    public $employeeDepartmentId;

    public $employeeName;

    public $order = true;



    private function getEmployeeRecords()
    {
        $this->_employeeRecords = parent::get($this->_employeeTable);
    }
}
