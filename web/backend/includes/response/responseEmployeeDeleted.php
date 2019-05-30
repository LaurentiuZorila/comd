<?php
include "../../functions/autoload_ajax.php";

$db = BackendDB::getInstance();
$employeeId  = Input::get('employeeId');
$officeId    = Input::get('leadOfficeId');
$employeeName = Input::get('employeeName');
$allTables  = $db->get(Params::TBL_OFFICE, AC::where(['id', $officeId]),['tables'])->first();
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
        'user_id'           => $employeeId,
        'lead_id'           => $officeId,
        'status'            => 2,
        'from_supervisors'  => 1,
        'message'           => 'bk_employee_deleted',
        'date'              => date('Y-m-d h:m:s'),
    ]);
    $db->getPdo()->commit();
    Session::put(Config::get('token/employeeDeleted'), Tokens::getSubmitToken());
    Session::put(Config::get('notification/employeeName'), $employeeName);
} catch (PDOException $e) {
    echo $e->getMessage();
    $db->getPdo()->rollBack();
}

if (Session::exists(Config::get('token/employeeDeleted'))) {
    Session::delete(Config::get('token/employeeDeleted'));
    echo 1;
} else {
    echo 0;
}