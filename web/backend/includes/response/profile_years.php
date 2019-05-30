<?php
include "../../functions/autoload_ajax.php";

$table    = Input::get('table');
$table    = Params::PREFIX . $table;
$staff_id = Input::get('staff_id');


$allYears = BackendDB::getInstance()->get($table, ['user_id', '=', $staff_id], ['year'])->results();

foreach ($allYears as $years) {
    $year[] = $years->year;
    $year = array_combine($year, $year);
}

echo json_encode($year);




