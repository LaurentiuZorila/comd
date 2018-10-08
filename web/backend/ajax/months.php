<?php
function __autoload($class_name)
{
    //commonClasses directories
    $directorys = array(
        '../backendClasses/',
        '../../common/classes/'
    );
    //for each directory
    foreach($directorys as $directory)
    {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
}
$data = new BackendProfile();

$employeeId   = $_GET['employees_id'];

/** Get office id for selected office, form employees table */
$officeId   = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $employeeId], ['offices_id'], false)->offices_id;

/** Get all tables from offices table */
$allTables  = $data->records(Params::TBL_OFFICE, ['id', '=', $officeId], ['tables'], false)->tables;

/** Make array with all tables */
$tables     = explode(',', trim($allTables));

/** Tables with prefix */
foreach ($tables as $table) {
        $prefixTables[]  = Params::PREFIX . trim(strtolower($table));
    }

/** Get all months form all tables */
foreach ($prefixTables as $prefixTable) {
        $allMonths = $data->records($prefixTable, ['employees_id', '=', $employeeId], ['month']);
    }

/** Transform numeric months in textual months */
foreach ($allMonths as $months) {
    foreach ($months as $month) {
        $numberMonths[]     = $month;
        $textualMonths[]    = Common::getMonths()[$month];
        $month              = array_combine($numberMonths, $textualMonths);
    }
}

/** remove duplicates and print Json */
echo json_encode(array_unique($month));