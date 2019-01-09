<?php
require_once './../core/calendar-init.php';

$id = Input::post('id');

if ($id !== 'undefined') {
    $deleteEvent = $frontDb->delete(Params::TBL_EVENTS, AC::where(['id', $id]));
    $frontDb->delete(Params::TBL_NOTIFICATION, AC::where(['event_id', $id]));
    if ($deleteEvent) {
        echo 1;
    }
} else {
    echo 0;
}
?>