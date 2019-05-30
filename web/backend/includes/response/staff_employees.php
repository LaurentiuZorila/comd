<?php
include "../../functions/autoload_ajax.php";
$data       = new BackendProfile();
$officesId  = $_GET['office_id'];

$employees = $data->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $officesId]);

foreach ($employees as $employee) {
    $employeesName[]    = trim(strtoupper($employee->name));
    $employeesId[]      = $employee->id;
    $employeesData      = array_combine($employeesId, $employeesName);
}

echo json_encode($employeesData);