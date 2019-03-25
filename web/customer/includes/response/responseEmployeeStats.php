<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = [
        './../customerClasses/',
        './../../common/classes/',
        './../../customerClasses/',
        './../../../common/classes/'
    ];

    //for each directory
    foreach($directorys as $directory) {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php')) {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});

$db = CustomerDB::getInstance();
$statsId = Input::get('statsId');
$employeeId = Input::get('employeeId');

$update = $db->update(Params::TBL_EMPLOYEES,
    [
        'status' => $statsId
    ],
    [
        'id' => $employeeId
    ]);

if ($update) {
    echo 1;
} else {
    echo  0;
}