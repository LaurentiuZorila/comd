<?php
spl_autoload_register(function($class) {
    require_once '../classes/'. $class . '.php';
});


$prefix   = 'cmd_';
$table    = Input::get('table');
$table    = $prefix . $table;
$staff_id = Input::get('staff_id');
$year     = Input::get('year');


$allMonths = DB::getInstance()->get($table, [['user_id', '=', $staff_id], 'AND', ['year', '=', $year]], ['month'])->results();

foreach ($allMonths as $months) {
    $month[$months->month] = Profile::getMonthsList()[$months->month];

}

echo json_encode($month);