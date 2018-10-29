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

$table      = Input::get('table');
$table      = Params::PREFIX . trim($table);
$officesId  = Input::get('offices_id');
$year       = Input::get('year');

// Array with all months
$allMonths   = $data->records($table, [['offices_id', '=', $officesId], 'AND', ['year', '=', $year]], ['month']);

foreach ($allMonths as $months) {
     $month[$months->month] = Common::getMonths($lang)[$months->month];
}

echo json_encode($month);