<?php
spl_autoload_register(function($class) {
    require_once '../classes/'. $class . '.php';
});

$prefix   = 'cmd_';
$table    = Input::get('table');
$table    = $prefix . $table;
$staff_id = Input::get('staff_id');


$allYears = DB::getInstance()->get($table, ['user_id', '=', $staff_id], ['year'])->results();

foreach ($allYears as $years) {
    $year[] = $years->year;
    $year = array_combine($year, $year);
}

echo json_encode($year);




