<?php
require_once './../core/calendar-init.php';
$userId = $frontUser->userId();
$results = $frontRecords->records(Params::TBL_EVENTS, ActionCond::where(['user_id', $userId]), ['*'], true);

foreach ($results as $key => $result) {
    $event[] = ['id' => $result->id, 'user_id' => $result->user_id, 'lead_id' => $result->lead_id, 'title' => $result->title, 'start' => $result->start, 'end' => $result->end];
}

echo json_encode($event);

?>