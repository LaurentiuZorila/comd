<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = [
        './../customerClasses/',
        './../../customerClasses/',
        '../../../common/classes/'
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
$db             = CustomerDB::getInstance();
$employeeId     = Input::get('employeeId');
$leadOfficeId   = Input::get('leadOfficeId');
$officeId       = Input::get('offices_id');
$departmentsId  = Input::get('departments_id');

$allTables  = $db->get(Params::TBL_OFFICE, AC::where(['id', $leadOfficeId]),['tables'])->first();
foreach ($allTables as $table) {
    $tables = explode(',', $table);
}
// delete employee from all tables
try {
    Session::delete(Config::get('token/employee_deleted'));
    $db->getPdo()->beginTransaction();

    $delete = $db->delete(Params::TBL_EMPLOYEES, AC::where(['id', $employeeId]));
    $eventDelete = $db->delete(Params::TBL_EVENTS, AC::where(['user_id', $employeeId]));
    if ($delete && $eventDelete) {
        foreach ($tables as $table) {
            $db->delete(Params::PREFIX . $table, AC::where(['employees_id', $employeeId]));
        }
    }

    $db->getPdo()->commit();
    Session::put(Config::get('token/employee_deleted'), Tokens::getSubmitToken());
} catch (PDOException $e) {
    echo $e->getMessage();
    $db->getPdo()->rollBack();
}

if (Session::exists(Config::get('token/employee_deleted'))) {
    Session::delete(Config::get('token/employee_deleted'));
    echo 1;
} else {
    echo 0;
}