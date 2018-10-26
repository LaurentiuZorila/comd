<?php
function __autoload($class_name)
{
    //commonClasses directories
    $directorys = array(
        '../../backendClasses/',
        '../../../common/classes/'
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
$officeId   = Input::get('id');

$allTables = $data->records(Params::TBL_OFFICE, ['id', '=', $officeId], ['tables']);

foreach (Common::objToArray($allTables, 'tables') as $table) {
    if (empty($table)) {
        $tables = [];
    }
    $tables[] = Params::PREFIX . trim(strtolower($table));
}

/** Check if exist year on tables */
if (count($tables) > 1) {
    foreach ($tables as $cmdTable) {
        $years[$cmdTable] = $data->records($cmdTable, $where =['offices_id', '=', $officeId], ['year']);
    }
} else {
    $years[] = 'Not found';
}

if (count($years) > 1) {
    $fullArray = [];
    foreach ($years as $k => $v) {
        foreach ($k as $val) {
            for ($i=0;$i<count($years);$i++) {
                $fullArray[] = $val->year;
            }
        }
   }
}


print_r($fullArray);
exit;


