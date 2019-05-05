<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = array(
        './../customerClasses/',
        './../../common/classes/',
        './../../customerClasses/',
        './../../../common/classes/'
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
});

$data     = new CustomerProfile();
$cityId   = Input::get('city_id');

$departments  = $data->records(Params::TBL_DEPARTMENT, AC::where(['city_id', $cityId]), ['id', 'name']);

foreach ($departments as $department) {
    $item[$department->id] = $department->name;
}

echo json_encode($item);