<?php
function __autoload($class_name)
{
    //commonClasses directories
    $directorys = array(
        '../customerClasses/',
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


$data           = new CustomerProfile();
$departmentId   = Input::get('departments_id');
$offices        = $data->records(Params::TBL_OFFICE, ['departments_id','=', $departmentId], ['id', 'name']);

foreach ($offices as $office) {
    $item[$office->id] = $office->name;
}

echo json_encode($item);