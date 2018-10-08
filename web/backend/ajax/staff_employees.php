<?php
function __autoload($class_name)
{
    /** CommonClasses directories */
    $directorys = array(
        '../backendClasses/',
        '../../common/classes/'
    );
    /** for each directory */
    foreach($directorys as $directory)
    {
        /** see if the file exsists */
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
}
$data       = new BackendProfile();
$officesId  = $_GET['office_id'];

$employees = $data->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $officesId]);

foreach ($employees as $employee) {
    $employeesName[]    = trim(strtoupper($employee->name));
    $employeesId[]      = $employee->id;
    $employeesData      = array_combine($employeesId, $employeesName);
}

echo json_encode($employeesData);