<?php
include './../core/init-calendar.php';
$id = Input::post('id');

if ($id !== 'undefined') {
    $deleteEvent = $customerDb->delete(Params::TBL_EVENTS, ActionCond::where(['id', $id]));
    if ($deleteEvent) {
        echo 1;
    }
} else {
    echo 0;
}

?>