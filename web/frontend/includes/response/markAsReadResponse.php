<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = array(
        './../../common/classes/',
        './../../frontClasses/',
        './../../../common/classes/',
        './../../../frontClasses/'
    );
    //for each directory
    foreach($directorys as $directory) {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php')) {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});
$employeeId = Input::get('employeeId');
$frontDb    = FrontendDB::getInstance();
$view       = $frontDb->update(Params::TBL_NOTIFICATION, ['employee_view' => 1], ['user_id' => $employeeId]);
if ($view) {
    echo 1;
} else {
    echo 0;
}