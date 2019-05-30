<?php
include "../../functions/autoload_ajax.php";
$data = new BackendProfile();

$table      = Input::get('table');
$table      = Params::PREFIX . trim($table);
$officesId  = Input::get('offices_id');
$year       = Input::get('year');
$langId     = Input::get('lang');
$language   = Params::LANG[$langId];

// Array with all months
$allMonths   = $data->records($table, [['offices_id', '=', $officesId], 'AND', ['year', '=', $year]], ['month']);


foreach ($allMonths as $months) {
    $month[$months->month] = Common::getMonths($language)[$months->month];
}

echo json_encode($month);