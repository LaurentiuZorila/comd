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
$data       = new BackendProfile();
$officeId   = $_GET['id'];

$allTables = $data->records(Params::TBL_OFFICE, ['id', '=', $officeId], ['tables']);

foreach (Common::objToArray($allTables, 'tables') as $table) {
    $tables[] = Params::PREFIX . trim(strtolower($table));
}

foreach ($tables as $table) {
    $years[] = $data->records($table, $where =[], ['year']);
}

foreach ($years as $values) {
    foreach ($values as $value) {
        $year[] = $value->year;
        $year = array_combine($year, $year);
    }
}

echo json_encode(array_unique($year));