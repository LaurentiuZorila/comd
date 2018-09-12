<?php
spl_autoload_register(function($class) {
    require_once '../classes/'. $class . '.php';
});

$employees_id   = $_GET['employees_id'];
$prefix         = 'cmd_';
// Get office id for selected user, form employees table
$offices_id = DB::getInstance()->get('cmd_employees', ['id', '=', $employees_id], ['offices_id'])->first();
// Get all tables from offices table
$allTables  = DB::getInstance()->get('cmd_offices', ['id', '=', $offices_id->offices_id], ['tables'])->first();
// Make array with selected tables
$tables  = explode(',', $allTables->tables);


foreach ($tables as $table) {
        $prefixTables[]  = $prefix . trim(strtolower($table));
    }

// Get all months form all tables
foreach ($prefixTables as $prefixTable) {
        $allMonths = DB::getInstance()->get($prefixTable, ['employees_id', '=', $employees_id], ['month'])->results();
    }

// Transform numeric months in textual months
foreach ($allMonths as $months) {
    foreach ($months as $month) {
        $numberMonths[] = $month;
        $textualMonths[] = Profile::getMonthsList()[$month];
        $month = array_combine($numberMonths, $textualMonths);
    }
}

// remove duplicates and print Json
echo json_encode(array_unique($month));