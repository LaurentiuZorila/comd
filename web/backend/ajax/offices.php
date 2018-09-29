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
$common = new BackendProfile();

$departmentsId = Input::get('departments');

$offices = $common->records(Params::TBL_OFFICE, ['departments_id', '=', $departmentsId], ['id', 'name']);

foreach ($offices as $office) {
    $data[$office->id] = $office->name;
}

echo json_encode($data);