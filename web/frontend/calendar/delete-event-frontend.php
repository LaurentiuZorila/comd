<?php
require_once './../core/calendar-init.php';
$id = Input::post('id');
$deleteEvent = $frontDb->delete(Params::TBL_EVENTS, AC::where(['id', $id]));
$deleteNotification = $frontDb->delete(Params::TBL_NOTIFICATION, AC::where(['event_id', $id]));

if ($deleteEvent && $deleteNotification) {
    echo 1;
} else {
   echo 0;
}
?>