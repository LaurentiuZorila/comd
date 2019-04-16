<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = [
        './../backendClasses/',
        './../../backendClasses/',
        './../common/classes',
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

$db = BackendDB::getInstance();
$employeeId  = Input::get('employeeId');
$officeId    = Input::get('leadOfficeId');
$employeeName = Input::get('employeeName');
$allTables  = $db->get(Params::TBL_OFFICE, AC::where(['id', $leadOfficeId]),['tables'])->first();
foreach ($allTables as $table) {
    $tables = explode(',', $table);
}
// delete employee from all tables
try {
    Session::delete(Config::get('token/employeeDeleted'));
    $db->getPdo()->beginTransaction();

    $delete = $db->delete(Params::TBL_EMPLOYEES, AC::where(['id', $employeeId]));
    $eventDelete = $db->delete(Params::TBL_EVENTS, AC::where(['user_id', $employeeId]));
    if ($delete && $eventDelete) {
        foreach ($tables as $table) {
            $db->delete(Params::PREFIX . $table, AC::where(['employees_id', $employeeId]));
        }
    }
    $insetNotification = $db->insert(Params::TBL_NOTIFICATION, [
        'user_id'   => $employeeId,
        'lead_id'   => $officeId,
        'status'    => 2,
        'message'   => 'bk_employee_deleted'
    ]);
    $db->getPdo()->commit();
    Session::put(Config::get('token/employeeDeleted'), Tokens::getSubmitToken());
    Session::put(Config::get('notification/employeeName'), $employeeName);
} catch (PDOException $e) {
    echo $e->getMessage();
    $db->getPdo()->rollBack();
}

if (Session::exists(Config::get('token/employeeDeleted'))) {
    echo 1;
} else {
    echo 0;
}