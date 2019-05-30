<?php
include "../../functions/autoload_ajax.php";
$model = new BackendProfile();

$cityId = Input::get('city_id');

$departments = $model->records(Params::TBL_DEPARTMENT, AC::where(['city_id', $cityId]), ['id', 'name'], ['ORDER BY' => 'name']);

foreach ($departments as $department) {
    $data[$department->id] = $department->name;
}

if (is_null($data)) {
    $data[] = Translate::t('not_found_offices', ['ucfirst']);
}
echo json_encode($data);