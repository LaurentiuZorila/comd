<?php
function __autoload($class_name) {
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

$common     = new BackendProfile();
$officeId   = $_GET['office_id'];

$allTables  = $common->records(Params::TBL_OFFICE, AC::where(['id', $officeId]), ['tables'], false);

$keyTable = explode(',', trim($allTables->tables));
$valTable = explode(',', $allTables->tables);
foreach ($valTable as $valtbl) {
    $valTables[] = ucfirst($valtbl);
}
$tables = array_combine($keyTable, $valTables);


echo json_encode($tables);