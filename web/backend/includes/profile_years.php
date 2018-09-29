<?php
function __autoload($class_name)
{
    //commonClasses directories
    $directorys = array(
        '../classes/',
        '../../commonClasses/'
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

$table    = Input::get('table');
$table    = Params::PREFIX . $table;
$staff_id = Input::get('staff_id');


$allYears = BackendDB::getInstance()->get($table, ['user_id', '=', $staff_id], ['year'])->results();

foreach ($allYears as $years) {
    $year[] = $years->year;
    $year = array_combine($year, $year);
}

echo json_encode($year);




