<?php
spl_autoload_register(function($class) {
    require_once '../classes/'. $class . '.php';
});


$user_id = $_GET['id'];

$allEmployees = DB::getInstance()->get('cmd_employees', ['user_id', '=', $user_id])->results();

foreach ($allEmployees as $employees) {
    $employeesName[] = trim(strtoupper($employees->name));
    $employeesId[] = $employees->id;
    $employees = array_combine($employeesId, $employeesName);
}

echo json_encode($employees);