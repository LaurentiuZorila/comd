<?php
require_once './../core/calendar-init.php';

$id = Input::post('id');

if ($id !== 'undefined') {
    $deleteEvent = $frontDb->delete(Params::TBL_EVENTS, ActionCond::where(['id', $id]));
    if ($deleteEvent) {
        echo 1;
    }
} else {
    echo 0;
}
?>