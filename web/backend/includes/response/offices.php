<?php
include "../../functions/autoload_ajax.php";
$common = new BackendProfile();

$departmentsId = Input::get('departments_id');

$offices = $common->records(Params::TBL_OFFICE, AC::where(['departments_id', $departmentsId]), ['id', 'name']);

foreach ($offices as $office) {
    $data[$office->id] = $office->name;
}

if (is_null($data)) {
    $data[] = Translate::t('not_found_offices', ['ucfirst']);
}
echo json_encode($data);