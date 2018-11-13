<?php
require_once './../core/calendar-init.php';

$id = Input::post('id');

$deleteEvent = $frontDb->delete(Params::TBL_EVENTS, ActionCond::where(['id', $id]));

if ($deleteEvent) {
    echo 1;
}
?>