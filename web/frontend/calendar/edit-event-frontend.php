<?php
require_once './../core/calendar-init.php';

$id     = Input::post('id');
$title  = Input::post('title');
$start  = Input::post('start');
$end    = Input::post('end');

$frontDb->update(Params::TBL_EVENTS, [
    'title' => $title,
    'start' => $start,
    'end'   => $end
], [
    'id'    => $id
]);

?>